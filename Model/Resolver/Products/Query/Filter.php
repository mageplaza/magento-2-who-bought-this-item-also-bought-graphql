<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_AlsoBoughtGraphQl
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */
declare(strict_types=1);

namespace Mageplaza\AlsoBoughtGraphQl\Model\Resolver\Products\Query;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as CatalogCollectionFactory;
use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product;
use Magento\CatalogGraphQl\Model\Resolver\Products\SearchResult;
use Magento\CatalogGraphQl\Model\Resolver\Products\SearchResultFactory;
use Magento\Framework\GraphQl\Query\FieldTranslator;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\AlsoBought\Model\ResourceModel\Associate\CollectionFactory;

/**
 * Retrieve filtered product data based off given search criteria in a format that GraphQL can interpret.
 */
class Filter
{
    /**
     * @var SearchResultFactory
     */
    protected $searchResultFactory;

    /**
     * @var Product
     */
    protected $productDataProvider;

    /**
     * @var FieldTranslator
     */
    protected $fieldTranslator;

    /**
     * @var Resolver
     */
    protected $layerResolver;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var CollectionFactory
     */
    protected $associateCollectionFactory;

    /**
     * @var CatalogCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Filter constructor.
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SearchResultFactory $searchResultFactory
     * @param Product $productDataProvider
     * @param Resolver $layerResolver
     * @param FieldTranslator $fieldTranslator
     * @param ProductRepositoryInterface $productRepository
     * @param CollectionFactory $associateCollectionFactory
     * @param CatalogCollectionFactory $productCollectionFactory
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SearchResultFactory $searchResultFactory,
        Product $productDataProvider,
        Resolver $layerResolver,
        FieldTranslator $fieldTranslator,
        ProductRepositoryInterface $productRepository,
        CollectionFactory $associateCollectionFactory,
        CatalogCollectionFactory $productCollectionFactory
    ) {
        $this->searchResultFactory        = $searchResultFactory;
        $this->productDataProvider        = $productDataProvider;
        $this->fieldTranslator            = $fieldTranslator;
        $this->layerResolver              = $layerResolver;
        $this->productRepository          = $productRepository;
        $this->associateCollectionFactory = $associateCollectionFactory;
        $this->productCollectionFactory   = $productCollectionFactory;
        $this->searchCriteriaBuilder      = $searchCriteriaBuilder;
    }

    /**
     * @param ResolveInfo $info
     * @param array $args
     * @param bool $isSearch
     *
     * @return SearchResult
     */
    public function getResult(
        ResolveInfo $info,
        array $args,
        bool $isSearch = false
    ): SearchResult {
        $fields         = $this->getProductFields($info);
        $searchCriteria = $this->searchCriteriaBuilder->build('mpalsobought', $args);
        $searchCriteria->setPageSize(100);
        $searchCriteria->setCurrentPage(1);
        $products   = $this->productDataProvider->getList($searchCriteria, $fields, $isSearch);
        $productIds = [];
        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($products->getItems() as $product) {
            $productIds[] = $product->getId();
        }

        $associateProductIds = $this->associateCollectionFactory->create()
            ->getProductListByIds($productIds);
        $productCollection   = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect('*')->addFieldToFilter('entity_id', ['in' => $associateProductIds]);
        $total = $productCollection->getSize();
        $productCollection->setPageSize($args['pageSize'])->setCurPage($args['currentPage']);
        $productArray = [];
        /** @var \Magento\Catalog\Model\Product $item */
        foreach ($productCollection->getItems() as $item) {
            $productId                         = $item->getId();
            $productArray[$productId]          = $item->getData();
            $productArray[$productId]['model'] = $item;
        }

        return $this->searchResultFactory->create($total, $productArray);
    }

    /**
     * Return field names for all requested product fields.
     *
     * @param ResolveInfo $info
     *
     * @return string[]
     */
    protected function getProductFields(ResolveInfo $info): array
    {
        $fieldNames = [];
        foreach ($info->fieldNodes as $node) {
            if ($node->name->value !== 'mpalsobought') {
                continue;
            }
            foreach ($node->selectionSet->selections as $selection) {
                if ($selection->name->value !== 'items') {
                    continue;
                }

                foreach ($selection->selectionSet->selections as $itemSelection) {
                    if ($itemSelection->kind === 'InlineFragment') {
                        foreach ($itemSelection->selectionSet->selections as $inlineSelection) {
                            if ($inlineSelection->kind === 'InlineFragment') {
                                continue;
                            }
                            $fieldNames[] = $this->fieldTranslator->translate($inlineSelection->name->value);
                        }
                        continue;
                    }
                    $fieldNames[] = $this->fieldTranslator->translate($itemSelection->name->value);
                }
            }
        }

        return $fieldNames;
    }
}

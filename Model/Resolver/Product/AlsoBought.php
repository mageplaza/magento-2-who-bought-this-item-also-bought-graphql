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

namespace Mageplaza\AlsoBoughtGraphQl\Model\Resolver\Product;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as CatalogCollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\AlsoBought\Model\ResourceModel\Associate\CollectionFactory;

/**
 * Class AlsoBought
 * @package Mageplaza\AlsoBoughtGraphQl\Model\Resolver\Product
 */
class AlsoBought implements ResolverInterface
{
    /**
     * @var CollectionFactory
     */
    protected $associateCollectionFactory;

    /**
     * @var CatalogCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * AlsoBought constructor.
     *
     * @param CollectionFactory $associateCollectionFactory
     * @param CatalogCollectionFactory $productCollectionFactory
     */
    public function __construct(
        CollectionFactory $associateCollectionFactory,
        CatalogCollectionFactory $productCollectionFactory
    ) {
        $this->associateCollectionFactory = $associateCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($value['entity_id'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        $productArray = [];
        $productIds = [$value['entity_id']];
        $associateProductIds = $this->associateCollectionFactory->create()
            ->getProductListByIds($productIds);
        $productCollection   = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect('*')->addFieldToFilter('entity_id', ['in' => $associateProductIds]);
        /** @var \Magento\Catalog\Model\Product $item */
        foreach ($productCollection->getItems() as $item) {
            $productId = $item->getId();
            $productArray[$productId]          = $item->getData();
            $productArray[$productId]['model'] = $item;
        }

        return $productArray;
    }
}

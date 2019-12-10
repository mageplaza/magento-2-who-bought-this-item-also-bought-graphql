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

namespace Mageplaza\AlsoBoughtGraphQl\Model\Resolver\Product\ProductImage;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product as ProductResourceModel;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Label
 * @package Mageplaza\AlsoBoughtGraphQl\Model\Resolver\Product\ProductImage
 */
class Label implements ResolverInterface
{
    /**
     * @var ProductResourceModel
     */
    protected $productResource;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Label constructor.
     *
     * @param ProductResourceModel $productResource
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ProductResourceModel $productResource,
        StoreManagerInterface $storeManager
    ) {
        $this->productResource = $productResource;
        $this->storeManager    = $storeManager;
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
        if (isset($value['label'])) {
            return $value['label'];
        }

        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        /** @var Product $product */
        $product   = $value['model'];
        $productId = (int) $product->getEntityId();
        if (!isset($value['image_type'])) {
            return $this->getAttributeValue($productId, 'name');
        }
        $imageType  = $value['image_type'];
        $imageLabel = $this->getAttributeValue($productId, $imageType . '_label');
        if ($imageLabel === null) {
            $imageLabel = $this->getAttributeValue($productId, 'name');
        }

        return $imageLabel;
    }

    /**
     * Get attribute value
     *
     * @param int $productId
     * @param string $attributeCode
     *
     * @return null|string Null if attribute value is not exists
     * @throws NoSuchEntityException
     */
    private function getAttributeValue(int $productId, string $attributeCode): ?string
    {
        $storeId = $this->storeManager->getStore()->getId();

        $value = $this->productResource->getAttributeRawValue($productId, $attributeCode, $storeId);

        return is_array($value) && empty($value) ? null : $value;
    }
}

<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2017 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\DataObject;

class VisibleProductResolver
{
    private $visibleProducts = [];
    private $configurableType;
    private $productFactory;

    public function __construct(Configurable $configurableType, ProductFactory $productFactory)
    {
        $this->configurableType = $configurableType;
        $this->productFactory = $productFactory;
    }

    public function getVisibleProduct(Product $product)
    {
        $result = null;

        if (!isset($this->visibleProducts[$product->getId()])) {
            if ($product->getVisibility() == Product\Visibility::VISIBILITY_NOT_VISIBLE) {
                $parentIds = $this->configurableType->getParentIdsByChild($product->getId());

                foreach ($parentIds as $parentId) {
                    /* @var Product $parentProduct */
                    if (isset($this->visibleProducts[$parentId])) {
                        $parentProduct = $this->visibleProducts[$parentId];
                    } else {
                        $parentProduct = $this->productFactory
                            ->create()
                            ->load($parentId);
                        $this->visibleProducts[$parentId] = $parentProduct;
                    }
                    if ($parentProduct->getVisibility() != Product\Visibility::VISIBILITY_NOT_VISIBLE
                        && $parentProduct->getStatus() == Product\Attribute\Source\Status::STATUS_ENABLED
                    ) {
                        $result = $this->visibleProducts[$product->getId()] = $parentProduct;

                        if ($parentProduct->getColor() == $product->getColor()) {
                            break;
                        }
                    }
                }
            } else {
                $this->visibleProducts[$product->getId()] = $product;
                $result = $product;
            }
        } else {
            $result = $this->visibleProducts[$product->getId()];
        }
        if ($result === null) {
            throw new \DomainException('Product is not visible.');
        }
        $result->addCustomOption('simple_product', $product, $product); // for correct price calculation

        return $result;
    }
}

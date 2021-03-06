<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2017 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Guidance\GooglePLA\Model\Feed\ColumnRendererInterface;
use Magento\Catalog\Model\Product;

class SalePrice implements ColumnRendererInterface
{
    private $productResolver;

    public function __construct(VisibleProductResolver $productResolver)
    {
        $this->productResolver = $productResolver;
    }

    public function render(Product $product)
    {
        $product = $this->productResolver->getVisibleProduct($product);

        return ($product->getFinalPrice() != $product->getPrice())
            ? round($product->getFinalPrice(), 2) . ' USD'
            : '';
    }
}

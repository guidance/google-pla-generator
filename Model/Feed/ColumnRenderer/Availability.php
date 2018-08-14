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

class Availability implements ColumnRendererInterface
{
    public function render(Product $product)
    {
        return $product->isSalable() ? 'in stock' : 'out of stock';
    }
}

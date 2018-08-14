<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2018 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Magento\Catalog\Model\Product;

class AttributeOptionNormalized extends AttributeOption
{
    protected function getValue(Product $product)
    {
        return ucwords(strtolower(parent::getValue($product)));
    }
}

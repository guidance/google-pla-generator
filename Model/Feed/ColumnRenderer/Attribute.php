<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2018 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Magento\Catalog\Model\Product;

class Attribute extends Base
{
    private $attribute;

    public function __construct($attribute, $limit = null, $default = null)
    {
        parent::__construct($limit, $default);

        $this->attribute = $attribute;
    }

    public function getValue(Product $product)
    {
        return $product->getDataUsingMethod($this->attribute);
    }
}

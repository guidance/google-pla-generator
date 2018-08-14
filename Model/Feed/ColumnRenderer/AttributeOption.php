<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2017 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Magento\Catalog\Model\Product;

class AttributeOption extends Base
{
    private $attribute;

    public function __construct(string $attribute, $limit = null, $default = null)
    {
        parent::__construct($limit, $default);

        $this->attribute = $attribute;
    }

    protected function getValue(Product $product)
    {
        return $product->getAttributeText($this->attribute);
    }
}

<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2017 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Magento\Catalog\Model\Product;

class Title extends Base
{
    private $productResolver;

    public function __construct(
        VisibleProductResolver $productResolver,
        $limit = null,
        $default = null
    ) {
        parent::__construct($limit, $default);

        $this->productResolver = $productResolver;
    }

    protected function getValue(Product $product)
    {
        $name = $this->productResolver->getVisibleProduct($product)->getName();
        $color = $product->getAttributeText('color_general');

        return ucwords(strtolower("$name - $color"));
    }
}

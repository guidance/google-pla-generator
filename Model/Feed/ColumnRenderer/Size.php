<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2018 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Magento\Catalog\Model\Product;
use JohnnyWas\SizeChart\Helper\Data as SizeChartHelper;

class Size extends Base
{
    private $sizeHelper;

    public function __construct(SizeChartHelper $sizeHelper, $limit = null, $default = null)
    {
        parent::__construct($limit, $default);

        $this->sizeHelper = $sizeHelper;
    }

    protected function getValue(Product $product)
    {
        return $product->getAttributeText($this->sizeHelper->getAttributeCode($product));
    }
}

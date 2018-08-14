<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2018 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Magento\Catalog\Model\Product;

class Description extends Base
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
        try {
            $product = $this->productResolver->getVisibleProduct($product);
            $result = strip_tags($product->getDescription());
            $result = preg_replace('/\n/', '', $result);
            $result = preg_replace('/\r/', '', $result);

            return $result;
        } catch (\Exception $e) {
            return null;
        }
    }
}

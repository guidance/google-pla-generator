<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2018 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Magento\Catalog\Model\Product;

class ParentSku extends Base
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
        $parentProduct = $this->productResolver->getVisibleProduct($product);

        if ($parentProduct->getId() != $product->getId()) {
            return $parentProduct->getData('sku'); // Direct access to data, because configurable
        }                                               // will return SKU of simple if simple option is set.
        return '';
    }
}

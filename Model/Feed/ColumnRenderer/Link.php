<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2018 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Store\Model\StoreManagerInterface;

class Link extends Base
{
    private $storeManager;
    private $productResolver;
    private $productFactory;

    public function __construct(
        StoreManagerInterface $storeManager,
        VisibleProductResolver $productResolver,
        ProductFactory $productFactory,
        $limit = null,
        $default = null
    ) {
        parent::__construct($limit, $default);

        $this->storeManager = $storeManager;
        $this->productResolver = $productResolver;
        $this->productFactory = $productFactory;
    }

    protected function getValue(Product $product)
    {
        $product = $this->productResolver->getVisibleProduct($product);
        $store = $this->storeManager->getStore('default');

        return $this->productFactory
            ->create()
            ->setStoreId($store->getId())
            ->load($product->getId())
            ->getProductUrl();
    }
}

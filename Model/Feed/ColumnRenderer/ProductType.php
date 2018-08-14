<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2017 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class ProductType extends Base
{
    private $categories;
    private $storeManager;
    private $productResolver;

    public function __construct(
        VisibleProductResolver $productResolver,
        StoreManagerInterface $storeManager,
        $limit = null,
        $default = null
    ) {
        parent::__construct($limit, $default);

        $this->productResolver = $productResolver;
        $this->storeManager = $storeManager;
    }

    protected function getValue(Product $product)
    {
        if (!isset($this->categories[$product->getId()])) {
            $product = $this->productResolver->getVisibleProduct($product);
            $store = $this->storeManager->getStore('default'); /* @var $store Store */

            $category = $product->getCategoryCollection() /* @var $category Category */
                ->addFieldToFilter('is_active', 1)
                ->setOrder('level', 'desc')
                ->getFirstItem();

            $pathIds = [];
            $result = [];

            if ($category->getId()) {
                $path = $category->getPathIds();

                foreach ($path as $itemId) {
                    if ($itemId == $store->getRootCategoryId()) {
                        continue;
                    }
                    $pathIds[] = $itemId;
                }
                $categories = $category->getParentCategories();

                foreach ($pathIds as $categoryId) {
                    if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) {
                        $categoryName = trim($categories[$categoryId]->getName());

                        if ($categoryName == 'Sale') {
                            $categoryName = 'Clothing';
                        }
                        $result[] = $categoryName;
                    }
                }
            }
            $result = implode(' > ', $result);
            $result = preg_replace('/,/', '\,', $result);
            $this->categories[$product->getId()] = $result;
        } else {
            $result = $this->categories[$product->getId()];
        }
        return $result;
    }
}

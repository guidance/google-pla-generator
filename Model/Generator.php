<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2018 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model;

use Guidance\GooglePLA\Model\Feed\ColumnRenderer\VisibleProductResolver;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\CatalogInventory\Model\Stock;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class Generator implements LoggerAwareInterface
{
    const PAGE_SIZE = 200;

    private $eavConfig;
    private $storeManager;
    private $prodCollFactory;
    private $visibleProductResolver;
    private $logger;

    public function __construct(
        EavConfig $eavConfig,
        StoreManagerInterface $storeManager,
        ProductCollectionFactory $prodCollFactory,
        ScopeConfigInterface $config,
        FeedFactory $feedFactory,
        LoggerInterface $logger,
        VisibleProductResolver $visibleProductResolver,
        $feedFileName = null
    ) {
        $this->eavConfig = $eavConfig;
        $this->storeManager = $storeManager;
        $this->prodCollFactory = $prodCollFactory;
        $this->feedFactory = $feedFactory;
        $this->visibleProductResolver = $visibleProductResolver;
        $this->logger = $logger;
    }

    /**
     * Generates Google PLA feed
     *
     * @param string $fileName Full file name. If file exists it will be overwritten
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function generate($fileName)
    {
        $products = $this->prodCollFactory->create()
            ->addAttributeToSelect('*')
            ->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner')
            ->joinField('websites', 'catalog_product_website', 'website_id', 'product_id=entity_id', null, 'left')
            ->addAttributeToFilter('type_id', Product\Type::TYPE_SIMPLE)
            ->addAttributeToFilter('status', Product\Attribute\Source\Status::STATUS_ENABLED)
            ->addAttributeToFilter('websites', $this->storeManager->getWebsite('base')->getId())
            ->addUrlRewrite()
            ->joinField('stock_status', 'cataloginventory_stock_status', 'stock_status', 'product_id=entity_id', [
                'stock_status' => Stock\Status::STATUS_IN_STOCK,
                'website_id' => 0
            ])
            ->setOrder('entity_id', 'asc');

        try {
            $start = time();
            $feed = $this->feedFactory->create(['fileName' => $fileName, 'logger' => $this->logger]);
            $count = $products->getSize();
            $this->logger->info(sprintf('Feed generation started (%s)', realpath($fileName)));
            $this->logger->info(sprintf('There are %d products to export', $count));

            for ($products->setPage($page = 1, self::PAGE_SIZE); $page <= $products->getLastPageNumber(); $products->setCurPage(++$page)->clear()) {
                foreach ($products as $product) {
                    try {
                        if (!$product->isSalable()) {
                            continue;
                        }
                        $visibleProduct = $this->visibleProductResolver->getVisibleProduct($product);

                        if (!$visibleProduct->isSalable()) {
                            continue;
                        }
                        $feed->addProduct($product);
                    } catch (\Exception $e) {
                        $this->logger->warning("Product #{$product->getId()}: {$e->getMessage()}");
                    }
                }
                $this->logger->info(sprintf(
                    'Progress: %d%% (%d / %d). ETA: %ds',
                    round(100 * ($page / $products->getLastPageNumber())),
                    $page,
                    $products->getLastPageNumber(),
                    (((time() - $start) / $page * $products->getLastPageNumber()) - time() + $start)
                ));
            }
            $this->logger->info("Feed generation finished in " . (time() - $start) .  "s ($fileName)");
        } catch (\Exception $e) {
            $this->logger->debug((string)$e);
            $this->logger->critical("Error occurred on product Google PLA feed generation ($fileName)");
        }
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}

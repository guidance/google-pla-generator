<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2018 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Guidance\GooglePLA\Model\Feed\ColumnRendererInterface;
use Magento\Catalog\Model\Product;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class Base implements ColumnRendererInterface, LoggerAwareInterface
{
    private $logger;
    private $limit;
    private $default;

    protected function getValue(Product $product)
    {
        return null;
    }

    public function __construct($limit = null, $default = null)
    {
        $this->limit = $limit;
        $this->default = $default;
    }

    public function render(Product $product)
    {
        try {
            $value = $this->getValue($product);
        } catch (\Exception $e) {
            $this->logger->warning("#{$product->getId()}: {$e->getMessage()} (" . get_class($this) . ')');
            $value = null;
        }
        if ($this->default !== null && empty($value)) {
            $value = $this->default;
        }
        if ($this->limit !== null) {
            $value = substr($value, 0, $this->limit);
        }
        return $value;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}

<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2018 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model;

use Magento\Catalog\Model\Product;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class Feed
{
    private $file;
    private $renderers = [];
    private $logger;

    public function __construct($fileName, array $columnRenderers, LoggerInterface $logger)
    {
        $this->file = new \SplFileObject($fileName, 'w');
        $this->logger = $logger;

        foreach ($columnRenderers as $column => $renderer) {
            $this->addRenderer($column, $renderer);
        }
        $this->addHeader();
    }

    public function addProduct(Product $product)
    {
        $line = [];

        foreach ($this->renderers as $renderer) {
            $line[] = $renderer->render($product);
        }
        $filtered = array_filter($line);

        if (empty($filtered)) {
            throw new \DomainException("No data for product #{$product->getId()}");
        }
        $this->addLine($line);
    }

    private function addRenderer($column, Feed\ColumnRendererInterface $renderer)
    {
        if ($renderer instanceof LoggerAwareInterface) {
            $renderer->setLogger($this->logger);
        }
        $this->renderers[$column] = $renderer;
    }

    private function addHeader()
    {
        $this->addLine(array_keys($this->renderers));
    }

    private function addLine(array $line)
    {
        foreach ($line as &$field) {
            $field = str_replace("\t", ' ', $field);
        }
        $this->file->fwrite(implode("\t", $line) . "\n");
    }
}

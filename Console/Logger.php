<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2017 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Console;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Logger implements LoggerInterface
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function log($level, $message, array $context = array())
    {
        $this->output->writeln($message);
    }

    /**
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function emergency($message, array $context = array())
    {
        $this->log(null, $message);
    }

    /**
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function alert($message, array $context = array())
    {
        $this->log(null, $message);
    }

    /**
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function critical($message, array $context = array())
    {
        $this->log(null, $message);
    }

    /**
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function error($message, array $context = array())
    {
        $this->log(null, $message);
    }

    /**
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function warning($message, array $context = array())
    {
        $this->log(null, $message);
    }

    /**
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function notice($message, array $context = array())
    {
        $this->log(null, $message);
    }

    /**
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function info($message, array $context = array())
    {
        $this->log(null, $message);
    }

    /**
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function debug($message, array $context = array())
    {
        $this->log(null, $message);
    }
}

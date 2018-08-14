<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2018 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model;

class LoggerHandler extends \Magento\Framework\Logger\Handler\Base
{
    protected $fileName = '/var/log/google_pla.log';
}

<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2018 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model;

use Magento\Cron\Model\Schedule;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class ScheduledGenerator
{
    const CONFIG_FEED_FILENAME = 'googlepla/settings/feed_filename';

    protected $config;
    protected $generator;
    protected $pathMap = [DirectoryList::PUB, DirectoryList::MEDIA, DirectoryList::ROOT];

    public function __construct(ScopeConfigInterface $config, Generator $generator, Filesystem $fileSystem)
    {
        $this->config = $config;
        $this->generator = $generator;
        $map = [];

        foreach ($this->pathMap as $key) {
            $map["{{$key}}"] = $fileSystem->getDirectoryRead($key)->getAbsolutePath();
        }
        $this->pathMap = $map;
    }

    protected function getFeedFileName()
    {
        $fileName = trim($this->config->getValue(self::CONFIG_FEED_FILENAME));
        $fileName = str_replace(array_keys($this->pathMap), array_values($this->pathMap), $fileName);

        if (!touch($fileName)) {
            throw new \DomainException("Unable to open $fileName");
        }
        return $fileName;
    }

    /**
     * @param Schedule $schedule Schedule executed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function generateBySchedule(Schedule $schedule)
    {
        $this->generator->generate($this->getFeedFileName());
    }
}

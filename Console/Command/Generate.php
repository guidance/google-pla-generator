<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2017 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Console\Command;

use Guidance\GooglePLA\Console\LoggerFactory;
use Guidance\GooglePLA\Model\Generator;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Generate extends Command
{
    const ARG_FEED_FILE_NAME = 'feed_file_name';

    private $feedGenerator;
    private $loggerFactory;
    private $appState;

    public function __construct(Generator $feedGenerator, LoggerFactory $loggerFactory, State $appState)
    {
        $this->appState = $appState;
        parent::__construct();

        $this->feedGenerator = $feedGenerator;
        $this->loggerFactory = $loggerFactory;
    }

    protected function configure()
    {
        $this->setName('google:pla:generate')
            ->setDescription('Generates Google PLA feed')
            ->addArgument(self::ARG_FEED_FILE_NAME, InputArgument::REQUIRED, 'Feed file name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO: This may no longer needed after upgrading from 2.1.6, for now hot fix.
        // "Area code already set on attempting to execute bin/magento catalog:images:resize

        // Issue Report: https://github.com/magento/magento2/issues/8770
        // Details: http://devdocs.magento.com/guides/v2.1/release-notes/tech_bull_216-imageresize.html
        try {
            $this->appState->setAreaCode(FrontNameResolver::AREA_CODE);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            // intentionally left empty
        }

        $fileName = $input->getArgument(self::ARG_FEED_FILE_NAME);
        $logger = $this->loggerFactory->create(['output' => $output]);
        $this->feedGenerator->setLogger($logger);
        $this->feedGenerator->generate($fileName);
    }
}

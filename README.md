# Google Product Listing Ads Generator

This module provides customizable generator of Google PLA Feed. It's not ready 
to use solution, rather a framework.

## Installation

The easiest way to install is to use Composer:

```
composer config repositories.pla vcs https://github.com/guidance/google-pla-generator
composer require guidance/module-google-pla
``` 

## Configuration 
There are few points for configuration this module:

1. To configure columns of the feed you need to pass ordered array of column renderers to 
   `Guidance\GooglePLA\Model\Feed` in `columnRenderers` (it's configured in `etc/di.xml`).
   All passed rendereres should implement `Guidance\GooglePLA\Model\Feed\ColumnRendererInterface`.
   There are plenty of ready to used renderers in `Model/Feed/ColumnRenderer/` folder that use.
     
2. Periodic feed generation can be configured in `etc/crontab.xml.`

3. Feed location can be configured on _Stores > Configuration > Catalog > Google PLA_ page of admin panel.

Also feed can be generated with Magento CLI command:

```
bin/magento google:pla:generate feed.txt
``` 

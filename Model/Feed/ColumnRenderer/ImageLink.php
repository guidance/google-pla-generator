<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     GooglePLA
 * @copyright   Copyright (c) 2018 Guidance Solutions (http://www.guidance.com)
 */
namespace Guidance\GooglePLA\Model\Feed\ColumnRenderer;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Catalog\Helper\Image as ImageHelper;

class ImageLink extends Base
{
    private $productResolver;
    private $eavConfig;
    private $imageHelper;

    public function __construct(
        VisibleProductResolver $productResolver, 
        EavConfig $eavConfig,
        ImageHelper $imageHelper,
        $limit = null,
        $default = null
    ) {
        parent::__construct($limit, $default);

        $this->productResolver = $productResolver;
        $this->eavConfig = $eavConfig;
        $this->imageHelper = $imageHelper;
    }

    protected function getValue(Product $product)
    {
        $color = $product->getColor();
        $visible = $this->productResolver->getVisibleProduct($product);
        $colorAttrId = $this->eavConfig->getAttribute(Product::ENTITY, 'color')->getId();

        foreach ($visible->getMediaGalleryImages() as $image) {
            $url = $this->imageHelper
                ->init($visible, 'product_thumbnail_image')
                ->setImageFile($image->getFile())
                ->getUrl();

            if ($this->isPlaceholder($url)) {
                continue;
            }
            if (preg_match("@filter_{$colorAttrId}_$color@i", $image->getLabel())) {
                return $url;
            }
            if (!isset($firstImageUrl)) {
                $firstImageUrl = $url;
            }
        }
        $url = $this->imageHelper->init($visible, 'product_thumbnail_image')
            ->setImageFile($visible->getImage())
            ->getUrl();

        if (!$this->isPlaceholder($url)) {
            return $url;
        }
        if (isset($firstImageUrl)) {
            return $firstImageUrl;
        }
        throw new \Exception("Product image is not found. Visible product ID: {$visible->getId()}");
    }

    protected function isPlaceholder($url)
    {
        return $url === null || strpos($url, 'placeholder') !== false;
    }
}

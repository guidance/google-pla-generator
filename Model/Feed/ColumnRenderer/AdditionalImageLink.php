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

class AdditionalImageLink extends Base
{
    const MAX_LENGTH = 2000;
    const MAX_COUNT = 10;

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
        $result = $this->extractImages($visible, "@filter_{$colorAttrId}_$color@i") ?: $this->extractImages($visible);

        return implode(',', $result);
    }

    protected function extractImages(Product $product, $labelFilterRegexp = null)
    {
        $result = [];
        $totalLength = 0;

        foreach ($product->getMediaGalleryImages() as $image) {
            if (null === $labelFilterRegexp || preg_match($labelFilterRegexp, $image->getLabel())) {
                $imageUrl = $this->imageHelper->init($product, 'product_thumbnail_image')
                    ->setImageFile($image->getFile())
                    ->getUrl();

                if (strpos($imageUrl, 'placeholder') === false) {
                    $totalLength += strlen($imageUrl);
                    // count($result) is number of commas to separate images.
                    if ($totalLength + count($result) > self::MAX_LENGTH) {
                        break;
                    }
                    $result[] = $imageUrl;
                }
            }
            // As per documentation: "You can include up to 10 additional images per item."
            if (count($result) >= self::MAX_COUNT) {
                break;
            }
        }
        return $result;
    }
}

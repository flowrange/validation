<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Image validation
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class ImageRule extends FileRule
{

    /**
     * Error : invalid image
     * @var string
     */
    const ERR_INVALID_IMAGE = 'image.invalid-image';


    /**
     * Error : width too large
     * @var string
     */
    const ERR_WIDTH_TOO_LARGE = 'image.width-too-large';


    /**
     * Error : height too large
     * @var string
     */
    const ERR_HEIGHT_TOO_LARGE = 'image.height-too-large';


    /**
     * Max width
     * @var int
     */
    protected $maxWidth;


    /**
     * Max height
     * @var int
     */
    protected $maxHeight;


    /**
     * Set the max width
     *
     * @param int $maxWidth Max width (pixels)
     *
     * @return self
     */
    public function maxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;
        return $this;
    }


    /**
     * Set the max height
     *
     * @param int $maxHeight Max height (pixels)
     *
     * @return self
     */
    public function maxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;
        return $this;
    }


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        $parentRule = parent::check($value, $validator);
        if ($parentRule->isValid()) {

            $file       = $parentRule->getValue()['tmp_name'];
            $imageInfos = @getimagesize($file);
            if($imageInfos) {

                list($width, $height) = $imageInfos;

                if ($this->maxWidth > 0 && $width > $this->maxWidth) {

                    return RuleCheckResult::make(false, $value, self::ERR_WIDTH_TOO_LARGE);
                }

                if ($this->maxHeight > 0 && $height > $this->maxHeight) {

                    return RuleCheckResult::make(false, $value, self::ERR_HEIGHT_TOO_LARGE);
                }

                return RuleCheckResult::make(true, $parentRule->getValue());

            } else {

                return RuleCheckResult::make(false, $value, self::ERR_INVALID_IMAGE);
            }
        }
    }
}

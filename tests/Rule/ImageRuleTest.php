<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Rule\FileRule;
use Flowrange\Validation\Rule\ImageRule;
use Flowrange\Validation\Rule as r;


/**
 * MFW_Validation_Rule_Image tests
 *
 * @author $Author: florent $
 * @copyright Copyright © 2010 Médiagonale SARL
 * @package MFW_Validation_
 * @subpackage Rule_test
 * @since 1.4
 * @version $Id: ImageTest.php 2234 2010-10-28 13:32:13Z florent $
 */
class ImageRuleTest extends RuleTestCase
{


    public function initDataBuilder()
    {
        $image = array(
            'name' => 'image.jpg',
            'type' => 'derp',
            'size' => 30451,
            'tmp_name' => __DIR__ . '/ImageRuleTest/image.jpg',
            'error' => UPLOAD_ERR_OK);

        $expectedImage = array(
            'name' => 'image.jpg',
            'type' => 'image/jpeg',
            'size' => 30451,
            'tmp_name' => __DIR__ . '/ImageRuleTest/image.jpg',
            'error' => UPLOAD_ERR_OK);

        return $this->getDataBuilder(r\image())

            ->areValid()

                ->test('valid image')
                    ->value($image)->returns($expectedImage)

                ->test('within boundaries')
                    ->value($image)->returns($expectedImage)
                    ->withRule(r\image()->maxWidth(640)->maxHeight(480))

                ->test('exact dimensions')
                    ->value($image)->returns($expectedImage)
                    ->withRule(r\image()->maxWidth(300)->maxHeight(319))

            ->areNotValid()

                ->test('width and height too large')
                    ->value($image)
                    ->withRule(r\image()->maxWidth(10)->maxHeight(10))
                    ->error(ImageRule::ERR_WIDTH_TOO_LARGE)

                ->test('width only too large')
                    ->value($image)
                    ->withRule(r\image()->maxWidth(10)->maxHeight(480))
                    ->error(ImageRule::ERR_WIDTH_TOO_LARGE)

                ->test('height only too large')
                    ->value($image)
                    ->withRule(r\image()->maxWidth(640)->maxHeight(10))
                    ->error(ImageRule::ERR_HEIGHT_TOO_LARGE)

                ->test('not an image')
                    ->value(
                        array(
                            'name' => 'file.txt',
                            'type' => 'text/plain',
                            'size' => 1,
                            'tmp_name' => __DIR__ . '/ImageRuleTest/file.txt',
                            'error' => UPLOAD_ERR_OK))
                    ->error(ImageRule::ERR_INVALID_IMAGE);
    }


}
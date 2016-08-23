<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Rule\FileRule;
use Flowrange\Validation\Rule as r;


/**
 * RileRule tests
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class FileRuleTest extends RuleTestCase
{


    public function initDataBuilder()
    {
        $file = array(
            'name' => 'file.txt', 'type' => 'some/type', 'size' => 17,
            'tmp_name' => __DIR__ . '/FileRuleTest/file.txt',
            'error' => UPLOAD_ERR_OK);

        $expected = array(
            'name' => 'file.txt', 'type' => 'text/plain', 'size' => 17,
            'tmp_name' => __DIR__ . '/FileRuleTest/file.txt',
            'error' => UPLOAD_ERR_OK);

        return $this->getDataBuilder(r\file())

            ->areValid()

                ->test('valid file')
                    ->value($file)
                    ->returns($expected)

                ->test('size less than max')
                    ->value($file)
                    ->withRule(r\file()->maxSize(20))
                    ->returns($expected)

                ->test('size equals max')
                    ->value($file)
                    ->withRule(r\file()->maxSize(17))
                    ->returns($expected)

                ->test('mime allowed')
                    ->value($file)
                    ->withRule(r\file()->allowedMimes(['text/plain']))
                    ->returns($expected)

            ->areNotValid()

                ->test('size too big (according to php.ini directives)')
                    ->value(
                        array('name' => 'file.txt', 'type' => '', 'size' => 1000000,
                              'tmp_name' => '',
                              'error' => UPLOAD_ERR_INI_SIZE))
                    ->error(FileRule::ERR_FILE_SIZE)


                ->test('size too big (according to form directives)')
                    ->value(
                        array('name' => 'file.txt', 'type' => '', 'size' => 1000000,
                              'tmp_name' => '',
                              'error' => UPLOAD_ERR_FORM_SIZE))
                    ->error(FileRule::ERR_FILE_SIZE)

                ->test('size too big (according to rule)')
                    ->value($file)
                    ->withRule(r\file()->maxSize(5))
                    ->error(FileRule::ERR_FILE_SIZE)

                ->test('file transfer incomplete (partial)')
                    ->value(
                        array('name' => '', 'type' => '', 'size' => 0,
                              'tmp_name' => '',
                              'error' => UPLOAD_ERR_PARTIAL))
                    ->error(FileRule::ERR_PARTIAL)

                ->test('no file')
                    ->value(
                        array('name' => '', 'type' => '', 'size' => 0,
                              'tmp_name' => '',
                              'error' => UPLOAD_ERR_NO_FILE))
                    ->error(FileRule::ERR_NO_FILE)

                ->test('no tmp dir')
                    ->value(
                        array('name' => '', 'type' => '', 'size' => 0,
                              'tmp_name' => '',
                              'error' => UPLOAD_ERR_NO_TMP_DIR))
                    ->error(FileRule::ERR_NO_FILE)

                ->test('can\'t write file')
                    ->value(
                        array('name' => '', 'type' => '', 'size' => 0,
                              'tmp_name' => '',
                              'error' => UPLOAD_ERR_CANT_WRITE))
                    ->error(FileRule::ERR_CANT_WRITE)

                ->test('mime not allowed')
                    ->value($file)
                    ->withRule(r\file()->allowedMimes(array('application/pdf')))
                    ->error(FileRule::ERR_FILE_TYPE_NOT_ALLOWED)

                ->test('tmp file not found')
                    ->value(
                        array('name' => 'file.txt', 'type' => 'text/plain', 'size' => 1,
                              'tmp_name' => __DIR__ . '/FileRuleTest/not-exists.txt',
                              'error' => UPLOAD_ERR_OK))
                    ->error(FileRule::ERR_FILE_NOT_FOUND);
    }

}

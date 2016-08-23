<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * File upload validation
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class FileRule extends Rule
{


    /**
     * Error : file too large
     * @var string
     */
    const ERR_FILE_SIZE= 'file.file-size';


    /**
     * Error : file not fully uploaded
     * @var string
     */
    const ERR_PARTIAL = 'file.partial';


    /**
     * Error : no file
     * @var string
     */
    const ERR_NO_FILE = 'file.no-file';


    /**
     * Error : can't write uploaded file
     * @var string
     */
    const ERR_CANT_WRITE = 'file.cant-write';


    /**
     * Error : file type not allwoed
     * @var string
     */
    const ERR_FILE_TYPE_NOT_ALLOWED = 'file.file-type-not-allowed';


    /**
     * Error : file not found
     * @var string
     */
    const ERR_FILE_NOT_FOUND = 'file.file-not-found';


    /**
     * File max size, in bytes
     * @var int
     */
    protected $maxSize;


    /**
     * Allowed MIME types
     * @var array
     */
    protected $allowedMimes = array();


    /**
     * Sets the max allowed size
     *
     * @param int $maxSize Max file size, in bytes
     * @return \Flowrange\Validation\Rule\FileRule
     */
    public function maxSize($maxSize)
    {
        $this->maxSize = $maxSize;
        return $this;
    }


    /**
     * Sets the allowed MIME types
     *
     * @param array $allowedMimes
     * @return \Flowrange\Validation\Rule\FileRule
     */
    public function allowedMimes($allowedMimes)
    {
        $this->allowedMimes = $allowedMimes;
        return $this;
    }


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        $uploadErr = isset($value['error']) ? $value['error'] : UPLOAD_ERR_NO_FILE;
        $file      = isset($value['tmp_name']) ? $value['tmp_name'] : null;

        $error = '';

        switch ($uploadErr) {

            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $error = self::ERR_FILE_SIZE;
                break;

            case UPLOAD_ERR_NO_FILE:
            case UPLOAD_ERR_NO_TMP_DIR:
                $error = self::ERR_NO_FILE;
                break;

            case UPLOAD_ERR_PARTIAL:
                $error = self::ERR_PARTIAL;
                break;

            case UPLOAD_ERR_CANT_WRITE:
                $error = self::ERR_CANT_WRITE;
                break;
        }

        if (!$error && ($file == null || !is_file($file))) {
            $error = self::ERR_FILE_NOT_FOUND;
        }

        if (!$error && ($this->maxSize && filesize($file) > $this->maxSize)) {
            $error = self::ERR_FILE_SIZE;
        }

        if ($error) {
            return RuleCheckResult::make(false, $value, $error);
        }

        $mime = $this->getMimeType($file);

        if (   is_array($this->allowedMimes)
            && count($this->allowedMimes) > 0
            && !in_array($mime, $this->allowedMimes)) {

            return RuleCheckResult::make(false, $value, self::ERR_FILE_TYPE_NOT_ALLOWED);
        }

        $value['type'] = $mime;
        return RuleCheckResult::make(true, $value);
    }


    /**
     * Returns a file mime type
     * @param string $file
     * @return string
     */
    public function getMimeType($file)
    {
        $finfo = finfo_open(FILEINFO_MIME);
        $mime  = finfo_file($finfo, $file);

        if(($colonPos = strpos($mime, ';')) !== false) {
            $mime = substr($mime, 0, $colonPos);
        }
        return $mime;
    }
}

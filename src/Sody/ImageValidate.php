<?php

namespace Sody;

trait ImageValidate
{
    /**
     * @var array
     */
    private $allowed = [
        1 => 'IMAGETYPE_GIF',
        2 =>  'IMAGETYPE_JPEG',
        3 =>  'IMAGETYPE_PNG'
    ];

    /**
     * @var array
     */
    private $mimeTypes = [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/gif'
    ];

    /**
     *
     * @param  [type]  $tmpFile [description]
     * @return boolean          [description]
     */
    public function isAllowed($tmpFile)
    {
        return array_key_exists(exif_imagetype($tmpFile), $this->allowed);
    }

    /**
     *
     * @param  [type]  $tmpFile [description]
     * @return boolean          [description]
     */
    public function isRealImage($tmpFile)
    {
        return getimagesize($tmpFile);
    }

    /**
     * Returns true if given file has mimetype
     * defined in mimeTypes
     *
     * @param  object  $file
     * @return boolean
     */
    public function isImage($file)
    {
        return in_array($file, $this->mimeTypes);
    }
}

<?php

namespace Sody;

use Sody\ImageInterface;

class Image implements ImageInterface
{
    use ImageValidate;

    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $tmp_name;
    /**
     * @var string
     */
    private $error;
    /**
     * @var string
     */
    private $size;

    public function __construct($image = [])
    {
        if (false === $image) {
            throw new ImageUploadException("You must provide an image");
        }

        foreach ($image as $key => $value) {
            $this->$key = $value;
        }

        if (false === $this->isRealImage($this->tmp_name)) {
            throw new ImageUploadException("The file must be an image");
        }

        if (false === $this->isAllowed($this->tmp_name)) {
            throw new ImageUploadException("The uploaded file type isn't allowed");
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTmpName()
    {
        return $this->tmp_name;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getExtension()
    {
        switch ($this->type) {
            case 'image/jpeg':
                return '.jpeg';
                break;

            case 'image/jpg':
                return '.jpg';
                break;

            case 'image/png':
                return '.png';
                break;

            default:
                return '.gif';
                break;
        }
    }
}

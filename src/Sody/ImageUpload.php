<?php

namespace Sody;

use Sody\ImageInterface;
use League\Flysystem\FilesystemInterface;
use Sody\ImageUploadException;
use Exception;

class ImageUpload
{
    /**
     * @var Sody\Image
     */
    private $image;
    /**
     * @var League\Flysystem\FilesystemInterface
     */
    private $filesystem;
    private $imageManager;
    /**
     * @var string
     */
    private $path;

    public function __construct(
        ImageInterface $image,
        $imageManager,
        FilesystemInterface $filesystem,
        $path = null
    ) {
        $this->image = $image;
        $this->imageManager = $imageManager;
        $this->filesystem = $filesystem;
        $this->path = $path;
    }

    /**
     * Stores an image to the file system.
     *
     * @param  array  $sizes [description]
     * @return [type]        [description]
     */
    public function store(array $sizes, $text)
    {
        $image = $this->imageManager->make($this->image->getTmpName());

        $uniqueName = $this->getUniqueName();
        $newImageName = $this->getNameWithExtension($uniqueName);

        $image->save($this->getSavePathForMainImage($newImageName));

        try {
            $this->filesystem->createDir($uniqueName);
        } catch (Exception $e) {}

        $image = $this->addText($image, 80, $text);

        $image->save($this->getSavePathForTextBasedImage($uniqueName));

        $fz = ['960' => 40, '480' => 20];

        foreach ($sizes as $width => $height) {
            $image = $this->imageManager->make($this->getSavePathForMainImage($newImageName));

            $image->resize($width, $height);

            $image = $this->addText($image, $fz[$width], $text);

            $image->save($this->getSavePathForTextBasedImage($uniqueName, $width));
        }

        return true;
    }

    private function getSavePathForMainImage($name)
    {
        return $this->path . $name;
    }

    private function getSavePathForTextBasedImage($name, $type = 'text')
    {
        return $this->path . "/{$name}/" . $type . $this->image->getExtension();
    }

    private function getNameWithExtension($name)
    {
        return $name . $this->image->getExtension();
    }

    private function addText($image, $fontSize, $text)
    {
        return $image->text((string) $text, 0, 0, function($font) use ($fontSize) {
            $font->file(ROOT . 'arial.ttf');
            $font->size((int) $fontSize);
            $font->color('#FD0000');
            $font->align('left');
            $font->valign('top');
            $font->angle(0);
        });
    }

    /**
     * Returns an unique name for the image
     *
     * @return integer
     */
    private function getUniqueName()
    {
        return time();
    }
}

<?php

namespace Sody;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;
use Intervention\Image\ImageManager;
use Sody\Directory;
use Sody\ImageUpload;
use Sody\ImageRepository;
use Sody\ImageInterface;

class Factory
{
    /**
     * Returns a fresh ImageRepository object
     *
     * @param  string $path
     * @return Sody\Image
     */
    public function createImageRepository($path = null)
    {
        return new ImageRepository($this->getFilesystem($path));
    }

    /**
     * Returns a fresh ImageUpload object
     *
     * @param  ImageInterface $image
     * @param  string         $path
     * @return Sody\ImageUpload
     */
    public function createImageUpload(ImageInterface $image, $path = null)
    {
        $filesystem = $this->getFilesystem($path);
        $imageManager = new ImageManager(['driver' => 'gd']);

        return new ImageUpload(
            $image,
            $imageManager,
            $filesystem,
            $path
        );
    }

    /**
     * Returns a fresh filesystem object with provided path
     * as root
     *
     * @param  string $path
     * @return League\Flysystem\Filesystem
     */
    private function getFilesystem($path = null)
    {
        $dir = new Directory($path);

        return new Filesystem(
            new Adapter($dir)
        );
    }
}

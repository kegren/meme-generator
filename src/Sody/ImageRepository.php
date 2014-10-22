<?php

namespace Sody;

use League\Flysystem\FilesystemInterface;

class ImageRepository
{
    /**
     * @var array
     */
    private $children = [];

    /**
     * @var League\Flysystem\FilesystemInterface
     */
    private $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Returns all images
     *
     * @return array
     */
    public function getImages()
    {
        $files = $this->filesystem->listWith(['mimetype', 'size'], null, true);

        if ($files) {
            return array_values(array_filter(array_map([$this, 'addToOutput'], $files)));
        }

        return [];
    }

    private function getImageInfoByPath($path)
    {
        list($width, $height) = getimagesize(ROOT . 'uploads/' . $path);

        return $width . 'x' . $height;
    }

    /**
     * Adds an image
     *
     * @param array $image
     */
    private function addToOutput($file)
    {
        if ($file['type'] === 'dir') {
            $this->children[$file['basename']] = [];
        }

        if (isset($file['dirname'])) {
            if (array_key_exists($file['dirname'], $this->children)) {
                $file['res'] = $this->getImageInfoByPath($file['path']);
                $this->children[$file['dirname']][] = $file;
            }
        }

        if (array_key_exists($file['filename'], $this->children)) {
            if ($file['type'] !== 'dir') {
                $file['children'] = $this->children[$file['filename']];

                // var_dump($this->getImageInfoByPath($file['path']));

                $file['res'] = $this->getImageInfoByPath($file['path']);
                return $file;
            }
        }
    }
}

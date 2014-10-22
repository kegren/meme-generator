<?php

namespace Sody;

use Exception;

class Directory
{
    /**
     * @var string
     */
    private $dir;

    public function __construct($dir = null)
    {
        if (null !== $dir) {
            if (false === is_dir($dir)) {
                throw new Exception("Provided directory doesn't exist");
            }
        }

        $this->dir = $dir;
    }

    public function get()
    {
        return $this->dir;
    }

    public function __toString()
    {
        return $this->dir;
    }
}

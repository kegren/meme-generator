<?php

namespace Sody;

/**
 * Image interface
 *
 * @author Kenny Damgren <kennydamgren@gmail.com>
 * @package Sody
 */
interface ImageInterface
{
    public function getType();
    public function getName();
    public function getTmpName();
    public function getError();
    public function getSize();
}

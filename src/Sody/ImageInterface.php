<?php

namespace Sody;

interface ImageInterface
{
    public function getType();
    public function getName();
    public function getTmpName();
    public function getError();
    public function getSize();
}

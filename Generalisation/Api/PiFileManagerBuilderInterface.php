<?php

namespace Sfynx\DddGeneratorBundle\Generalisation\Api;

interface PiFileManagerBuilderInterface
{
    public static function getFileDirname($filename, $separator = "/");
}

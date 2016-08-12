<?php

namespace Sfynx\DddGeneratorBundle\Util;

use Sfynx\DddGeneratorBundle\Generalisation\Api\PiFileManagerBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;


class PiFileManager implements PiFileManagerBuilderInterface
{
    /**
     * Retrieves the dirname of a file.
     *
     * @param string $filename Nom du fichier
     *
     * @return string
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public static function getFileDirname($filename, $separator = "/")
    {
        if (file_exists($filename)) {
            return dirname($filename);
        }
        $filename = explode($separator, $filename);
        array_pop($filename);

        return implode($separator, $filename);
    }

}

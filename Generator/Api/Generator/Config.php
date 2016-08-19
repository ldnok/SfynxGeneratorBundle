<?php
/**
 * Created by PhpStorm.
 * User: ldn
 * Date: 19/08/16
 * Time: 16:47
 */

namespace Sfynx\DddGeneratorBundle\Generator\Api\Generator;


class Config
{
    protected $generator;
    protected $destinationPath;
    protected $output;

    public function __construct($generator, $destinationPath, $output)
    {
        $this->generator = $generator;
        $this->destinationPath = $destinationPath;
        $this->output = $output;
    }
}
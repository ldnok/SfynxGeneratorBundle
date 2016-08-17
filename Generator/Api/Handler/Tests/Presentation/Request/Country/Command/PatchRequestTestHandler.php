<?php

namespace Tests\Presentation\Request\Country\Command;

require_once __DIR__.'/../../../../TraitVerifyResolver.php';

use Sfynx\DddBundle\Layer\Presentation\Request\Generalisation\CommandRequestInterface;
use Sfynx\DddBundle\Layer\Presentation\Request\Generalisation\Request\RequestInterface;
use Sfynx\DddBundle\Layer\Presentation\Request\Generalisation\Request\SymfonyStrategy;
use Sfynx\DddBundle\Layer\Presentation\Request\Generalisation\Resolver\ResolverInterface;
use DemoCountry\Presentation\Request\Country\Command\PatchRequest;
use \Phake;
use Sfynx\DddBundle\Layer\Presentation\Request\Generalisation\Resolver\ResolverStrategy;

class PatchRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var RequestInterface
     */
    protected $requestStrategy;
    /**
     * @var ResolverInterface
     */
    protected $resolverStrategy;
    /**
     * @var NewRequest
     */
    protected $request;

    public function setUp()
    {
        $this->SymfonyStrategy = Phake::mock(SymfonyStrategy::class);
        $this->resolver = Phake::mock(ResolverStrategy::class);
        $this->request = new PatchRequest($this->SymfonyStrategy,$this->resolver);

    }

    public function testGetRequestInterface()
    {
        $this->assertInstanceof(CommandRequestInterface::class,$this->request);
    }
}
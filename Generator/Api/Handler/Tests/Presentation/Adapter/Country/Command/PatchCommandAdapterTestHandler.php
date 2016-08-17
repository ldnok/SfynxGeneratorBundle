<?php

namespace Tests\Presentation\Adapter\Country\Command;

use DemoCountry\Presentation\Adapter\Country\Command\PatchCommandAdapter;
use DemoCountry\Presentation\Request\Country\Command\PatchRequest;
use Sfynx\DddBundle\Layer\Application\Generalisation\Interfaces\CommandInterface;
use Sfynx\DddBundle\Layer\Presentation\Adapter\Generalisation\CommandAdapterInterface;
use Sfynx\DddBundle\Layer\Presentation\Request\Generalisation\CommandRequestInterface;
use \Phake;

class PatchCommandAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UpdateCommandAdapter
     */
    protected $adapter;

    /**
     * @var CommandRequestInterface
     */
    protected $request;

    public function setUp()
    {
        $this->adapter = new PatchCommandAdapter();
        $this->request  = Phake::mock(PatchRequest::class);
    }

    public function testInterfaces()
    {
        $this->assertInstanceOf(CommandAdapterInterface::class,$this->adapter);
    }

    public function testCreateCommandFromRequest()
    {
        $this->adapter->createCommandFromRequest($this->request);
        Phake::verify($this->request,Phake::times(1))->getRequestParameters();

    }
}

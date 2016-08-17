<?php

namespace Tests\Presentation\Adapter\Country\Command;

use DemoCountry\Presentation\Adapter\Country\Command\NewCommandAdapter;
use DemoCountry\Presentation\Request\Country\Command\NewRequest;
use Sfynx\DddBundle\Layer\Application\Generalisation\Interfaces\CommandInterface;
use Sfynx\DddBundle\Layer\Presentation\Adapter\Generalisation\CommandAdapterInterface;
use Sfynx\DddBundle\Layer\Presentation\Request\Generalisation\CommandRequestInterface;
use \Phake;

class NewCommandAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NewCommandAdapter
     */
    protected $adapter;

    /**
     * @var CommandRequestInterface
     */
    protected $request;

    public function setUp()
    {
        $this->adapter = new NewCommandAdapter();
        $this->request  = Phake::mock(NewRequest::class);
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

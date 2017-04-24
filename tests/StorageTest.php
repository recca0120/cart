<?php

namespace Recca0120\Cart\Tests;

use Mockery as m;
use Recca0120\Cart\Storage;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;

class StorageTest extends TestCase
{
    protected function setUp()
    {
        $this->name = 'default';
        $this->hash = hash('sha256', 'Recca0120\Cart'.$this->name);
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testStore()
    {
        $storage = new Storage(
            $this->name = 'default',
            $session = m::mock('Illuminate\Contracts\Session\Session')
        );
        $session->shouldReceive('put')->once()->with($this->hash, $data = ['foo' => 'bar']);
        $storage->store($data);
    }

    public function testRestore()
    {
        $storage = new Storage(
            $this->name = 'default',
            $session = m::mock('Illuminate\Contracts\Session\Session')
        );
        $session->shouldReceive('get')->once()->with($this->hash, m::type('Illuminate\Support\Collection'))->andReturn(
            new Collection()
        );
        $this->assertInstanceOf('Illuminate\Support\Collection', $storage->restore());
    }
}

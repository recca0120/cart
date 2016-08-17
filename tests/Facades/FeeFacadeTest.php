<?php

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Mockery as m;
use Recca0120\Cart\Contracts\Fee as FeeContract;
use Recca0120\Cart\Facades\Fee;

class FeeFacadeTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function test_facade()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */

        $app = m::mock(ApplicationContract::class.','.ArrayAccess::class);
        Fee::setFacadeApplication($app);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */

        $app->shouldReceive('offsetGet')->with(FeeContract::class)->once();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */

        Fee::getFacadeRoot();
    }
}

<?php

namespace Symfony\Cmf\Bundle\RoutingAutoRouteBundle\Tests\Functional\AutoRoute;
use Symfony\Cmf\Bundle\RoutingAutoRouteBundle\Tests\Functional\BaseTestCase;

/**
 * Description here
 *
 * @author Daniel Leech <daniel@dantleech.com>
 * @date 13/03/08
 */
class AutoRouteManagerTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->arm = $this->getContainer()->get(
            'symfony_cmf_routing_auto_route.auto_route_manager'
        );
    }

    public function testContainer()
    {
        $this->markTestSkipped('Todo');
    }
}

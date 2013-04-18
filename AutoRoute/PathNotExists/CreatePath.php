<?php

namespace Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\PathNotExists;

use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\PathActionInterface;
use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\RouteStack;
use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\RouteMakerInterface;

/**
 * @author Daniel Leech <daniel@dantleech.com>
 */
class CreatePath implements PathActionInterface
{
    protected $routeMaker;

    public function __construct(RouteMakerInterface $routeMaker)
    {
        $this->routeMaker = $routeMaker;
    }

    public function init(array $options)
    {
    }

    public function execute(RouteStack $routeStack)
    {
        $this->routeMaker->make($routeStack);
    }
}

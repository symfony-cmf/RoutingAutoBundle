<?php

namespace Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\PathProvider;

use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\PathProviderInterface;
use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\Exception\BadProviderPositionException;
use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\RouteStack;

/**
 * Provides the routing extra bundles route_basepath.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class RouteBasePathObjectProvider implements PathProviderInterface
{
    protected $routingBasePath;

    public function __construct($routeBasePath)
    {
        $this->routeBasePath = $routeBasePath;
    }

    public function init(array $options)
    {
    }

    public function providePath(RouteStack $routeStack)
    {
        $context = $routeStack->getContext();

        if (count($context->getRouteStacks()) > 0) {
            throw new BadProviderPositionException(
                'RouteBasePathProvider must belong to the first builder unit - adding '.
                'the full routing basepath at an intermediate point would be senseless.'
            );
        }

        $id = $this->routeBasePath;
        $id = substr($id, 1);
        $pathElements = explode('/', $id);
        $routeStack->addPathElements($pathElements);
    }
}


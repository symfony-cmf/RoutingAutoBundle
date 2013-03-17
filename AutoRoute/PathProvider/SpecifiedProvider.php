<?php

namespace Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\PathProvider;

use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\PathProviderInterface;
use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\Exception\MissingOptionException;
use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\BuilderContext;
use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\RouteStack;

/**
 * @author Daniel Leech <daniel@dantleech.com>
 */
class SpecifiedProvider implements PathProviderInterface
{
    protected $path;

    public function init(array $options)
    {
        if (!isset($options['path'])) {
            throw new MissingOptionException(__CLASS__, 'path');
        }

        $this->path = $options['path'];
    }

    public function providePath(RouteStack $routeStack)
    {
        if (substr($this->path, 0, 1) == '/') {
            $this->path = substr($this->path, 1);
        }

        $routeStack->addPathElements(explode('/', $this->path));
    }
}

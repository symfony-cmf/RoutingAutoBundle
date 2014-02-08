<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2013 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\RoutingAutoBundle\Tests\AutoRoute\PathProvider;

use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\PathExists\PathProvider;
use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\PathProvider\ContentMethodProvider;

class ContentMethodProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->builderContext = $this->getMock(
            'Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\BuilderContext'
        );
        $this->routeStack = $this->getMockBuilder(
            'Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\RouteStack'
        )->disableOriginalConstructor()->getMock();
        $this->slugifier = $this->getMock(
            'Symfony\Cmf\Bundle\CoreBundle\Slugifier\SlugifierInterface'
        );

        $this->provider = new ContentMethodProvider($this->slugifier);
        $this->object = new ContentMethodTestClass();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testProvideMethod_invalidMethod()
    {
        $this->routeStack->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($this->builderContext));
        $this->builderContext->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($this->object));

        $this->provider->providePath($this->routeStack, array(
            'method' => 'invalidMethod',
            'slugify' => true,
        ));
    }

    public function setupTest($slugify = true)
    {
        $this->routeStack->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($this->builderContext));
        $this->builderContext->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($this->object));

        if ($slugify) {
            $this->slugifier->expects($this->any())
                ->method('slugify')
                ->will($this->returnCallback(function ($el) { return $el; }));
        }
    }

    public function testProvideMethod()
    {
        $this->setupTest();
        $this->routeStack->expects($this->once())
            ->method('addPathElements')
            ->with(array('this', 'is', 'path'));

        $this->provider->providePath($this->routeStack, array(
            'method' => 'getSlug',
            'slugify' => true,
        ));
    }

    public function testProvideMethodNoSlugify()
    {
        $this->setupTest(false);
        $this->routeStack->expects($this->once())
            ->method('addPathElements')
            ->with(array('this', 'is', 'path'));

        $this->provider->providePath($this->routeStack, array('method' => 'getSlug', 'slugify' => false));
    }

    public function testProvideMethodWithString()
    {
        $this->setupTest();
        $this->routeStack->expects($this->once())
            ->method('addPathElements')
            ->with(array('this/is/a/path'));

        $this->provider->providePath($this->routeStack, array(
            'method' => 'getStringSlug',
            'slugify' => true,
        ));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testProvideMethodWithAbsolute()
    {
        $this->setupTest();

        $this->provider->providePath($this->routeStack, array(
            'method' => 'getAbsoluteSlug',
            'slugify' => true,
        ));
    }

    public function testProvideMethodObjectToString()
    {
        $this->setupTest();

        $this->provider->init(array('method' => 'getStringObjectSlug'));
        $this->provider->providePath($this->routeStack);
    }

    /**
     * @expectedException \RunTimeException
     */
    public function testProvideMethodWrongType()
    {
        $this->setupTest();

        $this->provider->init(array('method' => 'getWrongTypeSlug'));
        $this->provider->providePath($this->routeStack);
    }
}

class ContentMethodTestClass
{
    public function getSlug()
    {
        return array('this', 'is', 'path');
    }

    public function getStringSlug()
    {
        return 'this/is/a/path';
    }

    public function getAbsoluteSlug()
    {
        return '/this/is/absolute';
    }

    public function getStringObjectSlug()
    {
        return new StringObject();
    }

    public function getWrongTypeSlug()
    {
        return new \StdClass();
    }

}

class StringObject
{
    public function __toString()
    {
        return 'this/is/from/an/object';
    }
}

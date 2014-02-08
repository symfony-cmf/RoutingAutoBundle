<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2013 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\RoutingAutoBundle\Tests\Unit\AutoRoute\PathProvider;

use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\PathExists\PathProvider;
use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\PathProvider\SpecifiedProvider;

class SpecifiedProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->provider = new SpecifiedProvider;
        $this->routeStack = $this->getMockBuilder(
            'Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\RouteStack'
        )->disableOriginalConstructor()->getMock();
    }

    public function providePath()
    {
        return array(
            array('foo/bar'),
            array('/foo/bar'),
        );
    }

    /**
     * @dataProvider providePath
     */
    public function testProvidePath($path)
    {
        $this->routeStack->expects($this->once())
            ->method('addPathElements')
            ->with(array('foo', 'bar'));

        $this->provider->configureOptions($this->provider->getOptionsResolver());
        $this->provider->providePath($this->routeStack, $this->provider->getOptionsResolver()->resolve(array('path' => $path)));
    }
}

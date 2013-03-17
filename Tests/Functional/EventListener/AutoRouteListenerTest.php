<?php

namespace Symfony\Cmf\Bundle\RoutingAutoBundle\Tests\Functional\Subscriber;

use Symfony\Cmf\Bundle\RoutingAutoBundle\Tests\Functional\app\Document\Blog;
use Symfony\Cmf\Bundle\RoutingAutoBundle\Tests\Functional\BaseTestCase;

class AutoRouteListenerTest extends BaseTestCase
{
    protected function createBlog()
    {
        $post = new Blog;
        $post->path = '/test/test-blog';
        $post->title = 'Unit testing blog';

        $this->getDm()->persist($post);
        $this->getDm()->flush();
        $this->getDm()->clear();
    }

    public function testPersistBlog()
    {
        $this->createBlog();

        $route = $this->getDm()->find(null, '/test/auto-route/blog/unit-testing-blog');

        $this->assertNotNull($route);

        // make sure auto-route has been persisted
        $blog = $this->getDm()->find(null, '/test/test-blog');
        $routes = $this->getDm()->getReferrers($blog);

        $this->assertCount(1, $routes);
        $this->assertInstanceOf('Symfony\Cmf\Bundle\RoutingAutoBundle\Document\AutoRoute', $routes[0]);
        $this->assertEquals('unit-testing-blog', $routes[0]->getName());
    }

    public function testUpdateBlog()
    {
        $this->createBlog();

        $blog = $this->getDm()->find(null, '/test/test-blog');
        // test update
        $blog->title = 'Foobar';
        $this->getDm()->persist($blog);
        $this->getDm()->flush();

        // make sure auto-route has been persisted
        $blog = $this->getDm()->find(null, '/test/test-blog');
        $routes = $blog->routes;

        $this->assertCount(1, $routes);
        $this->assertInstanceOf('Symfony\Cmf\Bundle\RoutingAutoBundle\Document\AutoRoute', $routes[0]);

        $this->getDm()->refresh($routes[0]);

        $this->assertEquals('foobar', $routes[0]->getName());
        $this->assertEquals('/test/auto-route/blog/foobar', $routes[0]->getId());
    }

    public function testRemoveBlog()
    {
        $this->createBlog();
        $blog = $this->getDm()->find(null, '/test/test-blog');

        // test removing
        $this->getDm()->remove($blog);

        $this->getDm()->flush();

        $baseRoute = $this->getDm()->find(null, '/test/auto-route/blog');
        $routes = $this->getDm()->getChildren($baseRoute);
        $this->assertCount(0, $routes);
    }
}


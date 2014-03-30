<?php

namespace Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute;

use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Bundle\RoutingAutoBundle\Model\AutoRouteInterface;

class UrlContextStack
{
    protected $subjectObject;
    protected $urlContexts = array();

    /**
     * @param mixed $subjectObject Subject for URL generation
     */
    public function __construct($subjectObject)
    {
        $this->subjectObject = $subjectObject;
    }

    /**
     * Return the "subject" of this URL context, i.e. the object
     * for which an auto route is required.
     *
     * @return object
     */
    public function getSubjectObject() 
    {
        return $this->subjectObject;
    }

    /**
     * Create and a URL context
     *
     * @param string $url    URL
     * @param string $locale Locale for given URL
     *
     * @return UrlContext
     */
    public function createUrlContext($locale)
    {
        $urlContext = new UrlContext(
            $this->getSubjectObject(),
            $locale
        );

        return $urlContext;
    }

    /**
     * Push a URL context onto the stack
     *
     * @param UrlContext $urlContext
     */
    public function pushUrlContext(UrlContext $urlContext)
    {
        $this->urlContexts[] = $urlContext;
    }

    public function getUrlContexts()
    {
        return $this->urlContexts;
    }

    /**
     * Return true if any one of the UrlContexts in the stacj
     * contain the given auto route
     *
     * @param AutoRouteInterface $autoRoute
     */
    public function containsRoute(AutoRouteInterface $autoRoute)
    {
        foreach ($this->urlContexts as $urlContext) {
            if ($autoRoute === $urlContext->getRoute()) {
                return true;
            }
        }

        return false;
    }

    public function getRouteByTag($tag)
    {
        foreach ($this->urlContexts as $urlContext) {
            $autoRoute = $urlContext->getRoute();
            if ($tag === $autoRoute->getAutoRouteTag()) {
                return $autoRoute;
            }
        }

        return null;
    }
}
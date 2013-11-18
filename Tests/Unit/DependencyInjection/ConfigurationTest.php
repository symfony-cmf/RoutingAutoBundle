<?php

namespace Symfony\Cmf\Bundle\RoutingAutoBundle\Tests\Unit\DependencyInjection;

use Symfony\Cmf\Bundle\RoutingAutoBundle\DependencyInjection\Configuration;
use Symfony\Cmf\Bundle\RoutingAutoBundle\DependencyInjection\CmfRoutingAutoExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;

class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    protected function getContainerExtension()
    {
        return new CmfRoutingAutoExtension();
    }

    protected function getConfiguration()
    {
        return new Configuration();
    }

    public function testSupportsAllConfigFormats()
    {
        $expectedConfiguration = array(
            'auto_route_mapping' => array(
                'Acme\BasisCmsBundle\Document\Page' => array(
                    'content_path' => array(
                        'pages' => array(
                            'provider' => array(
                                'name' => 'specified',
                                'options' => array(
                                    'path' => '/cms/routes/page',
                                ),
                            ),
                            'exists_action' => array(
                                'strategy' => 'use',
                                'options' => array(),
                            ),
                            'not_exists_action' => array(
                                'strategy' => 'create',
                                'options' => array(),
                            ),
                        ),
                    ),
                    'content_name' => array(
                        'provider' => array(
                            'name' => 'content_method',
                            'options' => array(
                                'method' => 'getTitle',
                            ),
                        ),
                        'exists_action' => array(
                            'strategy' => 'auto_increment',
                            'options' => array(
                                'pattern' => '-%d',
                            ),
                        ),
                        'not_exists_action' => array(
                            'strategy' => 'create',
                            'options' => array(),
                        ),
                    ),
                ),
            ),
        );

        $sources = array_map(function ($path) {
            return __DIR__.'/../../Resources/Fixtures/'.$path;
        }, array(
            'config/config.yml',
        ));

        $this->assertProcessedConfigurationEquals($expectedConfiguration, $sources);
    }
}

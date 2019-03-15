<?php

namespace Jplarar\PayPalBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class JplararPayPalExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $parameters = [
            'paypal_client_id',
            'paypal_client_secret',
            'paypal_redirect_success',
            'paypal_redirect_error',
            'paypal_env'
        ];

        foreach ($parameters as $parameter) {
            if (!isset($config[$parameter])) {
                throw new \InvalidArgumentException('The option "jplarar_paypal.'.$parameter.'" must be set.');
            }
            $container->setParameter('jplarar_paypal.'.$parameter, $config[$parameter]);
        }
    }

    /**
     * {@inheritdoc}
     * @version 0.0.1
     * @since 0.0.1
     */
    public function getAlias()
    {
        return 'jplarar_paypal';
    }
}

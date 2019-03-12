<?php

namespace Jplarar\PayPalBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jplarar_paypal');

        $rootNode
            ->children()
            ->scalarNode('paypal_client_id')->defaultValue(null)->end()
            ->scalarNode('paypal_client_secret')->defaultValue(null)->end()
            ->scalarNode('paypal_redirect_success')->defaultValue(null)->end()
            ->scalarNode('paypal_redirect_error')->defaultValue(null)->end()
            ->end();

        return $treeBuilder;
    }
}

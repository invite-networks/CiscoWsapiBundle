<?php
/*
 * This file is part of the Invite Wsapi Bundle
 *
 * The bundle provides a mapping to Cisco's IOS UC Gateway Api.
 *
 * (c) Invite Networks Inc. <info@invitenetworks.com>
 *
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace Invite\Bundle\Cisco\WsapiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Configuration.
 *
 * @author Josh Whiting <josh@invitenetworks.com>
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cisco_wsapi');

        /* $rootNode->addDefaultsIfNotSet()
          ->children()
          ->booleanNode('xcc_enabled')
          ->defaultFalse()
          ->info('Enable/Disable Xcc api service')
          ->end()
          ->booleanNode('xsvc_enabled')
          ->defaultFalse()
          ->info('Enable/Disable Xsvc api service')
          ->end()
          ->booleanNode('xcdr_enabled')
          ->defaultFalse()
          ->info('Enable/Disable Xcdr api service')
          ->end()
          ->end()
          ; */

        /*  $rootNode
          ->children()
          ->arrayNode('ios_hosts')
          ->isRequired()
          ->cannotBeEmpty()
          ->beforeNormalization()
          ->ifTrue(function($v) {
          return !is_array($v) && !is_null($v);
          }) */
        //   ->then(function($v) {
        //             return is_bool($v) ? array() : preg_split('/\s*,\s*/', $v);
        /*       })
          ->end()
          ->prototype('scalar')
          ->validate()
          ->ifTrue(function($v) {
          return !empty($v) && !filter_var($v, FILTER_VALIDATE_IP);
          })
          ->thenInvalid('Invalid CUBE IP "%s"')
          ->end()
          ->end()
          ->end()
          ->end(); */

        $this->addAppSection($rootNode);
        $this->addSoapSection($rootNode);
        $this->addProviderSection($rootNode);

        return $treeBuilder;
    }

    private function addAppSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
                ->arrayNode('app')
                ->addDefaultsIfNotSet()
                ->info('INVITE WebService REST API for Cisco UC Gateway API')
                ->children()
                ->scalarNode('xcc_name')
                ->defaultValue('invite_xcc')
                ->info('INVITE XCC Application name')
                ->end()
                ->scalarNode('xsvc_name')
                ->defaultValue('invite_xsvc')
                ->info('INVITE XSVC Application name')
                ->end()
                ->scalarNode('xcdr_name')
                ->defaultValue('invite_xcdr')
                ->info('INVITE XCDR Application name')
                ->end()
                ->scalarNode('host')
                ->isRequired()
                ->cannotBeEmpty()
                ->info('INVITE Wsapi Application Host')
                ->end()
                ->scalarNode('protocol')
                ->defaultValue('http')
                ->info('INVITE Wsapi Application Protocol')
                ->end()
                ->end()
                ->end()
                ->end()
        ;
    }

    private function addSoapSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
                ->arrayNode('soap')
                ->addDefaultsIfNotSet()
                ->info('Cisco IOS Gateway SOAP API Configuration Parameters')
                ->children()
                ->booleanNode('trace')
                ->defaultFalse()
                ->info('Enable/Disable SoapClient Exception Faults')
                ->end()
                ->booleanNode('exception')
                ->defaultFalse()
                ->info('Enable/Disable SoapClient Exception Faults')
                ->end()
                ->scalarNode('connection_timeout')
                ->defaultValue(5)
                ->info('Set the Soap Client Connection Timeout, for services with slow response. See default_socket_timeout')
                ->example('time in seconds')
                ->end()
                ->scalarNode('socket_timeout')
                ->defaultValue(5)
                ->info('Set the Soap Client Default Socket Timeout, this is a global php setting')
                ->example('time in seconds')
                ->end()
                ->end()
                ->end()
                ->end()
        ;
    }

    private function addProviderSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
                ->arrayNode('providers')
                ->addDefaultsIfNotSet()
                ->info('Cisco IOS Gateway SOAP API Configuration Parameters')
                ->children()
                ->scalarNode('xcc_protocol')
                ->defaultValue('http://')
                ->info('Use http or https for Cisco UC Gateway API')
                ->end()
                ->scalarNode('xsvc_protocol')
                ->defaultValue('http://')
                ->info('Use http or https for Cisco UC Gateway API')
                ->end()
                ->scalarNode('xcdr_protocol')
                ->defaultValue('http://')
                ->info('Use http or https for Cisco UC Gateway API')
                ->end()
                ->scalarNode('xcdr_cdr_format')
                ->defaultValue('compact')
                ->info('Xcdr cdr record format, compact or detailed')
                ->end()
                ->end()
                ->end()
                ->end()
        ;
    }

}

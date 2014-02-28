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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

/**
 * CiscoWsapiExtension.
 *
 * @author Josh Whiting <josh@invitenetworks.com>
 */
class CiscoWsapiExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        /**
         * Set Cisco IOS Gateway SOAP API Url from IP/Hostname supplied in config params
         */
        $container->setParameter('cisco_wsapi.app_host', $config['app']['host']);
        $container->setParameter('cisco_wsapi.app_protocol', $config['app']['protocol']);
        $container->setParameter('cisco_wsapi.xcc.app_name', $config['app']['xcc_name']);
        $container->setParameter('cisco_wsapi.xsvc.app_name', $config['app']['xsvc_name']);
        $container->setParameter('cisco_wsapi.xcdr.app_name', $config['app']['xcdr_name']);
        $container->setParameter('cisco_wsapi.xcc.protocol', $config['providers']['xcc_protocol']);
        $container->setParameter('cisco_wsapi.xsvc.protocol', $config['providers']['xsvc_protocol']);
        $container->setParameter('cisco_wsapi.xcdr.protocol', $config['providers']['xcdr_protocol']);
        $container->setParameter('cisco_wsapi.xcdr.cdr_format', $config['providers']['xcdr_cdr_format']);
        $container->setParameter('cisco_wsapi.soap.exception', $config['soap']['exception']);
        $container->setParameter('cisco_wsapi.soap.trace', $config['soap']['trace']);
        $container->setParameter('cisco_wsapi.soap.connection_timeout', $config['soap']['connection_timeout']);
        $container->setParameter('cisco_wsapi.soap.socket_timeout', $config['soap']['socket_timeout']);
    }

}

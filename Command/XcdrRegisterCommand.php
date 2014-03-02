<?php
/*
 * This file is part of the Invite Wsapi Bundle
 *
 * (c) Invite Networks Inc. <info@invitenetworks.com>
 *
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace Invite\Bundle\Cisco\WsapiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * XcdrRegisterCommand.
 *
 * @author Josh Whiting <josh@invitenetworks.com>
 * 
 * @CronJob("PT5M")
 */
class XcdrRegisterCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('invite:wsapi:xcdr:register')
                ->setDescription('Register with Cisco UC Gateway XCDR IOS API')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $router = $container->get('router');
        $context = $router->getContext();
        $appHost = $container->getParameter('cisco_wsapi.app_host');
        $appPort = $container->getParameter('cisco_wsapi.app_port');
        $appScheme = $container->getParameter('cisco_wsapi.app_scheme');
        $context->setHost($appHost);
        $context->setScheme($appScheme);

        if ($appScheme === 'https') {
            $context->setHttpsPort($appPort);
        } else {
            $context->setHttpPort($appPort);
        }

        $route = $router->generate('cisco_wsapi.xcdr_app', array(), true);
        $xcdrClient = $container->get('cisco_wsapi.xcdr_client');
        $xcdrHosts = $container->getParameter('xcdr_hosts');
        foreach ($xcdrHosts as $customer => $hosts) {
            foreach ($hosts as $host) {
                if ($this->checkCache($host, $container)) {
                    $msg = $host . ' already registered with XCDR Provider';
                    $output->writeln('<info>' . $msg . '</info>');
                    return;
                }
                $result = $xcdrClient->requestXcdrRegister($host, $route);
                $this->setCache($host, $route, $result, $container, $customer);
                $msg = $host . ' was registered with a status of ' . $result['providerStatus'];
                $output->writeln('<info>' . $msg . '</info>');
            }
        }
    }

    protected function checkCache($host, $container)
    {
        if ($container->getParameter('cisco_wsapi.redis_enabled')) {
            $redisService = $container->getParameter('cisco_wsapi.redis_service');
            $redis = $container->get($redisService);

            if ($redis->exists('xcdr:' . $host)) {
                if ($redis->hexists('xcdr:' . $host, 'reg.id')) {
                    if ($redis->get('xcdr:' . $host, 'status' === 'IN_SERVICE')) {
                        return true;
                    }
                }
            }
        }
        if ($container->getParameter('cisco_wsapi.memcache_enabled')) {
            // TODO finish memcache logic
        }

        // TODO figure out if should check disk as last resort
        return false;
    }

    protected function setCache($host, $route, $result, $container, $customer)
    {
        if ($container->getParameter('cisco_wsapi.redis_enabled')) {
            $redisService = $container->getParameter('cisco_wsapi.redis_service');
            $redis = $container->get($redisService);

            $redis->hmset('xcdr:' . $host, array(
                'customer' => $customer,
                'app.route' => $route,
                'reg.id' => $result['registrationID'],
                'status' => $result['providerStatus']
            ));
            // Initial reg expires in 1 hour.
            $redis->expire('xcdr:' . $host, 3600);
        }
        if ($container->getParameter('cisco_wsapi.memcache_enabled')) {
            // TODO finish memcache logic
        }

        // TODO figure out if should write to disk as last resort
        return;
    }

}

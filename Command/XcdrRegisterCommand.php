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
        $host = $container->getParameter('cisco_wsapi.app_host');
        $port = $container->getParameter('cisco_wsapi.app_port');
        $scheme = $container->getParameter('cisco_wsapi.app_scheme');
        $context->setHost($host);
        $context->setScheme($scheme);

        if ($scheme === 'https') {
            $context->setHttpsPort($port);
        } else {
            $context->setHttpPort($port);
        }

        $route = $router->generate('cisco_wsapi.xcdr_app', array(), true);
        $xcdrApi = $container->get('cisco_wsapi.xcdr_client');
        $xcdrHosts = $container->getParameter('xcdr_hosts');
        foreach ($xcdrHosts as $customer => $hosts) {
            foreach ($hosts as $host) {
                $result = $xcdrApi->requestXcdrRegister($host, $route);
                $output->writeln('<info>' . $result . '</info>');
            }
        }
    }

}

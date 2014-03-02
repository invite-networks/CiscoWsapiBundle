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
        $client = $container->get('cisco_wsapi.xcdr_client');
        $hosts = $container->getParameter('cisco_wsapi.xcdr_hosts');
        foreach ($hosts as $host) {
            $extras = array('customer' => 'stevens-henegar-college');
            $result = $client->register($host, $extras);
            $output->writeln('<comment>' . strtoupper($result['status']) . '</comment> : <info>' . $result['message'] . '</info>');
        }
    }

}

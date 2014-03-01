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
use Invite\Component\Ssh\Client\Cisco\IosSshClient;

/**
 * XcdrRegisterCommand.
 *
 * @author Josh Whiting <josh@invitenetworks.com>
 */
class XcdrCheckCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('invite:wsapi:xcdr:check')
                ->setDescription('Check if there is an active registration with UC Gateway')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new IosSshClient('209.180.87.74', 'invite', '!NV!TE|Customer');

        $client->connect();
        $data = $client->exec('show wsapi registration all');
        $client->close();

        $output->writeln('<info>' . $data . '</info>');
    }

}

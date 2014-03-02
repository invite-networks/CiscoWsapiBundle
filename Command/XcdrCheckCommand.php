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
use Symfony\Component\Console\Input\InputArgument;
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
        $this
                ->setName('invite:wsapi:xcdr:check')
                ->setDescription('Check if there is an active registration with UC Gateway')
                ->addArgument(
                        'host', InputArgument::REQUIRED, 'Hostname or IP address of device'
                )
                ->addArgument(
                        'username', InputArgument::REQUIRED, 'Username to login to the device'
                )
                ->addArgument(
                        'password', InputArgument::REQUIRED, 'Password to login to the device'
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getArgument('host');
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $client = new IosSshClient($host, $username, $password);

        $client->connect();
        $data = $client->wsapiXcdrReg();
        $client->close();

        $output->writeln('<info></info>');
        $output->writeln('<info>' . $host . ' XCDR Registration Status</info>');
        $output->writeln('<info>======================================</info>');
        foreach ($data as $k => $v) {
            $output->writeln(' <info>' . $k . ' </info>:<comment> ' . $v . '</comment>');
        }
        $output->writeln('<info></info>');
    }

}

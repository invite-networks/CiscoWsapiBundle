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
namespace Invite\Bundle\Cisco\WsapiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Invite\Bundle\Cisco\WsapiBundle\DependencyInjection\Compiler\CacheCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * CiscoWsapiBundle.
 *
 * @author Josh Whiting <josh@invitenetworks.com>
 */
class CiscoWsapiBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CacheCompilerPass());
    }

}

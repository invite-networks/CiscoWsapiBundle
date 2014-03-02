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
namespace Invite\Bundle\Cisco\WsapiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CacheCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('cisco_wsapi.redis_enabled') === true) {
            if ($container->getParameter('cisco_wsapi.redis_service') !== null) {
                $redis = $container->getParameter('cisco_wsapi.redis_service');
                $redisService = $container->getDefinition($redis);
                if ($redisService) {
                    $container->setDefinition('cisco_wsapi.redis', $redisService);
                }
            }
        }

        if ($container->getParameter('cisco_wsapi.memcache_enabled') === true) {
            if ($container->getParameter('cisco_wsapi.memcache_service') !== null) {
                $memcache = $container->getParameter('cisco_wsapi.memcache_service');
                $memcacheService = $container->getDefinition($memcache);
                if ($memcacheService) {
                    $container->setDefinition('cisco_wsapi.memcache', $memcacheService);
                }
            }
        }
    }

}
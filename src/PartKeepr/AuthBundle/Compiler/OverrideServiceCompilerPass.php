<?php

namespace PartKeepr\AuthBundle\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition('security.authentication.manager');
        $definition->setClass(\PartKeepr\AuthBundle\Security\Authentication\AuthenticationProviderManager::class);
    }
}
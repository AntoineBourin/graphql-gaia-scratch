<?php

namespace App\DependencyInjection\Compiler;

use App\Services\Type\TypesChain;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class GraphTypesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(TypesChain::class)) {
            return;
        }

        $definition = $container->findDefinition(TypesChain::class);

        $taggedServices = $container->findTaggedServiceIds('app.types');

        foreach ($taggedServices as $id => $tags) {
            // add the transport service to the TransportChain service
            $definition->addMethodCall('addType', [new Reference($id)]);
        }
    }
}

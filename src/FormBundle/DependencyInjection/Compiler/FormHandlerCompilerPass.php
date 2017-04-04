<?php

namespace FormHandler\FormBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FormHandlerCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('form.handler');

        if (!$container->hasDefinition('form.factory') && 0 < count($taggedServices)) {
            throw new \LogicException('Form factory expected for "form.handler" tagged services.');
        }

        $formFactory = $container->getDefinition('form.factory');
        foreach ($taggedServices as $id => $tags) {
            $container->getDefinition($id)->addMethodCall('setFormFactory', [$formFactory]);
        }
    }
}

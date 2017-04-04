<?php

namespace FormHandler\FormBundle;

use FormHandler\FormBundle\DependencyInjection\Compiler\FormHandlerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class FormHandlerBundle
 * @package FormHandler\FormBundle
 */
class FormHandlerBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FormHandlerCompilerPass());
    }
}

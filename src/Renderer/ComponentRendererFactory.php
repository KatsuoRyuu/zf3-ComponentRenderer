<?php
namespace KryuuCommon\ComponentRenderer\Renderer;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ComponentRendererFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $compiler = $container->get('Compiler');

        $renderer = new ComponentRenderer($compiler);
        $renderer->setHelperPluginManager($container->get('ViewHelperManager'));
        $renderer->setResolver($container->get('ViewResolver'));
        return $renderer;
    }
}

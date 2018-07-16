<?php
namespace KryuuCommon\ComponentRenderer;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class CompilerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $parser = $container->get('ComponentParser');
        return new Compiler($parser);
    }
}

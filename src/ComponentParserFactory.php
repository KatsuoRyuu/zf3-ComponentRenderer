<?php
namespace KryuuCommon\ComponentRenderer;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ComponentParserFactory implements FactoryInterface
{
    private $componentListRaw;
    private $componentList;

    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $config = $container->get('config');
        return new ComponentParser(array_keys($config['view_helpers']['aliases']), $container);
    }
}

<?php
namespace KryuuCommon\ComponentRenderer\Strategy;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ComponentStrategyFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $ci, $requestName, array $options = null)
    {
        $viewRenderer = $ci->get('ComponentRenderer');
        return new ComponentStrategy($viewRenderer);
    }
}

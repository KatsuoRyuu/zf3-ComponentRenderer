<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\ComponentRenderer;

class Module
{

//    public function onBootstrap(MvcEvent $e)
//    {
//    $sharedEvents        = $e->getApplication()->getEventManager()->getSharedManager();
//    $sm = $e->getApplication()->getServiceManager();
//
//    $sharedEvents->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function($e) use ($sm) {
//        $strategy = $sm->get('ComponentStrategy');
//        $view     = $sm->get('ViewManager')->getView();
//        $strategy->attach($view->getEventManager());
//        }, 100);
//    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}

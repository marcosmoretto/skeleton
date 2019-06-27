<?php

namespace Core;

 use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
 use Zend\ModuleManager\Feature\ConfigProviderInterface;

 class Module implements AutoloaderProviderInterface, ConfigProviderInterface
 {
     public function getAutoloaderConfig()
     {
         return [
             'ZF\Apigility\Autoloader' => [
                 'namespaces' => [
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                 ],
             ]
         ];

     }

     public function getConfig()
     {
         return include __DIR__ . '/config/module.config.php';
     }
 }
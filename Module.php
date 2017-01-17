<?php
namespace RtFilterForm;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'rtfilterform' => function($helpers){
                    $services = $helpers->getServiceLocator();
                    return new \RtFilterForm\View\Helper\RtFilterForm($services);
                }
            ),
        );
    }
}
<?php

namespace RtFilterForm\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FilterForm implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $object = new \RtFilterForm\Form\FilterForm($serviceLocator);        
        return $object;
    }
}
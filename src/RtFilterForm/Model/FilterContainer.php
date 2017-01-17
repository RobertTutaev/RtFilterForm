<?php

namespace RtFilterForm\Model;

use Zend\Session\Container;

class FilterContainer {
    
    private $container;
    
    public function __construct($name){
        $this->container=new Container('rtfilterform__'.$name);
    }
    
    public function setFilterFormDefault(array $FilterFormDefault){
        $this->container->filter=$FilterFormDefault;
    }
    
    public function getFilterFormDefault(){
        return $this->container->filter;
    }
}
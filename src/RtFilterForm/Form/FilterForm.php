<?php

namespace RtFilterForm\Form;
 
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
 
class FilterForm extends Form {
    
    private $au;
    private $cnfg;
    private $value0;
    private $field0;
    private $value1;
    private $field1;
    private $limit;
    private $default;
    private $desc = array(0 => ' ASC', 1 => ' DESC');
    
    //Вывод установленного по умолчанию значения
    public function getDataDefault() {
        return 
            array(
                'f_search'  =>  $this->field0[$this->default['f_search']],
                'f_value'   =>  $this->default['f_exact']?$this->default['f_value']:'%'.$this->default['f_value'].'%',
                'f_sort'    =>  $this->field1[$this->default['f_sort']],
                'f_desc'    =>  $this->desc[$this->default['f_desc']],
                'f_limit'   =>  $this->limit[$this->default['f_limit']],
            );    
    }
    
    //Считываем или записываем данные в сессию
    protected function setDefaultOptions($name) {
        //1. Считываем значения фильтра из конфига
        $this->value0   =$this->cnfg['filter_form'][$name]['value0'];
        $this->field0   =$this->cnfg['filter_form'][$name]['field0'];
        $this->value1   =$this->cnfg['filter_form'][$name]['value1'];
        $this->field1   =$this->cnfg['filter_form'][$name]['field1'];
        $this->default  =$this->cnfg['filter_form'][$name]['default'];
        
        //2. Считываем или записываем данные в сессию
        $this->au=new \RtFilterForm\Model\FilterContainer($name);
        $v=$this->au->getFilterFormDefault();
        if(isset($v))
            $this->default=$v;
        else 
            $this->au->setFilterFormDefault($this->default);
    }
    
    //Сохранение установленных по умолчанию значений
    public function setDataDefault($name) {
        $this->setDefaultOptions($name);
        $this->default['f_value']   ='';
        $this->default['f_exact']   =0;
        $this->au->setFilterFormDefault($this->default);
    }    
    
    //Переустановка и вывод значения по умолчанию 
    public function getData($flag = \Zend\Form\FormInterface::VALUES_NORMALIZED) {
        
        //Вызов родителя для проверки IsValid()
        parent::getData($flag = \Zend\Form\FormInterface::VALUES_NORMALIZED);
        //Переприсваиваем дефолтные значения значениями введенными в элементы формы
        $this->default['f_search']  =$this->get('f_search') ->getValue();
        $this->default['f_value']   =$this->get('f_value')  ->getValue();
        $this->default['f_exact']   =$this->get('f_exact')  ->getValue();
        $this->default['f_sort']    =$this->get('f_sort')   ->getValue();
        $this->default['f_desc']    =$this->get('f_desc')   ->getValue();
        $this->default['f_limit']   =$this->get('f_limit')  ->getValue();
        //Сохраняем новые дефолтные значения в сессию
        $this->au->setFilterFormDefault($this->default);
        //Возвращаем декодированный дефолтный результат
        return $this->getDataDefault();
    }    
     
    public function __construct($sl) {
        // Инициализация формы
        parent::__construct('f_form');
        $this->setAttribute('action', '');
        $this->setAttribute('method', 'post');
        $this->setInputFilter($this->getFilters());
        
        // Считываем значения из конфига
        $this->cnfg     =$sl->get('Config');
        $this->limit    =$this->cnfg['filter_form']['limit_values'];
     }
    
     public function setFilter($name) {
         
        $this->setDefaultOptions($name);
        
        //3. Поиск по полю
        $this->add(array(
            'name' => 'f_search',
            'type' => 'select',
            'required'  => False,
            'options'   => array(
                'label' => 'Search in',
                'value_options' => $this->value0,
            ),
            'attributes' => array(
                'id'        => 'f_search',
                'value'     => $this->default['f_search'],
                'onchange'  => "document.getElementById('f_value').value='';",
        )));
        
        //4. Значение в поисковом поле
        $this->add(array(
            'name' => 'f_value',
            'type' => 'text',
            'options'   => array(
                'label' => 'Value',
            ),
            'attributes'    => array(
                'id'        => 'f_value',
                'size'      => '32',
                'value'     =>$this->default['f_value'],
        )));
        
        //5. Искать точно
        $this->add(array(
            'name' => 'f_exact',
            'type' => 'checkbox',
            'required'  => False,
            'options'   => array(
                'label' => 'Exact',
            ),
            'attributes'    => array(
                'id'        => 'f_exact',
                'value'     => $this->default['f_exact'],
        )));
        
        //6. Сортировка по полю
        $this->add(array(
            'name' => 'f_sort',
            'type' => 'select',
            'required'  => False,
            'options'   => array(
                'label' => 'Sort by',
                'value_options' => $this->value1,
            ),
            'attributes'    => array(
                'id'        => 'f_sort',
                'value'     => $this->default['f_sort'],
        )));
        
        //7. Сортировка по возрастанию/убыванию
        $this->add(array(
            'name' => 'f_desc',
            'type' => 'checkbox',
            'required'  => False,
            'options'   => array(
                'label' => 'Descending',
            ),
            'attributes'    => array(
                'id'        => 'f_desc',
                'value'     => $this->default['f_desc'],
        )));
        
        //8. По сколько выводить строк
        $this->add(array(
            'name' => 'f_limit',
            'type' => 'select',
            'options'           => array(
                'label'         => 'Limit',
                'value_options' => $this->limit,
            ),
            'attributes'    => array(
                'id'        => 'f_limit',
                'value'     => $this->default['f_limit'],
        )));
    }
    
    // Присвоение фильтров и валидаторов элементам формы
    protected function getFilters() {
        $inputFilter = new InputFilter();
        $factory = new InputFactory();
        
        //f_value
        $inputFilter->add($factory->createInput(array(
            'name' => 'f_value',
            'required' => False,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 50)
                    )
            )
        )));
        
        return $inputFilter;
    }
}
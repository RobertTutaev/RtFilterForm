<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'filterform' => 'RtFilterForm\Factory\FilterForm',
        ),
    ),
    'filter_form' => array(
        'limit_values' => array(
            0 => '10',
            1 => '20',
            2 => '40',
            3 => '60',
            4 => '100',
        )
    ),
);
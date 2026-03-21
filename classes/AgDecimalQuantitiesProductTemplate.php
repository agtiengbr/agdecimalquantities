<?php

class AgDecimalQuantitiesProductTemplate extends AgObjectModel
{
    public $id_product;
    public $id_ag_decimal_quantities_template;

    public static $definition = array(
        'table' => 'ag_decimal_quantities_product_template',
        'primary' => 'id_ag_decimal_quantities_product_template',
        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true, 'db_type' => 'int unsigned'),
            'id_ag_decimal_quantities_template' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true, 'db_type' => 'int unsigned'),
        ),
    );
}
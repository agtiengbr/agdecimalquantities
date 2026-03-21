<?php

class AgDecimalQuantitiesTemplate extends AgObjectModel
{
    public $name;
    public $unit;
    public $minimal_quantity;
    public $fraction;

    public static $definition = array(
        'table' => 'ag_decimal_quantities_template',
        'primary' => 'id_ag_decimal_quantities_template',
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255, 'db_type' => 'varchar(255)'),
            'unit' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255, 'db_type' => 'varchar(255)'),
            'minimal_quantity' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true, 'db_type' => 'float'),
            'fraction' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true, 'db_type' => 'float'),
        ),
    );

    public static function getTemplates()
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template';
        return Db::getInstance()->executeS($sql);
    }
}
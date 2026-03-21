<?php

class OrderDetail extends OrderDetailCore
{
    public $decimal_quantity;
    public $unit;

    public function __construct($id = null) 
    {
        self::$definition['fields']['decimal_quantity'] = array('type' => self::TYPE_FLOAT);
        self::$definition['fields']['unit'] = array('type' => self::TYPE_STRING);

        parent::__construct($id);
    }

    public function getFields() {
        $add_field = parent::getFields();
        
        $add_field['decimal_quantity'] = (float) $this->decimal_quantity;
        $add_field['unit'] = pSQL($this->unit);

        return $add_field;
    }
}
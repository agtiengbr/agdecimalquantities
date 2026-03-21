<?php
abstract class HTMLTemplate extends HTMLTemplateCore
{
    protected function getTemplate($template_name)
    {
        if ($template_name != 'invoice.product-tab') {
            return parent::getTemplate($template_name);
        }

        return _PS_MODULE_DIR_ . "agdecimalquantities/pdf/invoice.product-tab.tpl";
    }
}

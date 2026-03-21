<?php

class AdminAgDecimalQuantitiesTemplateController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'ag_decimal_quantities_template';
        $this->className = 'AgDecimalQuantitiesTemplate';
        $this->lang = false;
        $this->bootstrap = true;

        parent::__construct();

        $this->fields_list = array(
            'id_ag_decimal_quantities_template' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'name' => array(
                'title' => $this->l('Name'),
            ),
            'unit' => array(
                'title' => $this->l('Unit'),
            ),
            'minimal_quantity' => array(
                'title' => $this->l('Minimal Quantity'),
                'type' => 'float',
            ),
            'fraction' => array(
                'title' => $this->l('Fraction'),
                'type' => 'float',
            ),
            'product_count' => array(
                'title' => $this->l('Number of Products'),
                'type' => 'int',
                'align' => 'center',
            ),
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );

        $this->addRowAction('edit');
        $this->addRowAction('deleteTemplate');
    }

    public function setMedia($isNewTheme=false)
    {
        parent::setMedia($isNewTheme);
        $this->addJS(_MODULE_DIR_ . 'agdecimalquantities/views/js/admin.js');
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Decimal Quantities Template'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Unit'),
                    'name' => 'unit',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Minimal Quantity'),
                    'name' => 'minimal_quantity',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Fraction'),
                    'name' => 'fraction',
                    'required' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        return parent::renderForm();
    }

    public function ajaxProcessSaveProductTemplate()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_template = (int)Tools::getValue('id_ag_decimal_quantities_template');

        if (!$id_product) {
            die(json_encode(['success' => false, 'message' => 'Invalid product ID']));
        }

        // Check if a record already exists for this product
        $existingEntry = new AgDecimalQuantitiesProductTemplate(
            Db::getInstance()->getValue(
                'SELECT id_ag_decimal_quantities_product_template FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product
            )
        );

        if (Validate::isLoadedObject($existingEntry)) {
            // Update existing record
            $existingEntry->id_ag_decimal_quantities_template = $id_template;
            $success = $existingEntry->update();
        } else {
            // Create a new record
            $newEntry = new AgDecimalQuantitiesProductTemplate();
            $newEntry->id_product = $id_product;
            $newEntry->id_ag_decimal_quantities_template = $id_template;
            $success = $newEntry->add();
        }

        die(json_encode(['success' => $success]));
    }

    public function getProductCount($id_ag_decimal_quantities_template)
    {
        $sql = 'SELECT COUNT(*) FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_ag_decimal_quantities_template = ' . (int)$id_ag_decimal_quantities_template;
        return Db::getInstance()->getValue($sql);
    }

    public function renderList()
    {
        $this->_select = 'a.*, (SELECT COUNT(*) FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template p WHERE p.id_ag_decimal_quantities_template = a.id_ag_decimal_quantities_template) AS product_count';
        $this->_join = '';
        $this->_group = '';
        $this->_orderBy = 'id_ag_decimal_quantities_template';
        $this->_orderWay = 'ASC';

        $this->context->smarty->assign(array(
            'currentIndex' => self::$currentIndex,
            'token' => $this->token,
            'templates' => AgDecimalQuantitiesTemplate::getTemplates(),
            'shop_ssl_url' => Tools::getShopDomainSsl(true, true) // Passando a URL SSL da loja
        ));

        return parent::renderList() . $this->context->smarty->fetch($this->getTemplatePath() . 'modal_delete_template.tpl');
    }

    public function displayDeleteTemplateLink($token, $id, $name)
    {
        $this->context->smarty->assign(array(
            'href' => self::$currentIndex . '&token=' . $token . '&deleteTemplate&id_ag_decimal_quantities_template=' . $id,
            'action' => $this->l('Delete Template')
        ));

        $tpl = $this->createTemplate('list_action_delete.tpl');
        return $tpl->fetch();
    }

    public function processDeleteTemplate()
    {
        $id_template_to_delete = (int)Tools::getValue('id_ag_decimal_quantities_template');
        $new_template = (int)Tools::getValue('new_template');

        if ($new_template) {
            $sql = 'UPDATE ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template SET id_ag_decimal_quantities_template = ' . (int)$new_template . ' WHERE id_ag_decimal_quantities_template = ' . (int)$id_template_to_delete;
        } else {
            $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_ag_decimal_quantities_template = ' . (int)$id_template_to_delete;
        }

        if (Db::getInstance()->execute($sql)) {
            $template = new AgDecimalQuantitiesTemplate($id_template_to_delete);
            $template->delete();
        }

        if (Tools::isSubmit('ajax')) {
            die(json_encode(array('success' => true)));
        } else {
            Tools::redirectAdmin(self::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminAgDecimalQuantitiesTemplate'));
        }
    }
}
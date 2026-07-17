<?php
require_once _PS_MODULE_DIR_ . 'agcliente/lib/AgModule.php';

use PrestaShop\PrestaShop\Adapter\Presenter\Order\OrderPresenter;

class baseAgDecimalQuantities extends AgModule
{
    // Define os hooks utilizados pelo módulo
    protected $hooks = ['displayAdminProductsExtra', 'actionAdminProductsControllerSaveAfter', 'actionAdminControllerSetMedia', 'displayProductActions', 'displayCartProductQuantity', 'displayHeader', 'actionAdminProductsListingFieldsModifier', 'actionAdminProductsListingResultsModifier', 'displayAdminOrder', 'ActionObjectOrderDetailAddBefore', 'displayOrderConfirmation', 'displayOrderDetail', 'actionProductUpdate'];

    // Define as abas do módulo
    protected $tabs = array(
        array(
            'name' => [
                'en' => 'Decimal Templates',
                'br' => 'Templates de Frações'
            ],
            'className' => 'AdminAgDecimalQuantitiesTemplate',
            'active' => true,
        ),
    );

    // Define a aba principal do módulo
    protected $main_tab = 'AdminCatalog';

    // Construtor do módulo
    public function __construct()
    {
        // Inicializa o módulo, define suas propriedades e chama o construtor da classe pai
        $this->name = 'agdecimalquantities';
        $this->tab = 'front_office_features';
        $this->version = '1.0.5';
        $this->author = 'AGTI';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Decimal Quantities');
        $this->description = $this->l('Allows customers to purchase decimal quantities of products.');
    }

    // Função de instalação do módulo
    public function install()
    {
        try {
            Db::getInstance()->execute("ALTER TABLE " . _DB_PREFIX_ .  "order_detail ADD COLUMN  decimal_quantity float");
            Db::getInstance()->execute("ALTER TABLE " . _DB_PREFIX_ .  "order_detail ADD COLUMN  unit varchar(255)");
        } catch (Exception $e) {dump($e);exit();}
        // Instala o módulo e registra os hooks necessários
        return parent::install();
    }

    // Função para exibir a página de configurações do módulo
    public function getContent()
    {
        if (Tools::isSubmit('saveTemplate')) {
            $id_product = (int)Tools::getValue('id_product');
            $id_template = (int)Tools::getValue('id_ag_decimal_quantities_template');

            // Remover mapeamento existente
            Db::getInstance()->delete(_DB_PREFIX_ . 'ag_decimal_quantities_product_template', 'id_product = ' . (int)$id_product);

            // Inserir novo mapeamento se um template foi selecionado
            if ($id_template > 0) {
                Db::getInstance()->insert(_DB_PREFIX_ . 'ag_decimal_quantities_product_template', array(
                    'id_product' => (int)$id_product,
                    'id_ag_decimal_quantities_template' => (int)$id_template,
                ));
            }

            // Redirecionar para evitar reenvio do formulário
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name);
        }

        // ... código existente para exibir a página de configurações ...
    }

    // Hook para exibir opções de templates de frações na página de administração de produtos
    public function hookDisplayAdminProductsExtra($params)
    {
        $id_product = (int)$params['id_product'];

        // Buscar todos os templates
        $templates = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template');

        // Adicionar opção para nenhum template
        array_unshift($templates, array('id_ag_decimal_quantities_template' => 0, 'name' => 'Nenhum template'));

        // Obter o template selecionado para o produto
        $selected_template = Db::getInstance()->getValue('SELECT id_ag_decimal_quantities_template FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product);

        // Atribuir os templates e o template selecionado ao Smarty
        $this->context->smarty->assign(array(
            'templates' => $templates,
            'selected_template' => $selected_template,
            'id_product' => $id_product,
            'module_name' => $this->name
        ));

        return $this->display($this->_path, 'admin_product_extra.tpl');
    }

    // Hook para salvar o template de fração selecionado para o produto após salvar o produto na administração
    public function hookActionAdminProductsControllerSaveAfter($params)
    {
        $id_product = (int)$params['id_product'];
        $id_template = (int)Tools::getValue('id_ag_decimal_quantities_template');

        // Obter o template atual do produto
        $current_template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product);
        $current_fraction = 1;
        if ($current_template) {
            $current_template_data = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$current_template['id_ag_decimal_quantities_template']);
            if ($current_template_data) {
                $current_fraction = (float)$current_template_data['fraction'];
            }
        }

        // Remover mapeamento existente
        Db::getInstance()->delete(_DB_PREFIX_ . 'ag_decimal_quantities_product_template', 'id_product = ' . (int)$id_product);

        // Inserir novo mapeamento se um template foi selecionado
        if ($id_template > 0) {
            // Obter o novo template selecionado
            $new_template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$id_template);
            if ($new_template) {
                $new_fraction = (float)$new_template['fraction'];

                // Atualizar a quantidade mínima do produto
                if ($new_fraction > 0) {
                    $minimal_quantity = (float)$new_template['minimal_quantity'];
                    $new_minimal_quantity = $minimal_quantity / $new_fraction;

                    // Usar o object model da classe Product para atualizar a quantidade mínima
                    $product = new Product($id_product);
                    $product->minimal_quantity = (int)$new_minimal_quantity;
                    $product->save();

                    // Ajustar o estoque do PrestaShop
                    $quantity = Db::getInstance()->getValue('SELECT quantity FROM ' . _DB_PREFIX_ . 'stock_available WHERE id_product = ' . (int)$id_product);
                    $new_quantity = ($quantity * $current_fraction) / $new_fraction;
                    Db::getInstance()->update('stock_available', array(
                        'quantity' => $new_quantity
                    ), 'id_product = ' . (int)$id_product);
                }
            }

            // Inserir novo mapeamento
            Db::getInstance()->insert(_DB_PREFIX_ . 'ag_decimal_quantities_product_template', array(
                'id_product' => (int)$id_product,
                'id_ag_decimal_quantities_template' => (int)$id_template,
            ));
        }
    }

    // Hook to add JavaScript and fetch the current template in the product edit page
    public function hookActionAdminControllerSetMedia()
    {
        // Check if we are in the product edit page
        $currentUrl = $_SERVER['REQUEST_URI'];
        if (preg_match('/\/sell\/catalog\/products\/(\d+)/', $currentUrl, $matches)) {
            $id_product = (int)$matches[1];

            // Fetch the selected template for the product
            $selected_template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product);

            // Define default step value
            $step_quantity = 1;
            $unity = '';
            ob_end_clean();
            if ($selected_template) {
                $template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$selected_template['id_ag_decimal_quantities_template']);
                if ($template) {
                    $step_quantity = (float)$template['fraction'];
                    $unity = $template['unit'];
                }
            }

            // Generate the admin controller URL
            $controller_url = $this->context->link->getAdminLink('AdminAgDecimalQuantitiesTemplate');

            // Pass the step value to JavaScript
            Media::addJsDef(array(
                'productStep' => $step_quantity,
                'productUnity' => $unity,
                'adminControllerLink' => $controller_url
            ));
        }

        // Add the JavaScript necessary for the administration of products
        $this->context->controller->addJs($this->_path . 'views/js/admin_product.js');
        Media::addJsDef(array(
            'agdecimalquantities_config_url' => $this->context->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name,
        ));

        // Add the JavaScript necessary for the order edit page
        $this->context->controller->addJs($this->_path . 'views/js/admin_order.js');
    }

    // Hook para exibir a quantidade mínima e a fração do produto na página do produto
    public function hookDisplayProductActions($params)
    {
        $id_product = (int)$params['product']['id_product'];

        // Obter o template selecionado para o produto
        $selected_template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product);

        // Definir valores padrão caso não haja template selecionado
        $min_quantity = 1;
        $step_quantity = 1;
        $unit = '';

        if ($selected_template) {
            $template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$selected_template['id_ag_decimal_quantities_template']);
            if ($template) {
                $min_quantity = (float)$template['minimal_quantity'];
                $step_quantity = (float)$template['fraction'];
                $unit = $template['unit'];
            }
        }

        // Atribuir a quantidade mínima e a fração ao Smarty
        $this->context->smarty->assign(array(
            'min_quantity' => $min_quantity,
            'step_quantity' => $step_quantity,
            'unit' => $unit
        ));

        return $this->display($this->_path, 'displayProductActions.tpl');
    }

    // Hook para exibir a quantidade fracionada e a unidade de medida no carrinho de compras
    public function hookDisplayCartProductQuantity($params)
    {
        $id_product = (int)$params['product']['id_product'];
        $quantity = (float)$params['product']['cart_quantity'] ?: 1;

        // Obter o template selecionado para o produto
        $selected_template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product);

        // Definir valores padrão caso não haja template selecionado
        $fraction = 1;
        $unit = '';
        $minimal_quantity = 1;

        if ($selected_template) {
            $template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$selected_template['id_ag_decimal_quantities_template']);
            if ($template) {
                $fraction = (float)$template['fraction'];
                $unit = $template['unit'];
                $minimal_quantity = (float)$template['minimal_quantity'];
            }
        }

        $fractional_quantity = $quantity * $fraction;

        // Atribuir a quantidade fracionada, a unidade de medida, o ID do produto, a fração e a quantidade mínima ao Smarty
        $this->context->smarty->assign(array(
            'fractional_quantity' => $fractional_quantity,
            'unit' => $unit,
            'product_id' => $id_product,
            'fraction' => $fraction,
            'minimal_quantity' => $minimal_quantity,
        ));

        return $this->display($this->_path, 'cart_product_quantity.tpl');
    }

    // Hook para adicionar o JavaScript necessário para o carrinho de compras e a página de detalhes do pedido
    public function hookDisplayHeader()
    {
        $this->context->controller->addJS($this->_path . 'views/js/cart.js');
        $this->context->controller->addJS($this->_path . 'views/js/order-confirmation.js');
        $this->context->controller->addCSS($this->_path . 'views/css/order-confirmation.css');
        $this->context->controller->addCSS($this->_path . 'views/css/geral.css');

        // Adiciona o JavaScript necessário para a página de detalhes do pedido
        $this->context->controller->addJS($this->_path . 'views/js/order_detail.js');

        // Adiciona o JavaScript e CSS necessários para a página do produto
        if ($this->context->controller->php_self === 'product') {
            $this->context->controller->addCSS($this->_path . 'views/css/product-page.css');
            $this->context->controller->addJS($this->_path . 'views/js/product_page.js');
        }

        // Adiciona o JavaScript e CSS necessários para a página do carrinho
        if ($this->context->controller->php_self === 'cart') {
            $this->context->controller->addCSS($this->_path . 'views/css/cart-page.css');
            $this->context->controller->addJS($this->_path . 'views/js/cart_page.js');
        }

        // Adiciona o JavaScript e CSS necessários para a página do checkout
        if ($this->context->controller->php_self === 'order') {
            $this->context->controller->addCSS($this->_path . 'views/css/checkout-page.css');
        }
    }

    // Hook para modificar a exibição da quantidade em estoque na listagem de produtos no admin
    public function hookActionAdminProductsListingFieldsModifier($params)
    {
        $params['fields']['sav_quantity'] = array(
            'title' => $this->l('Quantity'),
            'align' => 'center',
            'class' => 'fixed-width-xs',
            'callback' => 'callbackSavQuantity',
            'callback_object' => $this
        );
    }

    // Callback para exibir a quantidade fracionada na listagem de produtos no admin
    public function callbackSavQuantity($echo, $tr)
    {
        $id_product = (int)$tr['id_product'];
        $quantity = Db::getInstance()->getValue('SELECT quantity FROM ' . _DB_PREFIX_ . 'stock_available WHERE id_product = ' . (int)$id_product);

        // Obter o template selecionado para o produto
        $selected_template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product);

        // Definir valores padrão caso não haja template selecionado
        $fraction = 1;
        $unit = '';

        if ($selected_template) {
            $template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$selected_template['id_ag_decimal_quantities_template']);
            if ($template) {
                $fraction = (float)$template['fraction'];
                $unit = $template['unit'];
            }
        }

        // Calcular a quantidade fracionada
        $fractional_quantity = $quantity / $fraction;

        return $fractional_quantity . ' ' . $unit;
    }

    // Hook para modificar os resultados da listagem de produtos no admin
    public function hookActionAdminProductsListingResultsModifier($params)
    {
        foreach ($params['products'] as &$product) {
            $id_product = (int)$product['id_product'];
            $quantity = Db::getInstance()->getValue('SELECT quantity FROM ' . _DB_PREFIX_ . 'stock_available WHERE id_product = ' . (int)$id_product);

            // Obter o template selecionado para o produto
            $selected_template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product);

            // Definir valores padrão caso não haja template selecionado
            $fraction = 1;
            $unit = '';

            if ($selected_template) {
                $template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$selected_template['id_ag_decimal_quantities_template']);
                if ($template) {
                    $fraction = (float)$template['fraction'];
                    $unit = $template['unit'];
                }
            }

            // Calcular a quantidade fracionada
            $fractional_quantity = $quantity * $fraction;

            $product['sav_quantity'] = $fractional_quantity . ' ' . $unit;
        }
    }


    public function hookActionObjectOrderDetailAddBefore($params)
    {
        $order_detail = &$params['object'];
        $id_product = $order_detail->product_id;

        // Obter o template selecionado para o produto no momento da compra
        $selected_template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product);

        if ($selected_template) {
            $template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$selected_template['id_ag_decimal_quantities_template']);

            if ($template) {
                $fraction = (float)$template['fraction'];
                $unit = $template['unit'];
            }

            $order_detail->decimal_quantity = $order_detail->product_quantity * $fraction;
            $order_detail->unit = $unit;
        }
    }

    public function hookDisplayOrderConfirmation($params)
    {
        $order = $params['order'];
        $orderPresenter = new OrderPresenter();
        $presentedOrder = $orderPresenter->present($order);

        // Convert OrderLazyArray to a regular array for modification
        $presentedOrderArray = json_decode(json_encode($presentedOrder), true);

        foreach ($presentedOrderArray['products'] as &$product) {
            $order_detail = new OrderDetail($product['id_order_detail']);
            $product['decimal_quantity'] = $order_detail->decimal_quantity;
            $product['unit'] = $order_detail->unit;
        }

        $this->context->smarty->assign('order', $presentedOrderArray);
        return $this->display($this->_path, 'views/templates/hook/order-confirmation-table.tpl');
    }


    public function hookDisplayAdminOrder($params)
    {
        $id_order = $params['id_order'];
        $obj = new Order($id_order);

        $products = $obj->getProducts();

        foreach ($products as &$product) {
            $id_product = (int)$product['product_id'];
            $quantity = StockAvailable::getQuantityAvailableByProduct($id_product);

            // Obter o template selecionado para o produto
            $selected_template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product);

            // Definir valores padrão caso não haja template selecionado
            $fraction = 1;

            if ($selected_template) {
                $template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$selected_template['id_ag_decimal_quantities_template']);
                if ($template) {
                    $fraction = (float)$template['fraction'];
                }
            }

            // Calcular a quantidade fracionada disponível
            $available_quantity = $quantity * $fraction;
            $product['available_quantity'] = $available_quantity;
        }

        $this->context->smarty->assign(['products' => $products]);

        return $this->display($this->_path, 'display_admin_order.tpl');
    }

    // Hook para exibir a quantidade fracionada comprada pelo cliente no frontoffice
    public function hookDisplayOrderDetail($params)
    {
        
        $order = new Order($params['order']->id);
        $products = $order->getProducts();

        foreach ($products as &$product) {
            $id_product = (int)$product['product_id'];
            $quantity = StockAvailable::getQuantityAvailableByProduct($id_product);

            // Obter o template selecionado para o produto
            $selected_template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product);

            // Definir valores padrão caso não haja template selecionado
            $fraction = 1;

            if ($selected_template) {
                $template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$selected_template['id_ag_decimal_quantities_template']);
                if ($template) {
                    $fraction = (float)$template['fraction'];
                }
            }

            // Calcular a quantidade fracionada disponível
            $available_quantity = $quantity / $fraction;
            $product['available_quantity'] = $available_quantity;

            // Calcular a quantidade fracionada comprada
            $product['decimal_quantity'] = $product['product_quantity'] * $fraction;
        }

        $this->context->smarty->assign(['products' => $products]);

        return $this->display($this->_path, 'views/templates/hook/display_order_detail_list.tpl');
    }

    // Hook to add JavaScript and fetch the current template in the product edit page
    public function hookDisplayBackofficeHeader($params)
    {
        // Check if we are in the product edit page
        if (Tools::getValue('controller') === 'AdminProducts' && Tools::getValue('id_product')) {
            $id_product = (int)Tools::getValue('id_product');

            // Fetch the selected template for the product
            $selected_template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product);

            // Define default step value
            $step_quantity = 1;

            if ($selected_template) {
                $template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$selected_template['id_ag_decimal_quantities_template']);
                if ($template) {
                    $step_quantity = (float)$template['fraction'];
                }
            }

            // Pass the step value to JavaScript
            Media::addJsDef(array(
                'productStep' => $step_quantity,
            ));
        }
    }
}
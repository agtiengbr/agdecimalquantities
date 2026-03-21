<?php
class PaymentModule extends PaymentModuleCore
{
    protected function createOrderFromCart(
        Cart $cart,
        Currency $currency,
        $productList,
        $addressId,
        $context,
        $reference,
        $secure_key,
        $payment_method,
        $name,
        $dont_touch_amount,
        $amount_paid,
        $warehouseId,
        $cart_total_paid,
        $debug,
        $order_status,
        $id_order_state,
        $carrierId = null
    ) {
        $ret = parent::createOrderFromCart(
            $cart,
            $currency,
            $productList,
            $addressId,
            $context,
            $reference,
            $secure_key,
            $payment_method,
            $name,
            $dont_touch_amount,
            $amount_paid,
            $warehouseId,
            $cart_total_paid,
            $debug,
            $order_status,
            $id_order_state,
            $carrierId = null
        );
        
        $order = &$ret['order'];

        //atualiza a quantidade fracionada ao array product_list
        foreach ($order->product_list as &$product) {
            // Obter o template selecionado para o produto no momento da compra
            $id_product = $product['id_product'];

            $selected_template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$id_product);

            if ($selected_template) {
                $template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$selected_template['id_ag_decimal_quantities_template']);
    
                if ($template) {
                    $fraction = (float)$template['fraction'];
                    $unit = $template['unit'];
                }

                $product['decimal_qty'] = $product['quantity'] * $fraction;
                $product['unit'] = $product['quantity'] * $unit;
            }

            if (!$product['decimal_qty']) {
                $product['decimal_qty'] = $product['quantity'];
                $product['unit'] = '';
            }
        }

        return $ret;
    }


    protected function getPartialRenderer()
    {
        require_once _PS_MODULE_DIR_ . 'agdecimalquantities/ADQMailPartialTemplateRenderer.php';
        if (!$this->partialRenderer) {
            $this->partialRenderer = new ADQMailPartialTemplateRenderer($this->context->smarty);
        }

        return $this->partialRenderer;
    }
}
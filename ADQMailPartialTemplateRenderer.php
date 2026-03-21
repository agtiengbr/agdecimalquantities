<?php

use PrestaShop\PrestaShop\Adapter\MailTemplate\MailPartialTemplateRenderer;
use PrestaShop\PrestaShop\Core\Language\LanguageInterface;

class ADQMailPartialTemplateRenderer extends MailPartialTemplateRenderer
{
    /** @var Smarty */
    private $smarty;

    /**
     * @param Smarty $smarty
     */
    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    public function render($partialTemplateName, LanguageInterface $language, array $variables = [], $cleanComments = false)
    {
        // Handle fractional quantities for specific email templates
        if (in_array($partialTemplateName, ['order_conf_product_list.txt', 'order_conf_product_list.tpl'])) {
            foreach ($variables[0] as $name=>&$val) {
                if ($name == 'id_product') {
                    $productTemplate = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_product_template WHERE id_product = ' . (int)$val);
                    if ($productTemplate) {
                        $template = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'ag_decimal_quantities_template WHERE id_ag_decimal_quantities_template = ' . (int)$productTemplate['id_ag_decimal_quantities_template']);
                        if ($template) {
                            $variables[0]['decimal_quantity'] = $variables[0]['quantity'] * (float)$template['fraction'];
                            $variables[0]['unit'] = $template['unit'];

                            $prod = new Product($val);
                            $variables[0]['unit_price_full'] = Tools::displayPrice($prod->unit_price) . ' ' . $prod->unity;
                        }
                    }
                }
            }
        }

        $potentialPaths = [
            _PS_MODULE_DIR_ . "agdecimalquantities" . DIRECTORY_SEPARATOR . "mails" . DIRECTORY_SEPARATOR . $language->getIsoCode() . DIRECTORY_SEPARATOR . $partialTemplateName,
            _PS_MODULE_DIR_ . "agdecimalquantities" . DIRECTORY_SEPARATOR . "mails" . DIRECTORY_SEPARATOR . $partialTemplateName,
            _PS_THEME_DIR_ . 'mails' . DIRECTORY_SEPARATOR . $language->getIsoCode() . DIRECTORY_SEPARATOR . $partialTemplateName,
            _PS_MAIL_DIR_ . $language->getIsoCode() . DIRECTORY_SEPARATOR . $partialTemplateName,
            _PS_THEME_DIR_ . 'mails' . DIRECTORY_SEPARATOR . 'en' . DIRECTORY_SEPARATOR . $partialTemplateName,
            _PS_MAIL_DIR_ . 'en' . DIRECTORY_SEPARATOR . $partialTemplateName,
            _PS_MAIL_DIR_ . '_partials' . DIRECTORY_SEPARATOR . $partialTemplateName,
        ];

        foreach ($potentialPaths as $path) {
            if (Tools::file_exists_cache($path)) {
                $this->smarty->assign('list', $variables);
                $content = $this->smarty->fetch($path);

                if ($cleanComments) {
                    $content = preg_replace('/\s?<!--.*?-->\s?/s', '', $content);
                }

                return $content;
            }
        }

        return '';
    }
}
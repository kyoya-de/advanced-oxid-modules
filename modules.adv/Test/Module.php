<?php

namespace Test;

use D4rk4ng3l\Oxid\AdvancedModule\Metadata;

class Module extends Metadata
{
    protected function configure()
    {
        $this
            ->setDescription(
                array(
                    'de' => 'Modul für die Zahlung mit PayPal. Erfordert einen OXID eFire Account und die abgeschlossene Aktivierung des Portlets "PayPal".',
                    'en' => 'Module for PayPal payment. An OXID eFire account is required as well as the finalized activation of the portlet "PayPal".'
                )
            )
            ->setThumbnail('logo.jpg')
            ->setAuthor('OXID eSales AG')
            ->setVersion('2.0.3')
            ->setEmail('info@oxid-esales.com')
            ->setUrl('http://www.oxid-esales.com');

        // let's set some "extensions"
        $this->getExtensions()
            ->addExtension('order', 'oe/oepaypal/controllers/oepaypalorder')
            ->addExtension('payment', 'oe/oepaypal/controllers/oepaypalpayment')
            ->addExtension('wrapping', 'oe/oepaypal/controllers/oepaypalwrapping')
            ->addExtension('oxviewconfig', 'oe/oepaypal/controllers/oepaypaloxviewconfig')
            ->addExtension('oxaddress', 'oe/oepaypal/models/oepaypaloxaddress')
            ->addExtension('oxuser', 'oe/oepaypal/models/oepaypaloxuser')
            ->addExtension('oxorder', 'oe/oepaypal/models/oepaypaloxorder')
            ->addExtension('oxbasket', 'oe/oepaypal/models/oepaypaloxbasket')
            ->addExtension('oxbasketitem', 'oe/oepaypal/models/oepaypaloxbasketitem')
            ->addExtension('oxarticle', 'oe/oepaypal/models/oepaypaloxarticle')
            ->addExtension('oxcountry', 'oe/oepaypal/models/oepaypaloxcountry')
            ->addExtension('oxstate', 'oe/oepaypal/models/oepaypaloxstate');

        // here we add some templates
        $this->getTemplates()
            ->addTemplate('order_dhl.tpl', 'oe/efi_dhl/out/admin/tpl/order_dhl.tpl');

        // and we need some files
        $this->getFiles()
            ->addClass('oePayPalException', 'oe/oepaypal/core/exception/oepaypalexception.php')
            ->addClass('oePayPalCheckoutService', 'oe/oepaypal/core/oepaypalcheckoutservice.php')
            ->addClass('oePayPalLogger', 'oe/oepaypal/core/oepaypallogger.php')
            ->addClass('oePayPalPortlet', 'oe/oepaypal/core/oepaypalportlet.php')
            ->addClass('oePayPalDispatcher', 'oe/oepaypal/controllers/oepaypaldispatcher.php')
            ->addClass(
                'oePayPalExpressCheckoutDispatcher',
                'oe/oepaypal/controllers/oepaypalexpresscheckoutdispatcher.php'
            )
            ->addClass('oePayPalStandardDispatcher', 'oe/oepaypal/controllers/oepaypalstandarddispatcher.php')
            ->addClass('oePaypal_EblLogger', 'oe/oepaypal/core/oeebl/oepaypal_ebllogger.php')
            ->addClass('oePaypal_EblPortlet', 'oe/oepaypal/core/oeebl/oepaypal_eblportlet.php')
            ->addClass('oePaypal_EblSoapClient', 'oe/oepaypal/core/oeebl/oepaypal_eblsoapclient.php')
            ->addClass('oepaypalevents', 'oe/oepaypal/core/oepaypalevents.php');

        // yep we've some events.
        $this->getEvents()
            ->setEvent(Metadata\Events::EVENT_ACTIVATE, 'oepaypalevents::onActivate')
            ->setEvent(Metadata\Events::EVENT_DEACTIVATE, 'oepaypalevents::onDeactivate');

        // and we've some blocks
        $this->getBlocks()
            ->addBlock(
                new Metadata\Block(
                    'widget/sidebar/partners.tpl',
                    'partner_logos',
                    '/views/blocks/oepaypalpartnerbox.tpl'
                )
            )
            ->addBlock(
                new Metadata\Block(
                    'page/checkout/basket.tpl',
                    'basket_btn_next_top',
                    '/views/blocks/oepaypalexpresscheckout.tpl'
                )
            )
            ->addBlock(
                new Metadata\Block(
                    'page/checkout/basket.tpl',
                    'basket_btn_next_bottom',
                    '/views/blocks/oepaypalexpresscheckout.tpl'
                )
            )
            ->addBlock(
                new Metadata\Block(
                    'page/checkout/payment.tpl',
                    'select_payment',
                    '/views/blocks/oepaypalpaymentselector.tpl'
                )
            );

        // oh forgot settings for the admin? No!
        $this->getSettings()
            ->addSetting(new Metadata\Setting('main', 'dMaxPayPalDeliveryAmount', Metadata\Setting::TYPE_STRING, '20'))
            ->addSetting(new Metadata\Setting('main', 'blPayPalLoggerEnabled', Metadata\Setting::TYPE_BOOL, 'false'));
    }

    public function getId()
    {
        return "oepaypal";
    }

    public function getTitle()
    {
        return "PayPal";
    }
}
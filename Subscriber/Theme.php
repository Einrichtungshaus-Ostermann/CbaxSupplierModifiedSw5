<?php

namespace CbaxSupplierModifiedSw5\Subscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Enlight\Event\SubscriberInterface;
use Shopware\Components\Theme\LessDefinition;
use Shopware\Components\Plugin\ConfigReader;

class Theme implements SubscriberInterface
{
    /**
     * @var
     */
    private $pluginPath;

    /**
     * @var
     */
    private $configReader;

    /**
     * @var
     */
    private $pluginName;

    /**
     * Theme constructor
     */
    public function __construct($pluginPath, $pluginName, ConfigReader $configReader)
    {
		$this->pluginPath = $pluginPath;
        $this->pluginName = $pluginName;
        $this->configReader = $configReader;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Theme_Inheritance_Template_Directories_Collected' => 'onTemplateDirectoriesCollect',
            'Theme_Compiler_Collect_Plugin_Less' => 'onAddLessFiles',
            'Theme_Compiler_Collect_Plugin_Javascript' => 'onAddJavascriptFiles',
        ];
    }

    /**
     * adds the plugin views directory to the shopware template directories.
     * needs to be done here, in order to overwrite the documents template.
     *
     * @param EventArgs $args
     */
    public function onTemplateDirectoriesCollect(\Enlight_Event_EventArgs $args)
    {
        $dirs = $args->getReturn();

        $dirs[] = $this->pluginPath . '/Resources/views/';

        $args->setReturn($dirs);
    }

    /**
     * Provides the file collection for less
     *
     * @return ArrayCollection
     */
    public function onAddLessFiles(\Enlight_Event_EventArgs $args)
    {
		//hier liefert constructer nur Standard Shop!
        //shop aus $args liefert richtigen (sub)shop
        $shop = $args->get('shop');
        $config_local = $this->configReader->getByPluginName($this->pluginName, $shop);

        if (!$config_local['active']) {
            return;
        }
		
		$display = ($config_local['hideCategories']) ? 'none' : 'block';
		
        $less = new LessDefinition(
            array(
				'cbax-supplier-modified-display' => $display,
				'cbax-supplier-modified-supplier-box-height' => $config_local['supplierBoxHeight']
			),
            [
                $this->pluginPath . '/Resources/views/frontend/_public/src/less/all.less'
            ],
            $this->pluginPath
        );

        return new ArrayCollection([$less]);
    }

    /**
     * Provides the file collection for js files
     *
     * @return ArrayCollection
     */
    public function onAddJavascriptFiles(\Enlight_Event_EventArgs $args)
    {
		//hier liefert constructer nur Standard Shop!
        //shop aus $args liefert richtigen (sub)shop
        $shop = $args->get('shop');
        $config_local = $this->configReader->getByPluginName($this->pluginName, $shop);

        if (!$config_local['active']) {
            return;
        }
		
        $jsFiles = [
            $this->pluginPath . '/Resources/views/frontend/_public/src/js/supplier_modified.js'
        ];

        return new ArrayCollection($jsFiles);
    }
}

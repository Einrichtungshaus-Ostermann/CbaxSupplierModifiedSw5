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
    private $pluginName;
	
	/**
     * @var
     */
	private $configReader;

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
		$shop = $args->get('shop');
		$config = $this->configReader->getByPluginName($this->pluginName, $shop);
		
		if (!$config['active']) {
			return;
		}
		
		$display = ($config['categories_hide']) ? 'none' : 'block';
		
        $less = new LessDefinition(
            array(
				'cbax-supplier-modified-display' => $display,
				'cbax-supplier-modified-brand-label-height' => $config['brandLabelHeight'],
				'cbax-supplier-modified-brand-label-width' => $config['brandLabelWidth']
			),
            [
                $this->pluginPath . '/Resources/views/frontend/_public/src/less/all.less'
            ],
            $this->pluginPath
        );

        return new ArrayCollection([$less]);
		
		$less = new LessDefinition(
            [],
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
		$shop = $args->get('shop');
		$config = $this->configReader->getByPluginName($this->pluginName, $shop);
		
		if (!$config['active']) {
			return;
		}
		
        $jsFiles = [
            $this->pluginPath . '/Resources/views/frontend/_public/src/js/supplier_modified.js'
        ];

        return new ArrayCollection($jsFiles);
    }
}

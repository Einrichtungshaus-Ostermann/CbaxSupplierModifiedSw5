<?php

class Shopware_Controllers_Frontend_SupplierModified extends Enlight_Controller_Action
{
	/**
     * @var
     */
	private $config;
	
	/**
     * @var $helperComponent
     */
	private $helperComponent;

	public function preDispatch()
	{
		$this->View()->setScope(Enlight_Template_Manager::SCOPE_PARENT);
	}

	public function init()
	{
		$this->config = Shopware()->Container()->get('shopware.plugin.cached_config_reader')->getByPluginName('CbaxSupplierModifiedSw5', Shopware()->Shop());
		
		$this->helperComponent = Shopware()->Container()->get('cbax_supplier_modified_sw5.supplier_modified_helper');
	}

	public function indexAction()
	{
		if (!$this->config['active'])
		{
			$this->redirect(array('controller' => 'index'));
			return;
		}

		$this->View()->loadTemplate("frontend/plugins/supplier_modified/index.tpl");

		$shop = Shopware()->Shop();
		$sCategoryStart = $shop->getCategory()->getId();

		switch ($config->displayMode) {
			case 'showName': $brandItemCls = ' label-only';
				break;
			case 'showLogo': $brandItemCls = ' logo-only';
				break;
			default: $brandItemCls = '';
		}

		$supplierModified['byChar'] = $this->helperComponent->getSupplierByChar($sCategoryStart, 0);
		$supplierModified['banner'] = $this->config['supplier_banner'];
		$supplierModified['bannerPosition'] = $this->config['bannerPositionInListing'];
		$supplierModified['topsellerShow'] = $this->config['showTopsellerInOverview'];
		$supplierModified['headline'] = $this->config['headline'];
		$supplierModified['text'] = $this->config['text'];
		$supplierModified['textPosition'] = $this->config['textPosition'];
		$supplierModified['displayMode'] = $this->config['displayMode'];
		$supplierModified['brandItemCls'] = $brandItemCls;
		$supplierModified['displayFilter'] = $this->config['displayFilter'];
		$supplierModified['duration'] = $this->config['duration'];
		$supplierModified['stop'] = $this->config['stop'];
		$supplierModified['hideSidebar'] = $this->config['hideSidebar'];
		$supplierModified['hideNavigationTitle'] = $this->config['hide_navigation_title'];
		$supplierModified['showArticleCount'] = $this->config['show_article_count'];

		$this->View()->CbaxSupplierModified = $supplierModified;
	}
}

?>
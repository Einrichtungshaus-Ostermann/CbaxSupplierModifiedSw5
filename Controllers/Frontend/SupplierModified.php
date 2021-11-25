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
		$shop = false;
		if (Shopware()->Container()->initialized('shop')) {
			$shop = Shopware()->Container()->get('shop');
		}
	
		if (!$shop) {
			$shop = Shopware()->Container()->get('models')->getRepository(\Shopware\Models\Shop\Shop::class)->getActiveDefault();
		}
	
		$this->config = Shopware()->Container()->get('shopware.plugin.cached_config_reader')->getByPluginName('CbaxSupplierModifiedSw5', $shop);
		
		$this->helperComponent = Shopware()->Container()->get('cbax_supplier_modified_sw5.supplier_modified_helper');
	}

	public function indexAction()
	{
		if (!$this->config['active'])
		{
			$this->redirect(array('controller' => 'index'));
			return;
		}
		
		$namespace = Shopware()->Snippets()->getNamespace('frontend/plugins/supplier_modified/index');
		$supplierStandardTitle = $namespace->get('SupplierMetaTitleStandard');
		
		$supplierStandardUrl = $this->helperComponent->getRewriteUrl($sCategoryStart);
		
		$breadcrumb = $this->View()->sBreadcrumb;
		$breadcrumb[] = array(
			'link' => $supplierStandardUrl,
			'name' => $supplierStandardTitle
		);
		$this->View()->sBreadcrumb = $breadcrumb;
		
		$this->View()->loadTemplate("frontend/plugins/supplier_modified/index.tpl");

		$shop = Shopware()->Shop();
		$sCategoryStart = $shop->getCategory()->getId();

		$supplierModified['byChar'] 					= $this->helperComponent->getSupplierByChar($sCategoryStart, 0);
		$supplierModified['banner'] 					= $this->config['supplierBanner'];
		$supplierModified['bannerPosition'] 			= $this->config['bannerPositionInOverview'];
		$supplierModified['topsellerShow'] 				= $this->config['showTopsellerInOverview'];
		$supplierModified['headline'] 					= $this->config['headline'];
		$supplierModified['text'] 						= $this->config['text'];
		$supplierModified['textPosition'] 				= $this->config['textPositionInOverview'];
		$supplierModified['displayMode'] 				= $this->config['displayMode'];
		$supplierModified['displayFilter'] 				= $this->config['displayFilter'];
		$supplierModified['duration'] 					= $this->config['duration'];
		$supplierModified['stop'] 						= $this->config['stop'];
		$supplierModified['hideSidebarDesktop'] 		= $this->config['hideSidebarDesktop'];
		$supplierModified['hideSidebarSmartphone'] 		= $this->config['hideSidebarSmartphone'];
		$supplierModified['hideNavigationTitle'] 		= $this->config['hideNavigationTitle'];
		$supplierModified['showActiveFilter'] 			= $this->config['showActiveFilter'];
		$supplierModified['showArticleCountOverview'] 	= $this->config['showArticleCountInOverview'];
		$supplierModified['showArticleCountSidebar'] 	= $this->config['showArticleCountInSidebar'];
		$supplierModified['template'] 					= $this->config['template'];
		$supplierModified['navigation'] 				= $this->config['navigation'];

		$this->View()->CbaxSupplierModified = $supplierModified;
	}
}

?>
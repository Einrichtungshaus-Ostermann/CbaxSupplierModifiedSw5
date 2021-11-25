<?php

namespace CbaxSupplierModifiedSw5\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Plugin\ConfigReader;
use CbaxSupplierModifiedSw5\Components\SupplierModifiedHelper;

class Frontend implements SubscriberInterface
{
	/**
     * @var
     */
    private $config;
	
	/**
     * @var $helperComponent
     */
	private $helperComponent;
	
	/**
     * Frontend constructor
     */
    public function __construct($pluginName, ConfigReader $configReader, SupplierModifiedHelper $helperComponent)
    {
		$shop = false;
		if (Shopware()->Container()->initialized('shop')) {
			$shop = Shopware()->Container()->get('shop');
		}
	
		if (!$shop) {
			$shop = Shopware()->Container()->get('models')->getRepository(\Shopware\Models\Shop\Shop::class)->getActiveDefault();
		}
	
		$this->config = $configReader->getByPluginName($pluginName, $shop);
		
		$this->helperComponent = $helperComponent;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatch'
        );
    }
	
	public function onPostDispatch(\Enlight_Event_EventArgs $args)
	{
		if (!$this->config['active'])
			return;
		
		/** @var \Enlight_Controller_Action $controller */
		$controller = $args->getSubject();
		$view = $controller->View();
		$request = $controller->Request();

		$controllerName = $view->Controller;
		$manufacturer = $view->manufacturer;
		
		$controllerName = strtolower($request->getControllerName());
		$manufacturer = $view->getAssign('manufacturer');
		
		if ($controllerName == 'listing' && $manufacturer) {
			
			$shop = Shopware()->Shop();
			$sCategoryStart = $shop->getCategory()->getId();
			
			$sCategoryContent = $view->sCategoryContent;
			
			$namespace = Shopware()->Snippets()->getNamespace('frontend/plugins/supplier_modified/index');
			$supplierStandardTitle = $namespace->get('SupplierMetaTitleStandard');

			$supplierStandardUrl = $this->helperComponent->getRewriteUrl($sCategoryStart);
			if ($manufacturer) {
				$breadcrumb = $view->sBreadcrumb;
				$breadcrumb[] = array(
					'link' => $supplierStandardUrl,
					'name' => $supplierStandardTitle
				);
				$breadcrumb[] = array(
					'link' => $manufacturer->getLink(),
					'name' => $sCategoryContent['title']
				);
				$view->sBreadcrumb = $breadcrumb;

				$sCategoryContent['productBoxLayout'] = $this->config['productBoxLayout'];
				$view->sCategoryContent = $sCategoryContent;
			}

			$supplierId = $request->getParam('sSupplier');
	
			$supplierModified['byChar'] 					= $this->helperComponent->getSupplierByChar($sCategoryStart, $supplierId);
			$supplierModified['banner'] 					= $this->helperComponent->getBannerFromSupplier($supplierId);
			$supplierModified['bannerPosition'] 			= $this->config['bannerPositionInSupplier'];
			$supplierModified['topsellerShow'] 				= $this->config['showTopsellerInSupplier'];
			$supplierModified['textPosition'] 				= $this->config['textPositionInSupplier'];
			$supplierModified['displayMode'] 				= $this->config['displayMode'];
			$supplierModified['displayFilter'] 				= $this->config['displayFilter'];
			$supplierModified['duration'] 					= $this->config['duration'];
			$supplierModified['stop'] 						= $this->config['stop'];
			$supplierModified['hideSidebarDesktop'] 		= $this->config['hideSidebarDesktop'];
			$supplierModified['hideSidebarSmartphone'] 		= $this->config['hideSidebarSmartphone'];
			$supplierModified['hideNavigationTitle'] 		= $this->config['hideNavigationTitle'];
			$supplierModified['showActiveFilter'] 			= $this->config['showActiveFilter'];
			$supplierModified['showArticleCountSidebar'] 	= $this->config['showArticleCountInSidebar'];

			$controller->View()->CbaxSupplierModified = $supplierModified;
		}
		
		if ($controllerName == 'detail') {
			$controller->View()->CbaxShowSupplierTab = $this->config['showSupplierTab'];
		}
	}
	
	/**
     * Check if a given version is greater or equal to
     * the currently installed shopware version.
     *
     * @return bool
     */
	protected function assertMinimumVersion($requiredVersion)
    {
        $version = Shopware()->Config()->version;

        if ($version === '___VERSION___') {
            return true;
        }

        return version_compare($version, $requiredVersion, '>=');
    }
}

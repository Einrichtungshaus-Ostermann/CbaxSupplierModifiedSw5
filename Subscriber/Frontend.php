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
		$this->config = $configReader->getByPluginName($pluginName, Shopware()->Shop());
		
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

				if (!$this->assertMinimumVersion('5.0.9') || $this->assertMinimumVersion('5.2.20')) {
					$breadcrumb[] = array(
						'link' => $sCategoryContent['sSelfCanonical'],
						'name' => $sCategoryContent['title']
					);
				}
				$view->sBreadcrumb = $breadcrumb;

				$sCategoryContent['productBoxLayout'] = $this->config['product_box_layout'];
				$view->sCategoryContent = $sCategoryContent;
			}

			$supplierId = $request->getParam('sSupplier');

			$supplierModified['byChar'] = $this->helperComponent->getSupplierByChar($sCategoryStart, $supplierId);
			$supplierModified['banner'] = $this->helperComponent->getBannerFromSupplier($supplierId);
			$supplierModified['bannerPosition'] = $this->config['bannerPositionInSupplier'];
			$supplierModified['topsellerShow'] = $this->config['showTopsellerInSupplier'];
			$supplierModified['supplierInfoPosition'] = $this->config['supplier_info_position'];
			$supplierModified['hideSidebar'] = $this->config['hideSidebar'];
			$supplierModified['hideNavigationTitle'] = $this->config['hide_navigation_title'];
			$supplierModified['showActiveFilter'] = $this->config['show_active_filter'];
			$supplierModified['showArticleCount'] = $this->config['show_article_count'];

			$controller->View()->CbaxSupplierModified = $supplierModified;
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

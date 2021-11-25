<?php

namespace CbaxSupplierModifiedSw5\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Plugin\ConfigReader;

class Widgets implements SubscriberInterface
{
	/**
     * @var
     */
    private $config;
	
	/**
     * Frontend constructor
     */
    public function __construct($pluginName, ConfigReader $configReader)
    {
		$this->config = $configReader->getByPluginName($pluginName, Shopware()->Shop());
    }

    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Controller_Action_PreDispatch_Widgets_Listing' => 'onPreDispatchListingWidgets'
        );
    }
	
	/**
	 * @param Enlight_Event_EventArgs $args
	 * @throws Exception
	 */
	public function onPreDispatchListingWidgets(\Enlight_Event_EventArgs $args)
	{
		if (!$this->config['active'])
			return;

		/** @var \Enlight_Controller_Action $controller */
		$controller = $args->getSubject();
		$request = $controller->Request();

		if ($request->getParam('sSupplier') !== $request->getParam('s')) {
			$request->setParam('productBoxLayout', $this->config['product_box_layout']);
		}
	}
}

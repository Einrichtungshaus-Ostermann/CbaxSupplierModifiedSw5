<?php

namespace CbaxSupplierModifiedSw5\Subscriber;

use Enlight\Event\SubscriberInterface;

class Backend implements SubscriberInterface
{
	/**
     * @var
     */
    private $pluginPath;

    /**
     * Backend constructor
     */
    public function __construct($pluginPath)
    {
		$this->pluginPath = $pluginPath;
    }

    public static function getSubscribedEvents()
    {
        return array(
			'Enlight_Controller_Action_PostDispatch_Backend_Supplier' => 'loadBackendModuleSupplier',
			'Shopware\Models\Article\Repository::getSupplierListQueryBuilder::after' => 'afterGetSupplierListQueryBuilder'
        );
    }
	
	public function loadBackendModuleSupplier(\Enlight_Event_EventArgs $args)
    {
        $request = $args->getRequest();
        $view = $args->getSubject()->View();

        $view->addTemplateDir($this->pluginPath . '/Resources/views/');

        // if the controller action name equals "load" we have to load all application components.
        if ($request->getActionName() === 'load') {
			
			// Shopware Version
			$view->isShopwareVersion52 = $this->assertMinimumVersion('5.2.0');

			if ($this->assertMinimumVersion('5.1.4')) {
				$view->extendsTemplate('backend/supplier/supplier_modified/view/list.js');
			}

			if (!$this->assertMinimumVersion('5.2.0')) {
				$view->extendsTemplate('backend/supplier/supplier_modified/model/attribute.js');
			}

			if ($this->assertMinimumVersion('5.2.0')) {
				$view->extendsTemplate('backend/supplier/supplier_modified/controller/main.js');
			}

			if (!$this->assertMinimumVersion('5.2.0') ||
				$this->assertMinimumVersion('5.2.19')) {
				$view->extendsTemplate('backend/supplier/supplier_modified/view/create.js');
			}

			$view->extendsTemplate('backend/supplier/supplier_modified/model/supplier.js');
			$view->extendsTemplate('backend/supplier/supplier_modified/view/edit.js');
        }
    }
	
	public function afterGetSupplierListQueryBuilder(\Enlight_Hook_HookArgs $args)
	{
		$builder = $args->getReturn();

		$builder->addSelect(array('attribute.cbaxSupplierIsPremium, attribute.id as attributeId'));
		$builder->leftJoin('supplier.attribute', 'attribute');

		$args->setReturn($builder);
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
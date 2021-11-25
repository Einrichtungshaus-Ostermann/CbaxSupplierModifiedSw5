<?php

namespace CbaxSupplierModifiedSw5;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
use CbaxSupplierModifiedSw5\Bootstrap\Updater;
use CbaxSupplierModifiedSw5\Bootstrap\Attributes;

/**
 * Shopware-Plugin CbaxSupplierModifiedSw5.
 */
class CbaxSupplierModifiedSw5 extends Plugin
{
	/**
     * {@inheritdoc}
     */
    public function install(InstallContext $context)
    {
		$attributes = new Attributes($this->container->get('shopware_attribute.crud_service'), $this->container->get('models'));
        $attributes->createAttributes();
		
        parent::install($context);
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(UninstallContext $context)
    {
		$attributes = new Attributes($this->container->get('shopware_attribute.crud_service'), $this->container->get('models'));
        $attributes->removeAttributes();
		
		if (!$context->keepUserData()) {
			return;
		}
		
        $context->scheduleClearCache(UninstallContext::CACHE_LIST_ALL);
    }

    /**
     * {@inheritdoc}
     */
    public function update(UpdateContext $context)
    {
		$attributes = new Attributes($this->container->get('shopware_attribute.crud_service'), $this->container->get('models'));
        $attributes->createAttributes();
		
        $updater = new Updater($this->getPath());

        $updater->update($context->getCurrentVersion());

        $context->scheduleClearCache(UpdateContext::CACHE_LIST_ALL);
    }
	
	/**
     * {@inheritdoc}
     */
    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(ActivateContext::CACHE_LIST_ALL);
    }

    /**
     * {@inheritdoc}
     */
    public function deactivate(DeactivateContext $context)
    {
        $context->scheduleClearCache(DeactivateContext::CACHE_LIST_ALL);
    }
}

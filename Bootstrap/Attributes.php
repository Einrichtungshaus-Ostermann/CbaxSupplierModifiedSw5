<?php

namespace CbaxSupplierModifiedSw5\Bootstrap;

use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware\Bundle\AttributeBundle\Service\TypeMapping;
use Shopware\Components\Model\ModelManager;

class Attributes
{
    /**
     * @var CrudService
     */
    private $crudService;

    /**
     * @var ModelManager
     */
    private $modelManager;

    /**
     * @param CrudService  $crudService
     * @param ModelManager $modelManager
     */
    public function __construct(CrudService $crudService, ModelManager $modelManager)
    {
        $this->crudService = $crudService;
        $this->modelManager = $modelManager;
    }

    public function createAttributes()
    {
        $this->crudService->update('s_articles_supplier_attributes', 'cbax_supplier_is_premium', TypeMapping::TYPE_INTEGER);
		$this->crudService->update('s_articles_supplier_attributes', 'cbax_supplier_banner', TypeMapping::TYPE_STRING);
		$this->crudService->update('s_articles_supplier_attributes', 'cbax_supplier_is_hidden', TypeMapping::TYPE_INTEGER);

        $this->modelManager->generateAttributeModels(['s_articles_supplier_attributes']);
    }

    public function removeAttributes()
    {
        $this->crudService->delete('s_articles_supplier_attributes', 'cbax_supplier_is_premium');
		$this->crudService->delete('s_articles_supplier_attributes', 'cbax_supplier_banner');
		$this->crudService->delete('s_articles_supplier_attributes', 'cbax_supplier_is_hidden');

        $this->modelManager->generateAttributeModels(['s_articles_supplier_attributes']);
    }
}
<?php

namespace CbaxSupplierModifiedSw5\Bootstrap;

class Updater
{
	/**
     * @var
     */
    private $pluginPath;
	
    /**
     * Updater constructor
     */
    public function __construct($pluginPath)
    {
		$this->pluginPath = $pluginPath;
    }
	
	public function update($oldVersion)
	{
		if (version_compare($oldVersion, '2.0.0', '<=')) {
            // Bla Bli Blu
        }
		
		$this->cleanUpFiles();
		
		return true;
	}
	
	private function cleanUpFiles()
	{
		if (file_exists($this->pluginPath . '/Resources/views/backend/supplier/supplier_modified/model/attribute.js')) {
            unlink ($this->pluginPath . '/Resources/views/backend/supplier/supplier_modified/model/attribute.js');
        }
	}
}

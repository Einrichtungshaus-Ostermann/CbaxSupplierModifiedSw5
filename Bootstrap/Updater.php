<?php

namespace CbaxSupplierModifiedSw5\Bootstrap;

class Updater
{
    /**
     * Updater constructor
     */
    public function __construct()
    {
		
    }
	
	public function update($oldVersion)
	{
		if (version_compare($oldVersion, '2.0.0', '<=')) {
            // Bla Bli Blu
        }
		
		return true;
	}
}

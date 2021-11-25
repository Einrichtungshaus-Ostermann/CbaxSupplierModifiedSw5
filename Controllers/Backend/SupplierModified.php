<?php
class Shopware_Controllers_Backend_SupplierModified extends Shopware_Controllers_Backend_ExtJs
{
	public function updatePremiumSupplierAction()
	{
		$request 	= $this->Request();
		$supplierID	= (int)$request->supplierID;
		$isPremium	= (trim($request->isPremium) == 'true') ? 1 : 0;

		if (isset($supplierID) && isset($isPremium)) {
			$select = "SELECT id FROM s_articles_supplier_attributes WHERE supplierID = ?";
			$id = Shopware()->Db()->fetchOne($select, array($supplierID));

			if ($id) {
				$update = "UPDATE s_articles_supplier_attributes SET cbax_supplier_is_premium = ? WHERE supplierID = ?";
				Shopware()->Db()->query($update, array($isPremium, $supplierID));
			} else {
				$insert = "INSERT INTO s_articles_supplier_attributes (id, supplierID, cbax_supplier_is_premium) VALUES (?,?,?)";
				Shopware()->Db()->query($insert, array(NULL, $supplierID, $isPremium));
			}
		}

		$this->View()->assign(array("success"=>true));
	}
}
?>
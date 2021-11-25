<?php

namespace CbaxSupplierModifiedSw5\Components;

use Shopware\Components\Plugin\ConfigReader;

class SupplierModifiedHelper
{
   /**
     * @var
     */
    private $config;

    public function __construct($pluginName, ConfigReader $configReader)
    {
		$this->config = $configReader->getByPluginName($pluginName, Shopware()->Shop());
    }

    /**
	 * @param $categorieId
	 * @param $supplierId
	 * @return mixed
	 * @throws Exception
	 */
	public function getSupplierByChar($categorieId, $supplierId)
	{
		$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		
		if (Shopware()->Config()->get('hideNoInstock'))
		{
			if ($this->assertMinimumVersion('5.4.0'))
			{
				$articleFilterSql = " AND (d.laststock * d.instock >= d.laststock * d.minpurchase)";
			}
			else
			{
				$articleFilterSql = " AND (a.laststock * d.instock >= a.laststock * d.minpurchase)";
			}
		}
		
		// Zuerst alle Hersteller, welche mit KEINEM Buchstaben beginnen
		$filterSql = "(name NOT LIKE '";
		$filterSql .= implode("%' AND name NOT LIKE '", $letters);
		$filterSql .= "%')";

		$sql = "
		SELECT
			s.*,
			(
			SELECT COUNT(DISTINCT a.id)
			FROM s_articles AS a 
			
			INNER JOIN s_articles_details AS d
            ON d.articleID = a.id
			
			INNER JOIN s_articles_categories_ro ac
			ON  ac.articleID = a.id
			AND ac.categoryID = ?
			
			INNER JOIN s_categories c
			ON  c.id = ac.categoryID
			AND c.active = 1
			
			LEFT JOIN s_articles_avoid_customergroups ag
			ON ag.articleID = a.id
			AND ag.customergroupID = ?
			
			WHERE a.supplierID = s.id AND a.active = 1
			$articleFilterSql
			) AS countArticle
			
		FROM s_articles_supplier AS s
		
		LEFT JOIN s_articles_supplier_attributes AS sa
		ON sa.supplierID=s.id
		
		WHERE $filterSql
		AND (sa.cbax_supplier_is_hidden IS NULL OR sa.cbax_supplier_is_hidden = '0')
		
		ORDER BY s.name";
		$suppliers = Shopware()->Db()->fetchAll($sql, array($categorieId, Shopware()->Shop()->getCustomerGroup()->getId()));

		$active = false;
		$countArticleTotal = 0;
		foreach ($suppliers as $supplierKey => $supplierValue) {
			if ($supplierId == $supplierValue["id"]) {
				$active = true;
			}

			$query = array(
				'controller' => 'listing',
				'action' => 'manufacturer',
				'sSupplier' => $supplierValue["id"]
			);
			$suppliers[$supplierKey]["link"] = Shopware()->Router()->assemble($query);

			if ($this->assertMinimumVersion('5.0.9')) {
				if ($supplierValue["img"]) {
					$mediaService = Shopware()->Container()->get('shopware_media.media_service');
					$suppliers[$supplierKey]["img"] = $mediaService->getUrl($supplierValue['img']);
				}
			}

			$countArticleTotal = $countArticleTotal + $supplierValue["countArticle"];
		}
		$result[0]['char'] = '#';
		$result[0]['active'] = $active;
		$result[0]['countSupplier'] = count($suppliers);
		$result[0]['countArticle'] = $countArticleTotal;
		$result[0]['items'] = $suppliers;

		// Jetzt alle Hersteller welche mit einem Buchstaben beginnen
		$attributes = $this->getSupplierAttributes();

		$counter = 0;
		foreach ($letters as $letter) {
			$counter++;
			$search = $letter . "%";
			$sql = "
			SELECT
				s.*,
				$attributes,
				(
				SELECT COUNT(DISTINCT a.id)
				FROM s_articles AS a 
				
				INNER JOIN s_articles_details AS d
				ON d.articleID = a.id
				
				INNER JOIN s_articles_categories_ro ac
                ON  ac.articleID = a.id
                AND ac.categoryID = ?
				
                INNER JOIN s_categories c
                ON  c.id = ac.categoryID
                AND c.active = 1
				
				LEFT JOIN s_articles_avoid_customergroups ag
				ON ag.articleID = a.id
				AND ag.customergroupID = ?
				
				WHERE a.supplierID = s.id AND a.active = 1
				$articleFilterSql
				) AS countArticle
			
			FROM s_articles_supplier AS s
				
			LEFT JOIN s_articles_supplier_attributes AS sa
			ON sa.supplierID=s.id
			
			WHERE s.name LIKE '$search'
			AND (sa.cbax_supplier_is_hidden IS NULL OR sa.cbax_supplier_is_hidden = '0')
			
			ORDER BY s.name";
			$suppliers = Shopware()->Db()->fetchAll($sql, array($categorieId, Shopware()->Shop()->getCustomerGroup()->getId()));

			$active = false;
			$countArticleTotal = 0;
			foreach ($suppliers as $supplierKey => $supplierValue) {
				if ($supplierId == $supplierValue["id"]) {
					$active = true;
				}

				$query = array(
					'controller' => 'listing',
					'action' => 'manufacturer',
					'sSupplier' => $supplierValue["id"]
				);
				$suppliers[$supplierKey]["link"] = Shopware()->Router()->assemble($query);

				if ($this->assertMinimumVersion('5.0.9')) {
					if ($supplierValue["img"]) {
						$mediaService = Shopware()->Container()->get('shopware_media.media_service');
						$suppliers[$supplierKey]["img"] = $mediaService->getUrl($supplierValue['img']);
					}
				}

				$countArticleTotal = $countArticleTotal + $supplierValue["countArticle"];
			}
			$result[$counter]['char'] = $letter;
			$result[$counter]['active'] = $active;
			$result[$counter]['countSupplier'] = count($suppliers);
			$result[$counter]['countArticle'] = $countArticleTotal;
			$result[$counter]['items'] = $suppliers;
		}

		return $result;
	}

	public function getBannerFromSupplier($supplierId)
	{
		$sql = "SELECT cbax_supplier_banner FROM s_articles_supplier_attributes WHERE supplierID = ?";
		$banner = Shopware()->Db()->fetchOne($sql, array($supplierId));

		return $banner;
	}

	private function getSupplierAttributes()
	{
		$sql = "
		SHOW COLUMNS
		
		FROM
			s_articles_supplier_attributes
			
		WHERE field != 'id'
		
		AND field != 'supplierID'
		";
		$columns = Shopware()->Db()->fetchAll($sql);

		foreach ($columns as $column) {
			$attributes[] = 'sa.' . $column['Field'];
		}

		$attributes = implode(",", $attributes);

		return $attributes;
	}

	public function getRewriteUrl($sCategory)
	{
		$orgPath = 'sViewport=SupplierModified';
		$sql = 'SELECT path FROM s_core_rewrite_urls WHERE org_path=? AND subshopID=? AND main=1 ORDER BY id DESC';
		$path = Shopware()->Db()->fetchOne($sql, array($orgPath, Shopware()->Shop()->getId()));
		if ($path) {
			return $path;
		} else {
			return Shopware()->Config()->BaseFile . '?' . $orgPath;
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

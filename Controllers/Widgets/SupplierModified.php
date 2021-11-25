<?php

class Shopware_Controllers_Widgets_SupplierModified extends Enlight_Controller_Action
{
	/**
     * @var
     */
	private $config;

	public function init()
	{
		$this->config = Shopware()->Container()->get('shopware.plugin.cached_config_reader')->getByPluginName('CbaxSupplierModifiedSw5', Shopware()->Shop());
	}

	/**
	 * @throws Zend_Db_Adapter_Exception
	 */
	public function supplierTopSellerAction()
	{
		if (!$this->config['active'])
		{
			return;
		}

		$supplierId = $this->Request()->getParam('sSupplierID');
		$categoryId = $this->Request()->getParam('sCategoryID');

		$min = (!empty($supplierId)) ? $this->config['minTopsellerInSupplier'] : $this->config['minTopsellerInOverview'];
		$max = (!empty($supplierId)) ? $this->config['maxTopsellerInSupplier'] : $this->config['maxTopsellerInOverview'];
		
		$values = $this->getSupplierTopSeller($supplierId, $categoryId, 0, $max, $min);

		$this->View()->loadTemplate("widgets/supplier_modified/top_seller.tpl");

		$this->View()->swVersionMin52 = $this->assertMinimumVersion('5.2.0');
		$this->View()->sCharts = $values["values"];
		$this->View()->perPage = $perPage;
		$this->View()->supplierName = $this->getSupplier($supplierId);
		$this->View()->topseller_show = $this->config['topseller_show'];
	}

	public function supplierPremiumAction()
	{
		if (!$this->config['active'])
		{
			return;
		}
		
		$categoryId = $this->Request()->getParam('sCategoryID');

		$values = $this->getSupplierPremium($categoryId);

		$this->View()->loadTemplate("widgets/supplier_modified/supplier_premium.tpl");

		$this->View()->swVersionMin52 = $this->assertMinimumVersion('5.2.0');
		$this->View()->sPremium = $values;
		$this->View()->topseller_show = $this->config['topseller_show'];
	}

	/**
	 * @param $supplierId
	 * @param $categoryId
	 * @param int $offset
	 * @param int $limit
	 * @param int $min
	 * @return array
	 * @throws Zend_Db_Adapter_Exception
	 */
	private function getSupplierTopSeller($supplierId, $categoryId, $offset = 0, $limit = 8, $min = 4)
	{
		$limit = empty($limit) ? 8 : (int)$limit;
		$min = empty($min) ? 4 : (int)$min;

		if (!empty($supplierId)) {
			$sql = "
            SELECT
              STRAIGHT_JOIN
              SQL_CALC_FOUND_ROWS

              a.id AS articleID,
              s.sales AS quantity

            FROM s_articles_top_seller_ro s
			
			INNER JOIN s_articles_categories_ro ac
              ON ac.articleID = s.article_id
              AND ac.categoryID = ?

            INNER JOIN s_categories c
              ON ac.categoryID = c.id
              AND c.active = 1
			
            INNER JOIN s_articles a
              ON a.id = s.article_id
			  AND a.supplierID = ?
              AND a.active = 1

            GROUP BY a.id
            ORDER BY quantity DESC
        ";

			$sql = Shopware()->Db()->limit($sql, $limit, $offset);
			$articles = Shopware()->Db()->fetchAll($sql, array($categoryId, $supplierId));

		} else {

			$sql = "
            SELECT
              STRAIGHT_JOIN
              SQL_CALC_FOUND_ROWS

              a.id AS articleID,
              s.sales AS quantity

            FROM s_articles_top_seller_ro s
			
            INNER JOIN s_articles a
              ON a.id = s.article_id
              AND a.active = 1
		
			LEFT JOIN s_articles_supplier AS sas
			ON a.supplierID=sas.id
		
			LEFT JOIN s_articles_supplier_attributes AS sasa
			ON sasa.supplierID=sas.id
			
			WHERE (sasa.cbax_supplier_is_hidden IS NULL OR sasa.cbax_supplier_is_hidden = '0')

            GROUP BY a.id
            ORDER BY quantity DESC
        ";

			$sql = Shopware()->Db()->limit($sql, $limit, $offset);
			$articles = Shopware()->Db()->fetchAll($sql);
		}

		$count = Shopware()->Db()->fetchOne("SELECT FOUND_ROWS()");
		if ($count < $min) return;
		$pages = round($count / $limit);

		if ($pages == 0 && $count > 0) {
			$pages = 1;
		}

		$values = array();

		foreach ($articles as &$article) {
			$articleId = $article["articleID"];

			$value = Shopware()->Modules()->Articles()->sGetPromotionById('fix', 0, $articleId, false);
			if (!$value) {
				continue;
			}
			$values[] = $value;
		}

		return array("values" => $values, "pages" => $pages);
	}

	/**
	 * @param $categoryId
	 * @return mixed
	 * @throws Exception
	 */
	public function getSupplierPremium($categoryId)
	{
		$attributes = $this->getSupplierAttributes();
		
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
                AND ac.categoryID = $categoryId
				
                INNER JOIN s_categories c
                ON  c.id = ac.categoryID
                AND c.active = 1
				
				LEFT JOIN s_articles_avoid_customergroups ag
				ON ag.articleID = a.id
				AND ag.customergroupID = " . Shopware()->System()->sUSERGROUPDATA["id"] . "
				
				WHERE a.supplierID = s.id AND a.active = 1
				$articleFilterSql
				) AS countArticle
			
			FROM 
				s_articles_supplier AS s
			LEFT JOIN
				s_articles_supplier_attributes AS sa
			ON 
	  			sa.supplierID=s.id
			WHERE 
				sa.cbax_supplier_is_premium = 1
			AND
				(sa.cbax_supplier_is_hidden IS NULL OR sa.cbax_supplier_is_hidden = '0')
			ORDER BY s.name";
		$suppliers = Shopware()->Db()->fetchAll($sql);

		$countArticleTotal = 0;
		foreach ($suppliers as $supplierKey => $supplierValue) {
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

			$countArticleTotal += $supplierValue["countArticle"];
		}

		$result['countSupplier'] = count($suppliers);
		$result['countArticle'] = $countArticleTotal;
		$result['items'] = $suppliers;

		return $result;
	}

	public function getSupplier($supplierId)
	{
		$sql = "SELECT name FROM s_articles_supplier WHERE id = ?";
		return Shopware()->Db()->fetchOne($sql, array($supplierId));
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

	/**
	 * Check if a given version is greater or equal to
	 * the currently installed shopware version.
	 *
	 * @return bool
	 */
	protected function assertMinimumVersion($requiredVersion)
	{
		if (Shopware::VERSION === '___VERSION___') {
			return true;
		}

		return version_compare(Shopware::VERSION, $requiredVersion, '>=');
	}
}

?>
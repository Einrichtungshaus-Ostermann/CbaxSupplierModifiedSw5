{extends file="parent:frontend/index/sidebar.tpl"}

{* Filter supplier *}
{block name="frontend_index_left_menu"}
	{if $CbaxSupplierModified.byChar|@count}
		<div class="sidebar--supplier-navigation campaign--box">
			{if !$CbaxSupplierModified.hideNavigationTitle}<div class="supplier-sites--headline navigation--headline">{s namespace="frontend/plugins/supplier_modified/index" name='SupplierMetaTitleStandard'}Marken von A-Z{/s}</div>{/if}
			<div class="supplier-sites--filter" data-supplier-activefilter="{$CbaxSupplierModified.showActiveFilter}">

				{foreach $CbaxSupplierModified.byChar as $supplier}
					{if $supplier.countSupplier && ($supplier.countArticle || $CbaxSupplierModified.displayFilter == 'showAll')}
						{include file="frontend/plugins/supplier_modified/facet-value-list.tpl"}
					{/if}
				{/foreach}
				
                {if $manufacturer}
                    <div class="button--container" style="clear:both;">
                        <a class="btn is--primary is--full is--center" title="{s namespace='frontend/plugins/supplier_modified/index' name='FilterLinkDefault'}Alle Anzeigen{/s}" href="{url controller='SupplierModified'}">
                            {s namespace='frontend/plugins/supplier_modified/index' name='FilterLinkDefault'}Alle Anzeigen{/s}
                        </a>
                    </div>
				{/if}
                
			</div>
		</div>
	{/if}
	{$smarty.block.parent}
{/block}
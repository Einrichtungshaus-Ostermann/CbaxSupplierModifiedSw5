{extends file="parent:frontend/index/sidebar.tpl"}

{namespace name="frontend/plugins/supplier_modified/sidebar"}

{* Filter supplier *}
{block name="frontend_index_left_menu"}

	{block name="frontend_index_sidebar_plugins_supplier_modified_sidebar"}
        {if !$CbaxSupplierModified.hideSidebarDesktop}
            {if $CbaxSupplierModified.byChar|@count}
                <div class="sidebar--supplier-navigation campaign--box">
                    {block name='frontend_index_sidebar_plugins_supplier_modified_sidebar_headline'}
                        {if !$CbaxSupplierModified.hideNavigationTitle}
                            <div class="supplier-sites--headline navigation--headline">{s name='SupplierMetaTitleStandard'}Marken von A-Z{/s}</div>
                        {/if}
                    {/block}
                    <div class="supplier-sites--filter" data-supplier-activefilter="{$CbaxSupplierModified.showActiveFilter}">
                        
                        {block name="frontend_index_sidebar_plugins_supplier_modified_sidebar_facets"}
                            {foreach $CbaxSupplierModified.byChar as $supplier}
                                {if $supplier.countSupplier && ($supplier.countArticle || $CbaxSupplierModified.displayFilter == 'showAll')}
                                    {include file="frontend/plugins/supplier_modified/facet-value-list.tpl"}
                                {/if}
                            {/foreach}
                        {/block}
                        
                        {block name='frontend_index_sidebar_plugins_supplier_modified_sidebar_show_all_button'}
                            {if $manufacturer}
                                <div class="button--container" style="clear:both;">
                                    <a class="btn is--primary is--full is--center" title="{s name='FilterLinkDefault'}Alle Anzeigen{/s}" href="{url controller='SupplierModified'}">
                                        {s name='FilterLinkDefault'}Alle Anzeigen{/s}
                                    </a>
                                </div>
                            {/if}
                        {/block}
                        
                    </div>
                </div>
            {/if}
        {/if}
    {/block}
    
	{$smarty.block.parent}
    
{/block}
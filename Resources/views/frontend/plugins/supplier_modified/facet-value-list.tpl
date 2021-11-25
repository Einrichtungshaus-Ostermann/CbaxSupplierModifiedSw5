{block name="frontend_plugins_supplier_modified_facet_value_list"}
    <div class="filter-panel filter--property facet--property"
         data-filter-type="value-list"
         data-field-name="property" style="width:99%">
         
		{block name="frontend_plugins_supplier_modified_facet_value_list_flyout"}
            <div class="filter-panel--flyout">
            
            	{block name="frontend_plugins_supplier_modified_facet_value_list_title"}
                    <label class="filter-panel--title">
                        {$supplier.char}
                    </label>
                {/block}
                
                {block name="frontend_plugins_supplier_modified_facet_value_list_icon"}
                	<span class="filter-panel--icon"></span>
                {/block}
                
                {block name="frontend_plugins_supplier_modified_facet_value_list_content"}
                    <div class="filter-panel--content">
                    	
                        {block name="frontend_plugins_supplier_modified_facet_value_list_list"}
                            <ul class="filter-panel--option-list">
                                {foreach $supplier.items as $item}
									
                                	{block name="frontend_plugins_supplier_modified_facet_value_list_option"}
                                        {if $item.countArticle || $CbaxSupplierModified.displayFilter == 'showAll'}
                                            
                                            {if $item.cbax_supplier_url}
                                                {$url = $item.cbax_supplier_url}
                                            {else}
                                                {$url = $item.link}
                                            {/if}
                                            
                                            <li class="filter-panel--option">
                                            	
                                                {block name="frontend_plugins_supplier_modified_facet_value_list_option_container"}
                                                    <div class="option--container">
                                                    
                                                    	{block name="frontend_plugins_supplier_modified_facet_value_list_input"}
                                                            <span class="filter-panel--checkbox">
                                                                <input type="checkbox"
                                                                       id="{$item.id}"
                                                                       name="{$item.id}"
                                                                       value="{$item.id}"
                                                                       onclick="window.location='{$url}'"
                                                                       {if $sCategoryContent.canonicalParams.sSupplier eq $item.id}checked="checked" {/if}/>
                                                                <span class="checkbox--state">&nbsp;</span>
                                                            </span>
                                                        {/block}
                                                        
                                                        {block name="frontend_plugins_supplier_modified_facet_value_list_label"}
                                                            <label class="filter-panel--label" onclick="window.location='{$url}'"
                                                                   for="{$item.id}">
                                                                {$item.name}{if $CbaxSupplierModified.showArticleCountSidebar} ({$item.countArticle}){/if}
                                                            </label>
                                                        {/block}
                                                    </div>
                                                {/block}
                                            </li>
                                        {/if}
                                    {/block}
                                {/foreach}
                            </ul>
                        {/block}
                    </div>
                {/block}
            </div>
        {/block}
    </div>
{/block}
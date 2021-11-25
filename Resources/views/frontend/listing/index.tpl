{extends file="parent:frontend/listing/index.tpl"}

{* Main content *}
{block name='frontend_index_content'}
    	
        {if $CbaxSupplierModified}
       
        	<div class="content listing--content">
                
                {* Banner *}
                {block name="frontend_listing_index_plugins_supplier_modified_banner"}
                    {if $CbaxSupplierModified.bannerPosition == 'insideContent'}
                        {include file="frontend/plugins/supplier_modified/banner.tpl"}
                    {/if}
                {/block}
                
                {* Sidebar *}
                {block name="frontend_listing_index_plugins_supplier_modified_sidebar"}
                    {if $CbaxSupplierModified}
                        {if !$CbaxSupplierModified.hideSidebarSmartphone}
                            {include file="frontend/plugins/supplier_modified/sidebar.tpl"}
                        {/if}
                    {/if}
                {/block}
                
                {* Topseller *}
                {block name="frontend_listing_index_plugins_supplier_modified_topseller"}
                    {if $manufacturer && $CbaxSupplierModified.topsellerShow}
                        {action module=widgets controller=SupplierModified action=supplierTopSeller sSupplierID=$ajaxCountUrlParams.sSupplier sSupplierName=$sCategoryContent.canonicalTitle sCategoryID=$ajaxCountUrlParams.sCategory}
                    {/if}
                {/block}
                
                {* Text above Listing*}
                {block name="frontend_listing_index_plugins_supplier_modified_text_above_listing"}
                    {if $CbaxSupplierModified.textPosition == 'aboveListing'}
                        {include file='frontend/listing/text.tpl'}
                    {/if}
                {/block}
                
                {* Define all necessary template variables for the listing *}
                {block name="frontend_listing_index_plugins_supplier_modified_layout_variables"}
        
                    {$emotionViewports = [0 => 'xl', 1 => 'l', 2 => 'm', 3 => 's', 4 => 'xs']}
        
                    {* Count of available product pages *}
                    {$pages = 1}
        
                    {if $criteria}
                        {$pages = ceil($sNumberArticles / $criteria->getLimit())}
                    {/if}
        
                    {* Layout for the product boxes *}
                    {$productBoxLayout = 'basic'}
        
                    {if $sCategoryContent.productBoxLayout !== null && $sCategoryContent.productBoxLayout !== 'extend'}
                        {$productBoxLayout = $sCategoryContent.productBoxLayout}
                    {/if}
                {/block}
                
                {* Listing *}
                {block name="frontend_listing_index_plugins_supplier_modified_listing"}
                    {include file='frontend/listing/listing.tpl'}
                {/block}
                
                {* Text below Listing*}
                {block name="frontend_listing_index_plugins_supplier_modified_text_below_listing"}
                    {if $CbaxSupplierModified.textPosition == 'belowListing'}
                        {include file='frontend/listing/text.tpl'}
                    {/if}
                {/block}
                
             </div>
             
        {else}
        
        	{$smarty.block.parent}

		{/if}
        
	
{/block}
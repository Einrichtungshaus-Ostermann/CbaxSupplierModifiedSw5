{extends file="parent:frontend/index/index.tpl"}

{namespace name="frontend/plugins/supplier_modified/index"}

{* Keywords *}
{block name="frontend_index_header_meta_keywords"}{s name='SupplierMetaKeywordsStandard'}Hersteller Keywords{/s}{/block}

{* Description *}
{block name="frontend_index_header_meta_description"}{s name='SupplierMetaDescriptionStandard'}Hersteller Description{/s}{/block}

{* Canonical link *}
{block name='frontend_index_header_canonical'}
	<link rel="canonical" href="{url controller='SupplierModified'}" title="{s name='SupplierMetaTitleStandard'}Marken von A-Z{/s}"/>
{/block}

{* Main content *}
{block name="frontend_index_content"}
	<div class="content listing--content">
		
        {* Banner *}
        {block name="frontend_plugins_supplier_modified_index_banner"}
            {if $CbaxSupplierModified.bannerPosition == 'insideContent'}
                {include file="frontend/plugins/supplier_modified/banner.tpl"}
            {/if}
        {/block}

		{* Filter supplier *}
        {block name="frontend_plugins_supplier_modified_index_sidebar"}
            {if !$CbaxSupplierModified.hideSidebarSmartphone}
                {include file="frontend/plugins/supplier_modified/sidebar.tpl"}
            {/if}
        {/block}

		{* Topseller *}
        {block name="frontend_plugins_supplier_modified_index_topseller"}
            {if $CbaxSupplierModified.topsellerShow}
                {action module=widgets controller=SupplierModified action=supplierTopSeller}
            {/if}
        {/block}
		
        {* Text Supplier *}
        {block name="frontend_plugins_supplier_modified_index_text_above_listing"}
            {if ($CbaxSupplierModified.headline || $CbaxSupplierModified.text) && !$sSupplierInfo.id && $CbaxSupplierModified.textPosition === 'aboveListing'}
                {include file="frontend/plugins/supplier_modified/text.tpl"}
            {/if}
        {/block}

		{* Premium Supplier *}
        {block name="frontend_plugins_supplier_modified_index_premium"}
			{action module=widgets controller=SupplierModified action=supplierPremium sCategoryID=$sCategoryCurrent}
        {/block}
		
        {* Navigation *}
        {block name="frontend_plugins_supplier_modified_index_navigation"}
			{include file="frontend/plugins/supplier_modified/navigation.tpl"}
        {/block}
		
        {block name="frontend_plugins_supplier_modified_index_layout_variables"}
            {if $CbaxSupplierModified.template eq "listing_2col"}
                {assign var="productBoxLayout" value="listing_2col"}
            {elseif $CbaxSupplierModified.template eq "listing_3col"}
                {assign var="productBoxLayout" value="listing_3col"}
            {elseif $CbaxSupplierModified.template eq "listing_4col"}
                {assign var="productBoxLayout" value="listing_4col"}
            {elseif $CbaxSupplierModified.template eq "slider"}
                {assign var="productBoxLayout" value="slider"}
            {else}
                {assign var="productBoxLayout" value="listing_4col"}
            {/if}
        {/block}
        
        {block name="frontend_plugins_supplier_modified_index_list"}
            {foreach $CbaxSupplierModified.byChar as $supplier}
                {if $supplier.countSupplier && ($supplier.countArticle || $CbaxSupplierModified.displayFilter == 'showAll')}
                
                    {if $supplier.char == '#'}
                        {$charID = '0'}
                    {else}
                        {$charID = $supplier.char}
                    {/if}
                    
                    {if $productBoxLayout == 'slider'}
                    	{block name="frontend_plugins_supplier_modified_index_list_slider"}
                            <div id="{$charID}" class="topseller panel has--border is--rounded supplier-group">
                            
                            	{block name="frontend_plugins_supplier_modified_index_list_slider_headline"}
                                    <div class="topseller--title panel--title is--underline">
                                        {$supplier.char}
                                    </div>
                                {/block}
                                {include file="frontend/plugins/supplier_modified/slider.tpl"}
                            </div>
                        {/block}
                    {else}
                    	{block name="frontend_plugins_supplier_modified_index_list_box"}
                            <div id="{$charID}" class="brand--row supplier-group{if $supplier@last} last{/if}">
                            	
                                {block name="frontend_plugins_supplier_modified_index_list_box_headline"}
                                    <div class="brand--column column-main">
                                        <h2 class="brand_char">{$supplier.char}</h2>
                                    </div>
                                {/block}
                                
                                {block name="frontend_plugins_supplier_modified_index_list_box_content"}
                                    <div class="brand--column column-list">
                                        {foreach $supplier.items as $item}
                                            {if $item.countArticle || $CbaxSupplierModified.displayFilter == 'showAll'}
                                                {include file="frontend/plugins/supplier_modified/box_supplier.tpl"}
                                            {/if}
                                        {/foreach}
                                    </div>
                                {/block}
                            </div>
                        {/block}
                    {/if}
                {/if}
            {/foreach}
        {/block}
		
        {* Text Supplier *}
        {block name="frontend_plugins_supplier_modified_index_text_below_listing"}
            {if ($CbaxSupplierModified.headline || $CbaxSupplierModified.text) && !$sSupplierInfo.id && $CbaxSupplierModified.textPosition === 'belowListing'}
                {include file="frontend/plugins/supplier_modified/text.tpl"}
            {/if}
        {/block}
	</div>
{/block}
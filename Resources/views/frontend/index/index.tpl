{extends file="parent:frontend/index/index.tpl"}

{block name="frontend_index_body_classes"}{strip}
	{$smarty.block.parent}
	{if $CbaxSupplierModified} is--suppliermodified{/if}
	{if $CbaxSupplierModified.hideSidebarDesktop} is--cbax-sm-no-sidebar{/if}
{/strip}{/block}

{* Content top container *}
{block name="frontend_index_content_top"}

	{block name="frontend_index_index_plugins_supplier_modified_banner"}
        {if $CbaxSupplierModified.bannerPosition == 'aboveContent'}
            {include file="frontend/plugins/supplier_modified/banner.tpl"}
        {/if}
    {/block}
    
	{$smarty.block.parent}
    
{/block}
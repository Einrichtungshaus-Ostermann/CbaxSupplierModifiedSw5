{extends file="parent:frontend/index/index.tpl"}

{block name="frontend_index_body_classes"}{strip}
	{$smarty.block.parent}
	{if $CbaxSupplierModified} is--suppliermodified{/if}
	{if $CbaxSupplierModified.hideSidebar} is--cbax-sm-no-sidebar{/if}
{/strip}{/block}

{* Breadcrumb *}
{block name='frontend_index_start'}
	{$smarty.block.parent}
	{if $CbaxSupplierModified.byChar|@count && !$manufacturer}
		{$sBreadcrumb[] = ['name'=>"Marken von A-Z", 'link'=>{url controller='SupplierModified'}]}
	{/if}
{/block}

{* Content top container *}
{block name="frontend_index_content_top"}
	{$smarty.block.parent}
	{if $CbaxSupplierModified.banner && $CbaxSupplierModified.bannerPosition == 'aboveContent'}
		<div class="banner--main-container">
			<img class="banner--img emotion--banner" alt="{$sCategoryContent.title}" src="{$CbaxSupplierModified.banner}">
		</div>
	{/if}
{/block}

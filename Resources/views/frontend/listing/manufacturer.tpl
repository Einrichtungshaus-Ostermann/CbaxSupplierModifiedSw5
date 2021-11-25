{extends file="parent:frontend/listing/manufacturer.tpl"}

{* Vendor headline *}
{block name="frontend_listing_list_filter_supplier_headline"}
	{if empty($sArticles)}
		<h1 class="panel--title is--underline">
			{$manufacturer->getName()|escapeHtml}
		</h1>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
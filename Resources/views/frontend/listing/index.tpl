{extends file="parent:frontend/listing/index.tpl"}

{* Off-Canvas *}
{block name="frontend_listing_index_text"}
	{if $CbaxSupplierModified}
		<div class="supplier--info panel">
			<a href="#" class="btn is--small is--full is--center" data-offcanvasselector=".action--supplier-options" data-offcanvas="true" data-closeButtonSelector=".supplier--close-btn">
				<i class="icon--numbered-list"></i> {s namespace='frontend/plugins/supplier_modified/index' name='SupplierMetaTitleStandard'}Marken von A-Z{/s}
			</a>
		</div>

		<div class="action--supplier-options buttons--off-canvas">
			<a href="#" class="supplier--close-btn">
				{s namespace='frontend/plugins/supplier_modified/index' name="ListingActionsCloseSupplier"}Menü schließen{/s} <i class="icon--arrow-right"></i>
			</a>

			<div class="sidebar--supplier-navigation">
				{if !$CbaxSupplierModified.hideNavigationTitle}<div class="supplier-sites--headline navigation--headline">{s namespace="frontend/plugins/supplier_modified/index" name='SupplierMetaTitleStandard'}Marken von A-Z{/s}</div>{/if}

				<div class="filter--container" style="padding:0 10px;">

					{foreach $CbaxSupplierModified.byChar as $supplier}
						{if $supplier.countSupplier && ($supplier.countArticle || $CbaxSupplierModified.displayFilter == 'showAll')}
							{include file="frontend/plugins/supplier_modified/facet-value-list.tpl"}
						{/if}
					{/foreach}

					<div class="button--container" style="clear:both;">
						<a class="btn is--primary is--full is--center" title="{s namespace='frontend/plugins/supplier_modified/index' name='FilterLinkDefault'}Alle Anzeigen{/s}" href="{url controller='SupplierModified'}">
							{s namespace='frontend/plugins/supplier_modified/index' name='FilterLinkDefault'}Alle Anzeigen{/s}
						</a>
					</div>

				</div>
			</div>
		</div>
	{/if}

	{if $manufacturer && $CbaxSupplierModified.topsellerShow}
		{action module=widgets controller=SupplierModified action=supplierTopSeller sSupplierID=$ajaxCountUrlParams.sSupplier sSupplierName=$sCategoryContent.canonicalTitle sCategoryID=$ajaxCountUrlParams.sCategory}
	{/if}
	{$smarty.block.parent}
{/block}

{* Banner *}
{block name="frontend_listing_index_banner"}
	{if $CbaxSupplierModified.banner && $CbaxSupplierModified.bannerPosition == 'insideContent'}
		<div class="banner--container">
			<img class="banner--img emotion--banner" alt="{$sCategoryContent.title}" src="{$CbaxSupplierModified.banner}">
		</div>
	{/if}
	{$smarty.block.parent}
{/block}

{* Herstellerinformation über dem Listing *}
{block name="frontend_listing_index_text"}
	{if !$CbaxSupplierModified || $CbaxSupplierModified.supplierInfoPosition == 'aboveListing'}
		{$smarty.block.parent}
	{/if}
{/block}

{* Herstellerinformation unter dem Listing *}
{block name="frontend_listing_index_listing"}
	{$smarty.block.parent}
	{if $CbaxSupplierModified.supplierInfoPosition == 'belowListing'}
		{if !$hasEmotion}
			{include file='frontend/listing/text.tpl'}
		{/if}
	{/if}
{/block}
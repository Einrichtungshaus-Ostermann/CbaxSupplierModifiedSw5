{extends file="parent:frontend/index/index.tpl"}

{* Keywords *}
{block name="frontend_index_header_meta_keywords"}{s namespace='frontend/plugins/supplier_modified/index' name='SupplierMetaKeywordsStandard'}Hersteller Keywords{/s}{/block}

{* Description *}
{block name="frontend_index_header_meta_description"}{s namespace='frontend/plugins/supplier_modified/index' name='SupplierMetaDescriptionStandard'}Hersteller Description{/s}{/block}

{* Canonical link *}
{block name='frontend_index_header_canonical'}
	<link rel="canonical" href="{url controller='SupplierModified'}"
		  title="{s namespace='frontend/plugins/supplier_modified/index' name='SupplierMetaTitleStandard'}Marken von A-Z{/s}"/>
{/block}

{* Main content *}
{block name="frontend_index_content"}
	<div class="content listing--content">

		{if $CbaxSupplierModified.banner && $CbaxSupplierModified.bannerPosition == 'insideContent'}
			<div class="banner--container">
				<img class="banner--img emotion--banner"
					 alt="{s namespace='frontend/plugins/supplier_modified/index' name='SupplierBannerAltTag'}Banner{/s}"
					 src="{$CbaxSupplierModified.banner}">
			</div>
		{/if}

		{* Filter supplier *}
		<div class="supplier--info panel">
			<a href="#" class="btn is--small is--full is--center" data-offcanvasselector=".action--supplier-options"
			   data-offcanvas="true" data-closeButtonSelector=".supplier--close-btn">
				<i class="icon--numbered-list"></i> {s namespace='frontend/plugins/supplier_modified/index' name='SupplierMetaTitleStandard'}Marken von A-Z{/s}
			</a>
		</div>

		<div class="action--supplier-options buttons--off-canvas">
			<a href="#" class="supplier--close-btn">
				{s namespace='frontend/plugins/supplier_modified/index' name="ListingActionsCloseSupplier"}Menü schließen{/s}
				<i class="icon--arrow-right"></i>
			</a>
			<div class="sidebar--supplier-navigation">
				{if !$CbaxSupplierModified.hideNavigationTitle}
					<div class="supplier-sites--headline navigation--headline">{s namespace="frontend/plugins/supplier_modified/index" name='SupplierMetaTitleStandard'}Marken von A-Z{/s}</div>
				{/if}
				<div class="filter--container" style="padding:0 10px;">
					{foreach $CbaxSupplierModified.byChar as $supplier}
						{if $supplier.countSupplier && ($supplier.countArticle || $CbaxSupplierModified.displayFilter == 'showAll')}
							{include file="frontend/plugins/supplier_modified/facet-value-list.tpl"}
						{/if}
					{/foreach}
				</div>
			</div>
		</div>

		{* Topseller *}
		{if $CbaxSupplierModified.topsellerShow}
			{action module=widgets controller=SupplierModified action=supplierTopSeller}
		{/if}

		{if ($CbaxSupplierModified.headline || $CbaxSupplierModified.text) && !$sSupplierInfo.id && $CbaxSupplierModified.textPosition === 'aboveListing'}
			{include file="frontend/plugins/supplier_modified/text.tpl"}
		{/if}

		{* Premium Supplier *}
		{action module=widgets controller=SupplierModified action=supplierPremium sCategoryID=$sCategoryCurrent}

		<div id="brand_index" class="panel" data-supplier-duration="{$CbaxSupplierModified.duration}"
			 data-supplier-stop="{$CbaxSupplierModified.stop}">
			<div class="panel--body">
				{foreach $CbaxSupplierModified.byChar as $supplier}
					{if $supplier.countSupplier && ($supplier.countArticle || $CbaxSupplierModified.displayFilter == 'showAll')}
						<button class="btn is--secondary"
								data-supplier-href="#{if $supplier.char == '#'}0{else}{$supplier.char}{/if}">{$supplier.char}</button>
					{else}
						<button class="btn is--disabled">{$supplier.char}</button>
					{/if}
				{/foreach}
			</div>
		</div>

		{foreach $CbaxSupplierModified.byChar as $supplier}
			{if $supplier.countSupplier && ($supplier.countArticle || $CbaxSupplierModified.displayFilter == 'showAll')}
				<div class="brand--row{if $supplier@last} last{/if}">
					<div class="brand--column column-main">
						<h2 id="{if $supplier.char == '#'}0{else}{$supplier.char}{/if}"
							class="brand_char">{$supplier.char}</h2>
					</div>
					<div class="brand--column column-list">
						{strip}
							{foreach $supplier.items as $item}
								{if $item.countArticle || $CbaxSupplierModified.displayFilter == 'showAll'}
									<div class="brand--item{$CbaxSupplierModified.brandItemCls}">
										<a class="brand--link" href="{$item.link}">
											{if $CbaxSupplierModified.displayMode !== 'showName'}
												<img class="brand--logo"
													 src="{if $item.img}{$item.img}{else}{link file='responsive/frontend/_public/src/img/no_picture.jpg'}{/if}"
													 alt="{$item.name}"{if !$item.img} title="{$item.name}"{/if}/>
											{/if}
											{if $CbaxSupplierModified.displayMode !== 'showLogo'}
												<span class="brand--label{if $item.cbax_supplier_is_premium} premium{/if}">{$item.name}</span>
											{/if}
										</a>
									</div>
								{/if}
							{/foreach}
						{/strip}
					</div>
				</div>
			{/if}
		{/foreach}

		{if ($CbaxSupplierModified.headline || $CbaxSupplierModified.text) && !$sSupplierInfo.id && $CbaxSupplierModified.textPosition === 'belowListing'}
			{include file="frontend/plugins/supplier_modified/text.tpl"}
		{/if}
	</div>
{/block}
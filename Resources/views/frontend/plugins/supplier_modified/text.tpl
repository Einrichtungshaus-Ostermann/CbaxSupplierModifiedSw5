<div class="hero-unit category--teaser panel has--border is--rounded">

	{* Headline *}
	{if $CbaxSupplierModified.headline}
		<h1 class="panel--title{if $CbaxSupplierModified.text} is--underline{/if}"{if !$CbaxSupplierModified.text} style="padding-bottom: 1.25rem;"{/if}>{$CbaxSupplierModified.headline}</h1>
	{/if}

	{* Category text *}
	{if $CbaxSupplierModified.text}
		<div class="hero--text panel--body is--wide">

			{* Long description *}
			<div class="teaser--text-long">
				{$CbaxSupplierModified.text}
			</div>

			{* Short description *}
			<div class="teaser--text-short is--hidden">
				{$CbaxSupplierModified.text|strip_tags|truncate:200}
				<a href="#"
				   title="{"{s namespace="frontend/listing/listing" name="ListingActionsOpenOffCanvas"}{/s}"|escape}"
				   class="text--offcanvas-link">
					{s namespace="frontend/listing/listing" name="ListingActionsOpenOffCanvas"}{/s} &raquo;
				</a>
			</div>

			{* Off Canvas Container *}
			<div class="teaser--text-offcanvas is--hidden">

				{* Close Button *}
				<a href="#"
				   title="{"{s namespace="frontend/listing/listing" name="ListingActionsCloseOffCanvas"}{/s}"|escape}"
				   class="close--off-canvas">
					<i class="icon--arrow-left"></i> {s namespace="frontend/listing/listing" name="ListingActionsCloseOffCanvas"}{/s}
				</a>
				{* Off Canvas Content *}
				<div class="offcanvas--content">
					<div class="content--title">{$CbaxSupplierModified.headline}</div>
					{$CbaxSupplierModified.text}
				</div>
			</div>
		</div>
	{/if}
</div>
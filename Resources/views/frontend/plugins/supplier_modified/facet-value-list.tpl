<div class="filter-panel filter--property facet--property"
	 data-filter-type="value-list"
	 data-field-name="property" style="width:99%">
	<div class="filter-panel--flyout">
		<label class="filter-panel--title">
			{$supplier.char}
		</label>
		<span class="filter-panel--icon"></span>
		<div class="filter-panel--content">
			<ul class="filter-panel--option-list">
				{foreach $supplier.items as $item}
					{if $item.countArticle || $CbaxSupplierModified.displayFilter == 'showAll'}
						<li class="filter-panel--option">
							<div class="option--container">
								<span class="filter-panel--checkbox">
									<input type="checkbox"
										   id="{$item.id}"
										   name="{$item.id}"
										   value="{$item.id}"
										   onclick="window.location='{$item.link}'"
										   {if $sCategoryContent.canonicalParams.sSupplier eq $item.id}checked="checked" {/if}/>
									<span class="checkbox--state">&nbsp;</span>
								</span>
								<label class="filter-panel--label" onclick="window.location='{$item.link}'"
									   for="{$item.id}">
									{$item.name}{if $CbaxSupplierModified.showArticleCount} ({$item.countArticle}){/if}
								</label>
							</div>
						</li>
					{/if}
				{/foreach}
			</ul>
		</div>
	</div>
</div>

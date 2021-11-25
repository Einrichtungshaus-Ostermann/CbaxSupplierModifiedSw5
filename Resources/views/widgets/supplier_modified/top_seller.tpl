{if $sCharts|@count}
    {if $swVersionMin52}
        <div class="topseller panel has--border is--rounded">
            <div class="topseller--title panel--title is--underline">
				{if $supplierName}
					{s namespace="frontend/plugins/supplier_modified/index" name="TopsellerHeading"}Topseller von{/s} {$supplierName}
                {else}
					{s namespace="frontend/plugins/supplier_modified/index" name="TopsellerHeadingOverview"}Topseller{/s}
				{/if}
            </div>
            {include file="frontend/_includes/product_slider.tpl" articles=$sCharts}
        </div>
    {else}
        <div class="topseller panel has--border is--rounded">
            <div class="topseller--title panel--title is--underline">
				{if $supplierName}
					{s namespace="frontend/plugins/supplier_modified/index" name="TopsellerHeading"}Topseller von{/s} {$supplierName}
				{else}
					{s namespace="frontend/plugins/supplier_modified/index" name="TopsellerHeadingOverview"}Topseller{/s}
				{/if}
            </div>
            <div class="topseller--content panel--body product-slider" data-topseller-slider="true">
                <div class="product-slider--container">
                    {foreach from=$sCharts item=article}
                        <div class="product-slider--item">
                            {include file="frontend/listing/box_article.tpl" sArticle=$article productBoxLayout="slider" productSliderCls="topseller--content panel--body"}
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
    {/if}
{/if}
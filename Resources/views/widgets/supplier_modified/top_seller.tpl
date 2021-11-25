{namespace name="widgets/supplier_modified/top_seller"}

{block name="widgets_plugins_supplier_modified_top_seller"}
    {if $sCharts|@count}
        <div class="topseller panel has--border is--rounded">
        	{block name="widgets_plugins_supplier_modified_top_seller_headline"}
                <div class="topseller--title panel--title is--underline">
                    {if $supplierName}
                        {s name="TopsellerHeading"}Topseller von{/s} {$supplierName}
                    {else}
                        {s name="TopsellerHeadingOverview"}Topseller{/s}
                    {/if}
                </div>
            {/block}
            {include file="frontend/_includes/product_slider.tpl" articles=$sCharts}
        </div>
    {/if}
{/block}
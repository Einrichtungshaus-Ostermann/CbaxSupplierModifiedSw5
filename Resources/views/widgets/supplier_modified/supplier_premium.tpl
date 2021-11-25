{namespace name="widgets/supplier_modified/supplier_premium"}

{block name="widgets_plugins_supplier_modified_supplier_premium"}
    {if $sPremium.countSupplier && ($sPremium.countArticle || $CbaxSupplierModified.displayFilter == 'showAll')}
        <div class="topseller panel has--border is--rounded">
        	{block name="widgets_plugins_supplier_modified_supplier_premium_headline"}
                <div class="topseller--title panel--title is--underline">
                    {s name='PreniumsellerHeading'}Top Marken{/s}
                </div>
            {/block}
            {include file="frontend/plugins/supplier_modified/slider.tpl" supplier=$sPremium  tpl="premium"}
        </div>
    {/if}
{/block}
{if $sPremium.countSupplier && ($sPremium.countArticle || $CbaxSupplierModified.displayFilter == 'showAll')}
	{if $swVersionMin52}
        <div class="topseller panel has--border is--rounded">
            <div class="topseller--title panel--title is--underline">
                {s namespace='frontend/plugins/supplier_modified/index' name='grid_premium'}Top Marken{/s}
            </div>
            <div class="product-slider topseller--content panel--body" data-product-slider="true">
                <div class="product-slider--container brand-premium--container">
                    {foreach $sPremium.items as $premium}
                        {if $premium.countArticle || $CbaxSupplierModified.displayFilter == 'showAll'}
                            <div class="product-slider--item brand-premium--item">
								{if $CbaxSupplierModified.displayMode !== 'showName'}
                                    <a class="brand-premium--link" href="{$premium.link}">
                                        <img class="brand-premium--img" src="{if $premium.img}{$premium.img}{else}{link file='responsive/frontend/_public/src/img/no_picture.jpg'}{/if}"
                                             alt="{$premium.name}"{if !$premium.img} title="{$premium.name}"{/if}/>
                                    </a>
								{/if}
								{if $CbaxSupplierModified.displayMode !== 'showLogo'}
                                    <span class="brand-premium--text">{$premium.name}</span>
								{/if}
                            </div>
                        {/if}
                    {/foreach}
                </div>
            </div>
        </div>
    {else}
        <div class="topseller panel has--border is--rounded">
            <div class="topseller--title panel--title is--underline">
                {s namespace='frontend/plugins/supplier_modified/index' name='grid_premium'}Top Marken{/s}
            </div>
            <div class="topseller--content panel--body product-slider" data-topseller-slider="true">
                <div class="product-slider--container brand-premium--container">
                    {foreach $sPremium.items as $premium}
                        {if $premium.countArticle || $CbaxSupplierModified.displayFilter == 'showAll'}
                            <div class="product-slider--item brand-premium--item">
								{if $CbaxSupplierModified.displayMode !== 'showName'}
                                    <a class="brand-premium--link" href="{$premium.link}">
                                        <img class="brand-premium--img" src="{if $premium.img}{$premium.img}{else}{link file='responsive/frontend/_public/src/img/no_picture.jpg'}{/if}"
                                             alt="{$premium.name}"{if !$premium.img} title="{$premium.name}"{/if}/>
                                    </a>
								{/if}
								{if $CbaxSupplierModified.displayMode !== 'showLogo'}
                                    <span class="brand-premium--text">{$premium.name}</span>
								{/if}
                            </div>
                        {/if}
                    {/foreach}
                </div>
            </div>
        </div>
    {/if}
{/if}
{block name='frontend_plugins_supplier_modified_text'}
    <div class="hero-unit category--teaser panel has--border is--rounded">
    	
        {* Headline *}
    	{block name='frontend_plugins_supplier_modified_text_headline'}
            {if $CbaxSupplierModified.headline}
                <h1 class="panel--title{if $CbaxSupplierModified.text} is--underline{/if}"{if !$CbaxSupplierModified.text} style="padding-bottom: 1.25rem;"{/if}>{$CbaxSupplierModified.headline}</h1>
            {/if}
        {/block}
    
        {* Category text *}
        {block name="frontend_plugins_supplier_modified_text_content"}
            {if $CbaxSupplierModified.text}
                <div class="hero--text panel--body is--wide">
                    
                    {* Long description *}
                    {block name='frontend_plugins_supplier_modified_text_description'}
                        <div class="teaser--text-long">
                            {$CbaxSupplierModified.text}
                        </div>
                    {/block}
                    
                    {* Short description *}
                    {block name='frontend_plugins_supplier_modified_text_short_description'}
                        <div class="teaser--text-short is--hidden">
                            {$CbaxSupplierModified.text|strip_tags|truncate:200}
                            <a href="#"
                               title="{"{s namespace="frontend/listing/listing" name="ListingActionsOpenOffCanvas"}{/s}"|escape}"
                               class="text--offcanvas-link">
                                {s namespace="frontend/listing/listing" name="ListingActionsOpenOffCanvas"}{/s} &raquo;
                            </a>
                        </div>
                    {/block}
                    
                    {* Off Canvas Container *}
                    {block name="frontend_plugins_supplier_modified_text_content_offcanvas"}
                        <div class="teaser--text-offcanvas is--hidden">
            
                            {* Close Button *}
                            {block name="frontend_plugins_supplier_modified_text_content_offcanvas_close"}
                                <a href="#"
                                   title="{"{s namespace="frontend/listing/listing" name="ListingActionsCloseOffCanvas"}{/s}"|escape}"
                                   class="close--off-canvas">
                                    <i class="icon--arrow-left"></i> {s namespace="frontend/listing/listing" name="ListingActionsCloseOffCanvas"}{/s}
                                </a>
                            {/block}
                            
                            {* Off Canvas Content *}
                            {block name="frontend_plugins_supplier_modified_text_content_offcanvas_content"}
                                <div class="offcanvas--content">
                                    <div class="content--title">{$CbaxSupplierModified.headline}</div>
                                    {$CbaxSupplierModified.text}
                                </div>
                            {/block}
                        </div>
                    {/block}
                </div>
            {/if}
        {/block}
    </div>
{/block}
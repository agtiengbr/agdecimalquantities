<div id="order-items-agdecimalquantities" class="col-md-12">
  <div class="agdecimalquantities-table order-confirmation-table">
    {foreach from=$order.products item=product}
      <div class="order-line row">
        <div class="col-sm-2 col-xs-3">
          <span class="image">
            {if !empty($product.default_image)}
              <img src="{$product.default_image.medium.url}" loading="lazy" />
            {else}
              <img src="{$urls.no_picture_image.bySize.medium_default.url}" loading="lazy" />
            {/if}
          </span>
        </div>
        <div class="col-sm-4 col-xs-9 details">
          <span>{$product.name}</span>
          {if is_array($product.customizations) && $product.customizations|count}
            {foreach from=$product.customizations item="customization"}
              <div class="customizations">
                <a href="#" data-toggle="modal" data-target="#product-customizations-modal-{$customization.id_customization}">{l s='Product customization' d='Shop.Theme.Catalog'}</a>
              </div>
              <div class="modal fade customization-modal" id="product-customizations-modal-{$customization.id_customization}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' d='Shop.Theme.Global'}">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <h4 class="modal-title">{l s='Product customization' d='Shop.Theme.Catalog'}</h4>
                    </div>
                    <div class="modal-body">
                      {foreach from=$customization.fields item="field"}
                        <div class="product-customization-line row">
                          <div class="col-sm-3 col-xs-4 label">
                            {$field.label}
                          </div>
                          <div class="col-sm-9 col-xs-8 value">
                            {if $field.type == 'text'}
                              {if (int)$field.id_module}
                                {$field.text nofilter}
                              {else}
                                {$field.text}
                              {/if}
                            {elseif $field.type == 'image'}
                              <img src="{$field.image.small.url}" loading="lazy">
                            {/if}
                          </div>
                        </div>
                      {/foreach}
                    </div>
                  </div>
                </div>
              </div>
            {/foreach}
          {/if}
          {hook h='displayProductPriceBlock' product=$product type="unit_price"}
        </div>
        <div class="col-sm-6 col-xs-12 qty">
          <div class="row">
            <div class="col-xs-4 text-sm-center text-xs-left">{if isset($product.price_to_display)}{$product.price_to_display nofilter}{else if $product.unit_price_full}{$product.unit_price_full}{else}{$product.price}{/if}</div>
            <div class="col-xs-4 text-sm-center">
              {if $product.decimal_quantity > 0}
                {$product.decimal_quantity} {$product.unit}
              {else}
                {$product.quantity}
              {/if}
            </div>
            <div class="col-xs-4 text-sm-center text-xs-right bold">{$product.total}</div>
          </div>
        </div>
      </div>
    {/foreach}
  </div>
</div>

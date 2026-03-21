{if $page.page_name == 'cart' && isset($unit) && $unit}
    <div class="product-actions agdecimalquantities fractional-quantity">
        <!-- Campo customizado -->
        <div class="cart_custom_quantity_row">
            <div class="input-group bootstrap-touchspin has-qty-text agdecimalquantities-input-group">
                <div class="d-flex">
                    <input type="number" name="custom_quantity" class="form-control fractional-quantity-input" data-product-id="{$product_id}" data-fraction="{$fraction}" step="{$fraction}" data-minimal-quantity="{$minimal_quantity}" value="{$fractional_quantity}" min="{$minimal_quantity}"  required>
                    <span class="input-group-btn-vertical">
                        <button class="btn btn-touchspin js-touchspin bootstrap-touchspin-up h-100" type="button">
                            <i class="material-icons" aria-hidden="true">arrow_drop_up</i>
                        </button>
                        <button class="btn btn-touchspin js-touchspin bootstrap-touchspin-down h-100" type="button">
                            <i class="material-icons" aria-hidden="true">arrow_drop_down</i>
                        </button>
                    </span>
                </div>
                <span class="input-group-addon agdecimalquantities_unit">{$unit}</span>
            </div>
        </div>
    </div>
{else if $page.page_name == 'checkout' && isset($unit) && $unit}
    <div class="agdecimalquantities fractional-quantity">
        {$fractional_quantity} {$unit}
    </div>
{/if}
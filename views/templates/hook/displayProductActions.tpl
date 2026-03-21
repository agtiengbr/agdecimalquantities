{if isset($unit) && $unit}
    <div class="product-actions agdecimalquantities">
        <!-- Campo customizado -->
        <label for="custom_quantity">Quantidade Customizada:</label>
        <div class="add_to_cart_custom_quantity_row">
            <div class="input-group bootstrap-touchspin has-qty-text">
                <input type="number" id="custom_quantity" name="custom_quantity" class="form-control" min="{$min_quantity}" step="{$step_quantity}" value="{$min_quantity}" required>
                <span class="input-group-btn-vertical">
                    <button class="btn btn-touchspin js-touchspin bootstrap-touchspin-up h-100" type="button">
                        <i class="material-icons" aria-hidden="true">arrow_drop_up</i>
                    </button>
                    <button class="btn btn-touchspin js-touchspin bootstrap-touchspin-down h-100" type="button">
                        <i class="material-icons" aria-hidden="true">arrow_drop_down</i>
                    </button>
                </span>
                <span class="input-group-addon agdecimalquantities_unit">{$unit}</span>
            </div>
            <button class="btn btn-primary add-to-cart" data-button-action="add-to-cart" type="submit">
                <i class="material-icons keyboard-arrow-up"></i>
                Adicionar
            </button>
        </div>
    </div>
{/if}
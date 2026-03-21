$(document).ready(function() {
    $(document).on('change', '.fractional-quantity-input', function() {
        var productId = $(this).data('product-id');
        var fraction = parseFloat($(this).data('fraction'));
        var minimalQuantity = parseFloat($(this).data('minimal-quantity'));
        var fractionalQuantity = parseFloat($(this).val());

        if (fractionalQuantity < minimalQuantity) {
            fractionalQuantity = minimalQuantity;
            $(this).val(fractionalQuantity);
        }

        var prestashopQuantity = fractionalQuantity / fraction;
        var quantityInput = $('input[data-product-id="' + productId + '"]:not(.fractional-quantity-input)');

        if (quantityInput.length) {
            quantityInput.val(prestashopQuantity);
            quantityInput.focus(); // Ensure focus
            quantityInput.triggerHandler('blur'); // Try jQuery's event handler method
            quantityInput.trigger('focusout'); // Sometimes PrestaShop listens to focusout instead
        }
    });
});

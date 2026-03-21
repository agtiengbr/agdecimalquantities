$(document).ready(function() {
    $('.js-subtotal').text('Subtotal');
});

// Event delegation for dynamically replaced elements
$(document).on('click', '.bootstrap-touchspin-up', function() {
    var $input = $(this).closest('.cart_custom_quantity_row').find('.fractional-quantity-input');
    var productId = $input.data('product-id');
    var fraction = $input.data('fraction');
    var value = parseFloat($input.val()) || 0;
    var min = parseFloat($input.attr('min')) || 0;
    var newValue = value + fraction;

    if (newValue >= min) {
        $input.val(newValue);
        updateCart(productId, 'up', $(this)); // Increase quantity
    }
});

$(document).on('click', '.bootstrap-touchspin-down', function() {
    var $input = $(this).closest('.cart_custom_quantity_row').find('.fractional-quantity-input');
    var productId = $input.data('product-id');
    var fraction = $input.data('fraction');
    var value = parseFloat($input.val()) || 0;
    var min = parseFloat($input.attr('min')) || 0;
    var newValue = value - fraction;

    if (newValue >= min) {
        $input.val(newValue);
        updateCart(productId, 'down', $(this)); // Decrease quantity
    }
});

function updateCart(productId, operation, element) {
    element.prop('disabled', true);
    element.siblings('.btn-touchspin').prop('disabled', true);
    element.parent().siblings('.fractional-quantity-input').prop('disabled', true);
    $.ajax({
        url: prestashop.urls.pages.cart + 
             '?update=1&id_product=' + productId + 
             '&id_product_attribute=0' + 
             '&op=' + operation + 
             '&token=' + prestashop.static_token,
        type: 'POST',
        data: {
            ajax: 1,
            action: 'update'
        },
        success: function(response) {
            prestashop.emit('updateCart', {resp: JSON.parse(response)});
        },
        error: function(error) {
            console.error('Cart update failed:', error);
        },
        complete: function() {
            $(element).prop('disabled', false);
            element.siblings('.btn-touchspin').prop('disabled', false);
            element.parent().siblings('.fractional-quantity-input').prop('disabled', false);
            addEventsToFractionInputs();
        }
    });
}
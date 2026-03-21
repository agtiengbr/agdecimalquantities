$(document).ready(function() {
    var $input = $('#custom_quantity');
    var step = parseFloat($input.attr('step')) || 1;
    var min = parseFloat($input.attr('min')) || 0;

    $('.bootstrap-touchspin-up').on('click', function() {
      var decimals = ($input.attr('step').split('.')[1] || '').length;
      var value = parseFloat($input.val()) || min;
      $input.val((value + step).toFixed(decimals)).trigger('change');
    });

    $('.bootstrap-touchspin-down').on('click', function() {
      var decimals = ($input.attr('step').split('.')[1] || '').length;
      var value = parseFloat($input.val()) || min;
      value = Math.max(value - step, min);
      $input.val(value.toFixed(decimals)).trigger('change');
    });

    $('#custom_quantity').on('change', function () {
      var customQuantity = parseFloat($(this).val());
      var stepQuantity = parseFloat($(this).attr('step'));
      if (!isNaN(customQuantity) && stepQuantity > 0) {
          $('#quantity_wanted').val(Math.round(customQuantity / stepQuantity));
      }
    });
});

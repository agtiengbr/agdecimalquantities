document.addEventListener("DOMContentLoaded", function() {
    var customProductListBody = document.querySelector("#agDecimalQuantitiesOrderDetailTable tbody");
    var vanillaProductListBody = document.querySelector("#order-products tbody");
    if (vanillaProductListBody && customProductListBody) {
        vanillaProductListBody.innerHTML = customProductListBody.innerHTML;
        customProductListBody.closest('table').remove();
    }
});

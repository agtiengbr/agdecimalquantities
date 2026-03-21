document.addEventListener("DOMContentLoaded", function() {
    var customProductListBody = document.querySelector("#agDecimalQuantitiesOrderProductsTable tbody");
    var vanillaProductListBody = document.querySelector("#orderProductsTable tbody");
    if (vanillaProductListBody && customProductListBody) {
        vanillaProductListBody.innerHTML = customProductListBody.innerHTML;
        customProductListBody.closest('table').remove();
    }

    var addProductBtn = document.querySelector("#addProductBtn");
    if (addProductBtn) {
        addProductBtn.remove();
    }
});

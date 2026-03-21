<table class="table" id="agDecimalQuantitiesOrderProductsTable" data-currency-precision="2">
  <tbody>
    {foreach from=$products item=product}
      <tr id="orderProduct_{$product['id_order_detail']}" class="cellProduct">
        <td class="cellProductImg">
        </td>
        <td class="cellProductName">
          <a href="/painel/index.php/sell/catalog/products/3?_token=C5I5r7SIcGD-YeUrM5KsAOQjDFPdwcv8XihvUXvgOeU">
            <p class="mb-0 productName">{$product.product_name}</p>
            <p class="mb-0 productReference">
              {l s='Reference Number'}: {$product.reference}
            </p>
          </a>
        </td>
        <td class="cellProductUnitPrice">
            {if $product.decimal_quantity}
                {Tools::displayPrice($product.price / ($product.decimal_quantity  / $product.product_quantity))} {$product.unity}
            {else}
                {Tools::displayPrice($product.price)}
            {/if}
        </td>
        <td class="cellProductQuantity text-center">
          <span class="badge badge-secondary">
            {if $product.decimal_quantity}
                {$product.decimal_quantity} {$product.unit}
            {else}
                {$product.product_quantity}
            {/if}
          </span>
        </td>
        <td class="cellProductLocation d-none"></td>
        <td class="cellProductRefunded d-none"></td>
        <td class="cellProductAvailableQuantity text-center">{$product.available_quantity} {$product.unit}</td>
        <td class="cellProductTotalPrice">{Tools::displayPrice($product.total_price)}</td>
        <td></td>
        <td class="text-right cellProductActions">
        </td>
        <td class="text-right cancel-product-element">
        </td>
      </tr>
    {/foreach}
  </tbody>
</table>
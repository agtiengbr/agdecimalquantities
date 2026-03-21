<table class="table table-bordered return" id="agDecimalQuantitiesOrderDetailTable" data-currency-precision="2">
  <thead class="thead-default">
    <tr>
      <th>{l s='Product' d='Shop.Theme.Catalog'}</th>
      <th>{l s='Quantity' d='Shop.Theme.Catalog'}</th>
      <th>{l s='Unit price' d='Shop.Theme.Catalog'}</th>
      <th>{l s='Total price' d='Shop.Theme.Catalog'}</th>
    </tr>
  </thead>
  <tbody>
    {foreach from=$products item=product}
      <tr id="orderProduct_{$product['id_order_detail']}" class="cellProduct">
        <td class="cellProductName">
          <a href="{$product.link}">
            <p class="mb-0 productName">{$product.product_name}</p>
            <p class="mb-0 productReference">
              {l s='Reference Number'}: {$product.reference}
            </p>
          </a>
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
        <td class="cellProductUnitPrice">
            {if $product.decimal_quantity}
                {Tools::displayPrice($product.price / ($product.decimal_quantity  / $product.product_quantity))} {$product.unity}
            {else}
                {Tools::displayPrice($product.price)}
            {/if}
        </td>
        <td class="cellProductTotalPrice">{Tools::displayPrice($product.total_price)}</td>
      </tr>
    {/foreach}
  </tbody>
</table>

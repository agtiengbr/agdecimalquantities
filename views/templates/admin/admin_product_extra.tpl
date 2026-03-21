<div class="row">
    <label class="col-lg-2 mb-0" for="template_select">Selecionar Template:</label>
    <select name="id_ag_decimal_quantities_template" id="template_select" class="form-control col-lg-3">
        {foreach from=$templates item=template}
            <option value="{$template.id_ag_decimal_quantities_template}" {if $template.id_ag_decimal_quantities_template == $selected_template}selected{/if}>
                {$template.name}
            </option>
        {/foreach}
    </select>
    <input type="hidden" name="id_product" value="{$id_product}"/>
    <button type="submit" name="saveTemplate" class="btn btn-primary ml-3">Salvar Template</button>
</div>
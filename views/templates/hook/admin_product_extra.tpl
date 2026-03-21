<div id="template_form" class="container row flex-column mx-1">
    <!-- Label with instruction -->
    <label class="mb-1 fw-bold d-flex align-items-center" for="template_select">
        Selecione um template para aplicar:
    </label>

    <div class="mt-2">
        <!-- Dropdown selection with spacing -->
        <select name="id_ag_decimal_quantities_template" id="template_select" class="form-control col-lg-4">
            {foreach from=$templates item=template}
                <option value="{$template.id_ag_decimal_quantities_template}" {if $template.id_ag_decimal_quantities_template == $selected_template}selected{/if}>
                    {$template.name}
                </option>
            {/foreach}
        </select>

        <!-- Hidden product ID input -->
        <input type="hidden" name="id_product" value="{$id_product}">

        <!-- Instructional text for save button -->
        <small class="text-muted d-block mt-1">
            Após selecionar um template, clique em "Salvar Template" para aplicar as configurações.
        </small>

        <!-- Save button with better spacing -->
        <button type="button" id="save_template_button" class="btn btn-primary mt-3">
            Salvar Template
        </button>
    </div>
</div>

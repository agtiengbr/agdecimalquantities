<!-- Modal -->
<div class="modal fade" id="deleteTemplateModal" tabindex="-1" role="dialog" aria-labelledby="deleteTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTemplateModalLabel">{l s='Delete Template' d='Modules.Agdecimalquantities.Admin'}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {l s='Are you sure you want to delete this template?' d='Modules.Agdecimalquantities.Admin'}
                <br>
                {l s='Optionally, select a new template to reassign the products:' d='Modules.Agdecimalquantities.Admin'}
                <select id="newTemplate" class="form-control">
                    <option value="">{l s='Do not reassign' d='Modules.Agdecimalquantities.Admin'}</option>
                    {foreach from=$templates item=template}
                        <option value="{$template.id_ag_decimal_quantities_template}">{$template.name}</option>
                    {/foreach}
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Cancel' d='Modules.Agdecimalquantities.Admin'}</button>
                <button type="button" id="confirmDelete" class="btn btn-danger">{l s='Delete' d='Modules.Agdecimalquantities.Admin'}</button>
            </div>
        </div>
    </div>
</div>
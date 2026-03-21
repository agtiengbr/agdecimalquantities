<div class="modal fade" id="importProductsModal" tabindex="-1" role="dialog" aria-labelledby="importProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importProductsModalLabel">Import Products</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="importProductsForm" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="importFile">CSV File</label>
                        <input type="file" class="form-control" id="importFile" name="importFile" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
            </div>
        </div>
    </div>
</div>
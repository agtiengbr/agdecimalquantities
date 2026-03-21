$(document).ready(function() {
    $('#deleteTemplateModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botão que acionou o modal
        var id = button.data('id'); // Extrai o ID do template dos dados do botão
        var href = button.data('href'); // Extrai o href do botão

        // Atualiza o link de confirmação no modal
        var modal = $(this);
        modal.find('#confirmDelete').data('id', id);
        modal.find('#confirmDelete').data('href', href);
    });

    $('#confirmDelete').click(function() {
        var id = $(this).data('id');
        var href = $(this).data('href');
        var newTemplate = $('#newTemplate').val();

        $.ajax({
            url: href,
            type: 'POST',
            data: {
                id_ag_decimal_quantities_template: id,
                new_template: newTemplate
            },
            success: function(response) {
                // Atualiza a lista de templates
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('An error occurred while deleting the template.');
            }
        });
    });

    $('#deleteTemplateForm').on('submit', function(event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function(response) {
                location.reload();
            }
        });
    });
});
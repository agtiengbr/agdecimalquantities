// ...existing code...

$(document).ready(function() {
    $('#save_template_button').on('click', function() {
        var templateId = $('#template_select').val();
        var productId = $('input[name="id_product"]').val();

        if (!templateId || !productId) {
            $.growl.error({ title: 'Erro', message: 'Por favor, selecione um template antes de salvar.' });
            return;
        }

        $.ajax({
            url: adminControllerLink,
            type: 'POST',
            data: {
                action: 'saveProductTemplate',
                id_product: productId,
                id_ag_decimal_quantities_template: templateId,
                ajax: true
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $.growl.notice({ title: 'Sucesso', message: 'Template salvo com sucesso.' });
                } else {
                    $.growl.error({ title: 'Erro', message: 'Erro ao salvar o template.' });
                }
            },
            error: function() {
                $.growl.error({ title: 'Erro', message: 'Erro de comunicação com o servidor.' });
            }
        });
    });
});


// Function to duplicate the input with name="qty_0"
function duplicateInput() {
    // Get the original input
    var originalInput = document.querySelector('input[name="form[step3][qty_0]"]');
    if (!originalInput) return;

    // Create the new input
    var newInput = document.createElement('input');
    newInput.type = 'number';
    newInput.name = 'form[step3][qty_decimal_0]';
    newInput.step = getProductStep(); // Fetch the step from the product configured template
    newInput.value = calculateFractionalStock(originalInput.value, getProductStep()); // Calculate the initial value based on the fractional parts
    newInput.setAttribute('data-unity', getProductUnity()); // Set the template unity

    // Create a span to display the unity
    var unitySpan = document.createElement('span');
    unitySpan.textContent = getProductUnity();
    unitySpan.style.marginLeft = '0.5rem';
    unitySpan.style.lineHeight = '2.188rem';

    // Add styles to the new input
    newInput.style.height = 'auto';
    newInput.style.minHeight = '2.188rem';
    newInput.style.display = 'inline-block';
    newInput.style.width = 'calc(100% - 3rem)';
    newInput.style.height = '2.188rem';
    newInput.style.padding = '.375rem .4375rem';
    newInput.style.fontSize = '.875rem';
    newInput.style.fontWeight = '400';
    newInput.style.lineHeight = '1.5';
    newInput.style.color = '#363a41';
    newInput.style.backgroundColor = '#fff';
    newInput.style.backgroundClip = 'padding-box';
    newInput.style.border = '1px solid #bbcdd2';
    newInput.style.borderRadius = '4px';
    newInput.style.boxShadow = 'none';
    newInput.style.transition = 'border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out';
    newInput.style.padding = '.5rem 1rem';

    // Hide the original input
    originalInput.type = 'hidden';

    // Create a container for the input and unity span
    var container = document.createElement('div');
    container.style.display = 'flex';
    container.style.alignItems = 'center';
    container.appendChild(newInput);
    container.appendChild(unitySpan);

    // Append the container to the form
    originalInput.parentNode.insertBefore(container, originalInput.nextSibling);

    // Add event listener to update the original input when the new input changes
    newInput.addEventListener('input', function() {
        originalInput.value = Math.round(newInput.value / getProductStep());
    });
}

// Function to duplicate the input with id="form_step3_minimal_quantity"
function duplicateMinimalQuantityInput() {
    // Get the original input
    var originalInput = document.getElementById('form_step3_minimal_quantity');
    if (!originalInput) return;

    // Create the new input
    var newInput = document.createElement('input');
    newInput.type = 'number';
    newInput.id = 'form_step3_minimal_quantity_decimal';
    newInput.step = getProductStep(); // Fetch the step from the product configured template
    newInput.value = calculateFractionalStock(originalInput.value, getProductStep()); // Calculate the initial value based on the fractional parts
    newInput.setAttribute('data-unity', getProductUnity()); // Set the template unity

    // Create a span to display the unity
    var unitySpan = document.createElement('span');
    unitySpan.textContent = getProductUnity();
    unitySpan.style.marginLeft = '0.5rem';
    unitySpan.style.lineHeight = '2.188rem';

    // Add styles to the new input
    newInput.style.height = 'auto';
    newInput.style.minHeight = '2.188rem';
    newInput.style.display = 'inline-block';
    newInput.style.width = 'calc(100% - 3rem)';
    newInput.style.height = '2.188rem';
    newInput.style.padding = '.375rem .4375rem';
    newInput.style.fontSize = '.875rem';
    newInput.style.fontWeight = '400';
    newInput.style.lineHeight = '1.5';
    newInput.style.color = '#363a41';
    newInput.style.backgroundColor = '#fff';
    newInput.style.backgroundClip = 'padding-box';
    newInput.style.border = '1px solid #bbcdd2';
    newInput.style.borderRadius = '4px';
    newInput.style.boxShadow = 'none';
    newInput.style.transition = 'border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out';
    newInput.style.padding = '.5rem 1rem';

    // Hide the original input
    originalInput.type = 'hidden';

    // Create a container for the input and unity span
    var container = document.createElement('div');
    container.style.display = 'flex';
    container.style.alignItems = 'center';
    container.appendChild(newInput);
    container.appendChild(unitySpan);

    // Append the container to the form
    originalInput.parentNode.insertBefore(container, originalInput.nextSibling);

    // Add event listener to update the original input when the new input changes
    newInput.addEventListener('input', function() {
        originalInput.value = Math.round(newInput.value / getProductStep());
    });
}

// Function to get the product step from the configured template
function getProductStep() {
    // Fetch the step value from the variable defined in base.php
    return window.productStep || 1;
}

// Function to get the product unity from the configured template
function getProductUnity() {
    // Fetch the unity value from the variable defined in base.php
    return window.productUnity || '';
}

// Function to calculate the fractional stock
function calculateFractionalStock(stock, step) {
    return stock * step;
}

// Function to duplicate inputs in the table rows
function duplicateTableInputs() {
    var rows = document.querySelectorAll('.js-combinations-list .combination');
    rows.forEach(function(row) {
        var td = row.querySelector('td.attribute-quantity');
        if (!td) return;

        var originalInput = td.querySelector('input');
        if (!originalInput) {
            return;
        }

        // Create the new decimal input
        var newDecimalInput = document.createElement('input');
        newDecimalInput.type = 'number';
        newDecimalInput.name = originalInput.name.replace('attribute_quantity', 'attribute_quantity_decimal');
        newDecimalInput.step = getProductStep(); // Fetch the step from the product configured template
        newDecimalInput.value = calculateFractionalStock(originalInput.value, getProductStep()); // Calculate the initial value based on the fractional parts
        newDecimalInput.setAttribute('data-unity', getProductUnity()); // Set the template unity

        // Create a span to display the unity
        var unitySpan = document.createElement('span');
        unitySpan.textContent = getProductUnity();
        unitySpan.style.marginLeft = '0.5rem';
        unitySpan.style.lineHeight = '2.188rem';

        // Add styles to the new decimal input
        newDecimalInput.style.height = 'auto';
        newDecimalInput.style.minHeight = '2.188rem';
        newDecimalInput.style.display = 'inline-block';
        newDecimalInput.style.width = 'calc(100% - 3rem)';
        newDecimalInput.style.height = '2.188rem';
        newDecimalInput.style.padding = '.375rem .4375rem';
        newDecimalInput.style.fontSize = '.875rem';
        newDecimalInput.style.fontWeight = '400';
        newDecimalInput.style.lineHeight = '1.5';
        newDecimalInput.style.color = '#363a41';
        newDecimalInput.style.backgroundColor = '#fff';
        newDecimalInput.style.backgroundClip = 'padding-box';
        newDecimalInput.style.border = '1px solid #bbcdd2';
        newDecimalInput.style.borderRadius = '4px';
        newDecimalInput.style.boxShadow = 'none';
        newDecimalInput.style.transition = 'border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out';
        newDecimalInput.style.padding = '.5rem 1rem';

        // Hide the original input
        originalInput.type = 'hidden';

        // Create a container for the input and unity span
        var container = document.createElement('div');
        container.style.display = 'flex';
        container.style.alignItems = 'center';
        container.appendChild(newDecimalInput);
        container.appendChild(unitySpan);

        // Append the container to the form
        originalInput.parentNode.insertBefore(container, originalInput.nextSibling);

        // Hide the original input and replace it with a copy
        var combinationId = row.dataset.index;
        var targetInput = document.getElementById(`combination_${combinationId}_attribute_quantity`);
        if (targetInput) {
            targetInput.type = 'hidden';

            var newDecimalInputCopy = document.createElement('input');
            newDecimalInputCopy.type = 'number';
            newDecimalInputCopy.value = targetInput.value;
            newDecimalInputCopy.step = newDecimalInput.step;
            newDecimalInputCopy.style.height = 'auto';
            newDecimalInputCopy.style.minHeight = '2.188rem';
            newDecimalInputCopy.style.display = 'inline-block';
            newDecimalInputCopy.style.width = 'calc(100% - 3rem)';
            newDecimalInputCopy.style.height = '2.188rem';
            newDecimalInputCopy.style.padding = '.375rem .4375rem';
            newDecimalInputCopy.style.fontSize = '.875rem';
            newDecimalInputCopy.style.fontWeight = '400';
            newDecimalInputCopy.style.lineHeight = '1.5';
            newDecimalInputCopy.style.color = '#363a41';
            newDecimalInputCopy.style.backgroundColor = '#fff';
            newDecimalInputCopy.style.backgroundClip = 'padding-box';
            newDecimalInputCopy.style.border = '1px solid #bbcdd2';
            newDecimalInputCopy.style.borderRadius = '4px';
            newDecimalInputCopy.style.boxShadow = 'none';
            newDecimalInputCopy.style.transition = 'border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out';
            newDecimalInputCopy.style.padding = '.5rem 1rem';

            // Create a span to display the unity for the new decimal input copy
            var newDecimalUnitySpan = document.createElement('span');
            newDecimalUnitySpan.textContent = getProductUnity();
            newDecimalUnitySpan.style.marginLeft = '0.5rem';
            newDecimalUnitySpan.style.lineHeight = '2.188rem';

            // Create a container for the new decimal input copy and unity span
            var newDecimalContainer = document.createElement('div');
            newDecimalContainer.style.display = 'flex';
            newDecimalContainer.style.alignItems = 'center';
            newDecimalContainer.appendChild(newDecimalInputCopy);
            newDecimalContainer.appendChild(newDecimalUnitySpan);

            targetInput.parentNode.insertBefore(newDecimalContainer, targetInput.nextSibling);

            
            // Add event listener to update the correct input when the new decimal input changes
            newDecimalInput.addEventListener('input', function() {
                let that = this;
                setTimeout(function() {
                    var combinationId = row.dataset.index;
                    var targetInput = document.getElementById(`combination_${combinationId}_attribute_quantity`);
                    targetInput.value = Math.round(that.value / getProductStep());

                    newDecimalInputCopy.value = that.value;
                }, 500);
            });

            
            // Add event listener to update the correct input when the new decimal input copy changes
            newDecimalInputCopy.addEventListener('input', function() {
                let that = this;
                setTimeout(function() {
                    var combinationId = row.dataset.index;
                    var targetInput = document.getElementById(`combination_${combinationId}_attribute_quantity`);

                    targetInput.value = Math.round(that.value / getProductStep());
                    newDecimalInput.value = that.value;
                }, 500);
            });
        }
    });
}

// Function to observe and duplicate inputs for new rows added to the table
function observeTableChanges() {
    var table = document.querySelector('.js-combinations-list');
    if (!table) return;

    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                duplicateTableInputs();
            }
        });
    });

    observer.observe(table, { childList: true });
}

// Wait for the DOM to be fully loaded before running the duplicateInput, duplicateMinimalQuantityInput, and duplicateTableInputs functions
document.addEventListener('DOMContentLoaded', function() {
    // Wait for the qty_0 input to exist
    var checkExist = setInterval(function() {
        if (document.querySelector('input[name="form[step3][qty_0]"]')) {
            clearInterval(checkExist);
            duplicateInput();
        }
    }, 100); // Check every 100 milliseconds

    // Wait for the minimal quantity input to exist
    var checkMinimalExist = setInterval(function() {
        if (document.getElementById('form_step3_minimal_quantity')) {
            clearInterval(checkMinimalExist);
            duplicateMinimalQuantityInput();
        }
    }, 100); // Check every 100 milliseconds

    // Check if the table has already been added to the DOM
    var checkTableExist = setInterval(function() {
        if (document.querySelector('.js-combinations-list')) {
            clearInterval(checkTableExist);
            duplicateTableInputs();
            observeTableChanges();
        }
    }, 100); // Check every 100 milliseconds
});

// ...existing code...

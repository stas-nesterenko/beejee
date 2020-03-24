$(document).on('submit', "form", function (e) {
    e.preventDefault();

    var _this = $(this);

    $(_this).find('.form-group .help-block').text('');
    $(_this).find('.form-group').removeClass('has-error');

    if (!validateInputs(_this)) {
        return false;
    }

    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: $(this).attr('method') === 'get' ? $(this).serializeArray() : new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: $(this).attr('method') === 'get',
        dataType: 'json',
        success: function (response) {
            if (response.field_error) {
                for (inputName in response.field_error) {
                    setError(_this, inputName, response.field_error[inputName]);
                }
            }
            if (response.location){
                window.location.href = response.location;
                return false;
            }
            if (response.success){
                $('#modal-success .modal-body p').html(response.success);
                $('#modal-success').modal();
            }
        }
    });
});

function setError(_form, inputName, error)
{
    $(_form).find('[name^="' + inputName + '"]').closest('.form-group').addClass('has-error');
    $(_form).find('[name^="' + inputName + '"]').siblings('.form-group .help-block').html(error);
}

function validateInputs(_form) {
    let result = true;

    if ($(_form).find('[name=validationRules]')) {
        const validationRules = JSON.parse($(_form).find('[name=validationRules]').val());
        for (let inputName in validationRules) {
            if ($(_form).find(`[name="${inputName}"]`)) {
                const value = $(_form).find(`[name="${inputName}"]`).val();
                if (validationRules[inputName].minLength && validationRules[inputName].minLength.value > value.length) {
                    setError(_form, inputName, validationRules[inputName].minLength.error);
                    result = false;
                } else if (validationRules[inputName].maxLength && validationRules[inputName].maxLength.value < value.length) {
                    setError(_form, inputName, validationRules[inputName].maxLength.error);
                    result = false;
                }
            }
        }
    }

    return result;
}

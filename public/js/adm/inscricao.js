$(document).ready(function () {
    $("input[name='tipo_pessoa']").change(function () {
        var val = $("input[type='radio'][name='tipo_pessoa']:checked").val();
        if (val == 0) {
            $('.pessoaJuridica').hide('slow');
            $('.pessoaFisica').show('slow');

        } else {
            $('.pessoaFisica').hide('slow');
            $('.pessoaJuridica').show('slow');

        }
    });
});
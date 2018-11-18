$('document').ready(function () {
    $('input[:radio]').change(function () {
        $("input[:radio] option:checked").each(function () {
            if ($("input[:radio] option:checked").val() == "0") {
                console.log('opF');
            } else {
                console.log('opJ');
            }
        });
    });
});
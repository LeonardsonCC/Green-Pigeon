prevent_ajax_view = true;

$(document).ready(function () {
    $('#close-feedback').click(function() {
        $('#feedback').slideUp('slow', function(){});
    });

    M.AutoInit();

    $(document).on('click', '#toast-container .toast', function () {
        $(this).fadeOut(function () {
            $(this).remove();
        });
    });

    $('#formulario-cadastrar').hide();

    $('.alert_close').click(function () {
        $(this).parent().parent().parent().parent().parent().slideUp("slow", function () {
        });
    });

    $('.alternar-login').click(function (e) {
        e.preventDefault();
        $('#formulario-entrar').fadeToggle(function () {
            $('#formulario-cadastrar').fadeToggle()
        });
    })
    $('.alternar-registrar').click(function (e) {
        e.preventDefault();
        $('#formulario-cadastrar').fadeToggle(function () {
            $('#formulario-entrar').fadeToggle()
        });
    });


    $('.modal-trigger').click(function (e) {
        if ($(this).data('href') != "") {
            $.ajax({
                url: $(this).data('href'),
                success: function (response) {
                    $('.modal').html(response);
                }
            })
        }
    });

    $('.modal-close').click(function (e) {
        e.preventDefault();
    });
});
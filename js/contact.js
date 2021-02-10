(function ($) {
    "use strict";

    const forms = document.getElementsByClassName('needs-validation');
    const validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();
            form.classList.add('was-validated');

            if (form.checkValidity() !== false) {
                $('.loading').show();
                const request = $.ajax({
                    type: "POST",
                    data: $(form).serialize(),
                    dataType: 'json',
                    url: "subscribe/index.php"
                })
                request.done((response, textStatus, jqXHR) => {
                    $('.loading').hide();
                    $(form).removeClass('was-validated');
                    $(this).find('input').val('');
                    $('#success').modal('show');
                    console.log(response);
                });
                request.fail((jqXHR, textStatus, errorThrown) => {
                    $('.loading').hide();
                    console.error(
                        "The following error occurred: "+
                        textStatus, errorThrown
                    );
                })
            }
        }, false);
    });

})(jQuery)

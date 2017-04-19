<script>


    $(document).ready(function () {

        // ---------------------------------------------------------
        $('.btn-ejecutar-envio').click(function () {
            alerta = $(this).attr('href').replace(/^.*?(#|$)/, '');

            var request = $.ajax({
                method: "GET",
                url: "{{ url('config/ejecutar/envio/" + alerta + "') }}"
            });

            request.done(function (data) {
                $('#mensaje').html(data);
                $('#mensaje').attr('class', 'alert alert-success');
            });

            request.fail(function (data, textStatus) {
                $('#mensaje').html("Error: " + textStatus);
                $('#mensaje').attr('class', 'alert alert-error');
            });

        });
        // ---------------------------------------------------------

        $('#template_compromiso_atrasado').wysihtml5();
        $('#template_compromiso_en_suscripcion').wysihtml5();

        // Determina si el form es solamente para visualizacion
        var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
        if (show_view) {
            $("input, textarea").attr('readonly', 'readonly');
        }

        $("#dias_alerta_compromiso_atrasado_1").numeric();
        $("#dias_alerta_compromiso_atrasado_2").numeric();
        $("#dias_alerta_compromiso_atrasado_3").numeric();
        $("#dias_alerta_compromiso_suscripcion").numeric();
        // Inicia switch para estado activo/inactivo
        $("[name='fl_status']").bootstrapSwitch();

        //Inicia validacion
        $("form[name=configForm]").validate({
            lang: 'en'
            , rules: {
                email_compromiso_atrasado: {required: true},
                dias_alerta_compromiso_atrasado_1: {required: true},
                asunto_compromiso_atrasado: {required: true},
                template_compromiso_atrasado: {required: true},
                asunto_compromiso_en_suscripcion: {required: true},
                template_compromiso_en_suscripcion: {required: true},
                dias_alerta_compromiso_suscripcion: {required: true}
            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=configForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_config").append('<option value=' + json['id_config'] + ' selected="selected">' + json['nombre_config'] + '</option>');
                        //console.log(json['id_config']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
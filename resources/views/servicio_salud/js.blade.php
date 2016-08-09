<script>


    $(document).ready(function () {

        // Determina si el form es solamente para visualizacion
        var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
        if (show_view) {
            $("input, textarea, select").attr('disabled', 'disabled');
        }

        // Inicia switch para estado activo/inactivo
        $("[name='fl_status']").bootstrapSwitch();

        //Inicia validacion
        $("#rut_completo").keypress(function (e) {

            if (/\d+|,+|[/b]+|-+/i.test(e.key)) {

                console.log("character accepted: " + e.key)
            } else {
                console.log("illegal character detected: " + e.key)
                return false;
            }

        });
        jQuery.validator.addMethod("validarut_completo", function (value, element) {
            validaRut = Rut(value);
            return validaRut;
        }, "Por favor informe un rut_completo valido, sin puntos");

        $("form[name=servicio_saludForm]").validate({
            rules: {
                id_servicio_salud: {required: true},
                nombre_servicio_salud: {required: true},
                nombre_subsecretario_a: {required: true},
                rut_completo: {required: true, validarut_completo: true}
            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=servicio_saludForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_servicio_salud").append('<option value=' + json['id_servicio_salud'] + ' selected="selected">' + json['nombre_servicio_salud'] + '</option>');
                        //console.log(json['id_servicio_salud']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
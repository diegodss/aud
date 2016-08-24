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


        $("form[name=auditorForm]").validate({
            rules: {
                nombre_auditor: {required: true}
                //,rut_completo: {required: true, validarut_completo: true }
            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=auditorForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_auditor").append('<option value=' + json['id_auditor'] + ' selected="selected">' + json['nombre_auditor'] + '</option>');
                        //console.log(json['id_auditor']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
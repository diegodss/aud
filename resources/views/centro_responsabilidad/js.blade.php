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



        $("form[name=centro_responsabilidadForm]").validate({
            rules: {
                id_subsecretaria: {required: true},
                nombre_centro_responsabilidad: {required: true},
                tipo: {required: true}
            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=centro_responsabilidadForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_centro_responsabilidad").append('<option value=' + json['id_centro_responsabilidad'] + ' selected="selected">' + json['nombre_centro_responsabilidad'] + '</option>');
                        //console.log(json['id_centro_responsabilidad']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
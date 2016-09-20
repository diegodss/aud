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
        $("form[name=departamentoForm]").validate({
             lang: 'en' 
			 , rules: {
                id_centro_responsabilidad: {required: true},
                nombre_departamento: {required: true}

            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=departamentoForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_departamento").append('<option value=' + json['id_departamento'] + ' selected="selected">' + json['nombre_departamento'] + '</option>');
                        //console.log(json['id_departamento']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
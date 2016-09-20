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
        $("form[name=unidadForm]").validate({
             lang: 'en' 
			 , rules: {
                id_departamento: {required: true},
                nombre_unidad: {required: true}

            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=unidadForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_unidad").append('<option value=' + json['id_unidad'] + ' selected="selected">' + json['nombre_unidad'] + '</option>');
                        //console.log(json['id_unidad']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
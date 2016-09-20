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
        $("form[name=establecimientoForm]").validate({
             lang: 'en' 
			 , rules: {
                id_servicio_salud: {required: true},
                nombre_establecimiento: {required: true},
                tipo_establecimiento: {required: true}
            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=establecimientoForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_establecimiento").append('<option value=' + json['id_establecimiento'] + ' selected="selected">' + json['nombre_establecimiento'] + '</option>');
                        //console.log(json['id_establecimiento']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
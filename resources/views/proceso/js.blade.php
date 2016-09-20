<script>


    $(document).ready(function () {

        // Uso de select2 para campo de proceso
        $('#id_proceso').select2();

        // Determina si el form es solamente para visualizacion
        var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
        if (show_view) {
            $("input, textarea").attr('readonly', 'readonly');
        }

        // Inicia switch para estado activo/inactivo
        $("[name='fl_status']").bootstrapSwitch();

        //Inicia validacion
        $("form[name=procesoForm]").validate({
             lang: 'en' 
			 , rules: {
                nombre_proceso: {required: true},
                responsable_proceso: {required: true}
            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=procesoForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_proceso").append('<option value=' + json['id_proceso'] + ' selected="selected">' + json['nombre_proceso'] + '</option>');
                        //console.log(json['id_proceso']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
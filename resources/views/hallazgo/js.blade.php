<script>


    $(document).ready(function () {

        // Uso de select2 para campo de hallazgo
        $('#id_hallazgo').select2();

        // Determina si el form es solamente para visualizacion
        var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
        if (show_view) {
            $("input, textarea").attr('readonly', 'readonly');
        }

        //Inicia validacion
        $("form[name=hallazgoForm]").validate({
             lang: 'en' 
			 , rules: {
                nombre_hallazgo: {required: true},
                recomendacion: {required: true},
				criticidad: {required: true},
                id_proceso_auditado: {required: true}
            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=hallazgoForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_hallazgo").append('<option value=' + json['id_hallazgo'] + ' selected="selected">' + json['nombre_hallazgo'] + '</option>');
                        //console.log(json['id_hallazgo']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
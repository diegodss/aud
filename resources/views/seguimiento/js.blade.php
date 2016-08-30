<script>


    $(document).ready(function () {

        // Determina si el form es solamente para visualizacion
        var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
        if (show_view) {
            $("input, textarea, select").attr('disabled', 'disabled');
        }

        //Inicia validacion
        $("form[name=seguimientoForm]").validate({
            rules: {
                id_compromiso: {required: true}
                , diferencia_tiempo: {required: true}
                , estado: {required: true}
                , condicion: {required: true}
                , porcentaje_avance: {required: true}
            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=seguimientoForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_seguimiento").append('<option value=' + json['id_seguimiento'] + ' selected="selected">' + json['diferencia_tiempo'] + '</option>');
                        //console.log(json['id_seguimiento']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
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
        $("form[name=compromisoForm]").validate({
            rules: {
                id_hallazgo: {required: true},
                plazo_estimado: {required: true}

            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=compromisoForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_compromiso").append('<option value=' + json['id_compromiso'] + ' selected="selected">' + json['plazo_estimado'] + '</option>');
                        //console.log(json['id_compromiso']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
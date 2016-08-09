<script>


    $(document).ready(function () {

        // Uso de select2 para campo de comuna
        $('#id_comuna').select2();

        // Determina si el form es solamente para visualizacion
        var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
        if (show_view) {
            $("input, textarea").attr('readonly', 'readonly');
        }

        // Inicia switch para estado activo/inactivo
        $("[name='fl_status']").bootstrapSwitch();

        //Inicia validacion
        $("form[name=comunaForm]").validate({
            rules: {
                nombre_comuna: {required: true},
                cod_comuna_deis: {required: true},
                id_region: {required: true}
            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=comunaForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_comuna").append('<option value=' + json['id_comuna'] + ' selected="selected">' + json['nombre_comuna'] + '</option>');
                        //console.log(json['id_comuna']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
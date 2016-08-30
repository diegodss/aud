<script>


    function prin(id) {

    }
    $(document).ready(function () {
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $('#excel').on('click', function (e) {
            console.log("imprimindo excel");
            var url = "{{ URL::to('/') }}/planilla_seguimiento/excel";
            console.log(url);
            $.get(url);

        }); //click

        $('#print').on('click', function (e) {
            console.log("imprimir div");
            $('#planilla_seguimiento').printElement();

        }); //click

        $('#plazo_comprometido_inicio').datepicker({
            format: "dd-mm-yyyy",
            language: "es",
            autoclose: true
        });

        $('#plazo_comprometido_fin').datepicker({
            format: "dd-mm-yyyy",
            language: "es",
            autoclose: true
        });

        // Determina si el form es solamente para visualizacion
        var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
        if (show_view) {
            $("input, textarea").attr('readonly', 'readonly');
        }

        // Inicia switch para estado activo/inactivo
        $("[name='fl_status']").bootstrapSwitch();

        //Inicia validacion
        $("form[name=regionForm]").validate({
            rules: {
                nombre_region: {required: true}
            }
        });

        // Define si es un formulario de mantenedor o formluario rapido
        $(function () {
            $('form[name=regionForm]').submit(function () {
                console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function (json) {
                        $("#id_region").append('<option value=' + json['id_region'] + ' selected="selected">' + json['nombre_region'] + '</option>');
                        //console.log(json['id_region']);
                        $('#myModal').modal('toggle');
                    }, 'json');

                    return false;
                }

            });
        });
    });
</script>
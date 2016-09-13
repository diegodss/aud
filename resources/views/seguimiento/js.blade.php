<script>


    $(document).ready(function () {

        selectNumeroInformeUnidad = '<select id="numero_informe_unidad" class="form-control form-100" name="numero_informe_unidad"><option value="" selected="selected">Unidad</option><option value="UAI">UAI</option><option value="UAE">UAE</option><option value="UAS">UAS</option><option value="DAM">DAM</option></select>';
        selectAno = '<select id="ano" class="form-control" name="ano"><option value="" selected="selected">Año</option><option value="2016">2016</option><option value="2015">2015</option><option value="2014">2014</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option></select>';

        $("#fg_numero_informe_unidad").html(selectNumeroInformeUnidad);
        $("#fg_ano").html(selectAno);

        $('#estado').on('change', function (e) {

            if (this.value == "Reprogramado") {
                $("#condicion").val("Reprogramado");
            }

        });
        // Determina si el form es solamente para visualizacion
        var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
        if (show_view) {
            $("input, textarea, select").attr('disabled', 'disabled');
        }

        $("#porcentaje_avance").numeric();

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
<script>

    function getParameterByName(name, url) {
        if (!url) {
            url = window.location.href;
        }
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
        if (!results)
            return null;
        if (!results[2])
            return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    $(document).ready(function () {

        selectNumeroInformeUnidad = '<select id="numero_informe_unidad" class="form-control form-100" name="numero_informe_unidad"><option value="" selected="selected">Unidad</option><option value="UAI">UAI</option><option value="UAE">UAE</option><option value="UAS">UAS</option><option value="DAM">DAM</option></select>';

        var numero_informe_unidad_request = getParameterByName('numero_informe_unidad');
        var ano_request = getParameterByName('ano');

        var myDate = new Date();
        var year = myDate.getFullYear();
        var finalYear = year - 10;
        var anoSelected;
        var unidadSelected;

        selectAno = '<select id="ano" class="form-control" name="ano">';
        selectAno += '<option value="" selected="selected">Año</option>'

        for (var i = year; i >= finalYear; i--) {

            anoSelected = '';
            if (parseInt(numero_informe_unidad_request) == parseInt(i)) {
                anoSelected = 'selected="selected"';
            }


            selectAno += '<option value="' + i + '" ' + anoSelected + '>' + i + '</option>';
        }
        selectAno += '</select>';

        $("#fg_numero_informe_unidad").html(selectNumeroInformeUnidad);
        $("#fg_ano").html(selectAno);

        $('#estado').on('change', function (e) {

            $('#condicion').empty()

            switch (this.value) {
                case "REPROGRAMADO":
                    $('#condicion').append("<option value='Reprogramado'>Reprogramado</option>");
                    $("#condicion").val("Reprogramado");
                    break;
                case "FINALIZADO":
                    $('#condicion').append("<option value='Asume riesgo'>Asume riesgo</option>");
                    $('#condicion').append("<option value='Cumplido'>Cumplido</option>");
                    $("#condicion").val("Cumplido");
                    break;

                case "VENCIDO":
                    $('#condicion').append("<option value='No evaluado'>No evaluado</option>");
                    $('#condicion').append("<option value='Cumplido Parcial'>Cumplido Parcial</option>");
                    $('#condicion').append("<option value='No cumplido'>No cumplido</option>");
                    $('#condicion').append("<option value='Cumplido'>Cumplido</option>");
                    $("#condicion").val("Cumplido Parcial");
                    break;

                case "VIGENTE":
                    $('#condicion').append("<option value='No evaluado'>No evaluado</option>");
                    $('#condicion').append("<option value='Cumplido Parcial'>Cumplido Parcial</option>");
                    $('#condicion').append("<option value='Cumplido'>Cumplido</option>");
                    $("#condicion").val("Cumplido");
                    break;

                case "EN SUSCRIPCION":
                    $('#condicion').append("<option value='No evaluado'>No evaluado</option>");
                    $("#condicion").val("No evaluado");
                    break;

                default:
                    break;
            }

            if (this.value == "REPROGRAMADO") {
                $("#condicion").val("Reprogramado");
            } else if (this.value == "Finalizado" && $("#porcentaje_avance").val() == 100) {
                $("#condicion").val("Cumplida");
            }
        });

        $('#porcentaje_avance').on('focusout', function (e) {
            if ($("#porcentaje_avance").val() >= 1 && $("#porcentaje_avance").val() <= 99) {
                $("#condicion").val("Cumplida Parcial");
            } else if ($("#porcentaje_avance").val() == 100) { //$('#estado').val() == "FINALIZADO" &&
                $("#condicion").val("Cumplida");
            }

        });


        /*
         $('.porcentaje_avance_slider').each(function () {
         var $projectBar = $(this).find('.bar');
         var $projectPercent = $(this).find('.percent');
         var $projectRange = $(this).find('.ui-slider-range');
         $projectBar.slider({
         range: "min",
         animate: true,
         value: $("#percent_v").val(),
         min: 0,
         max: 100,
         step: 1,
         slide: function (event, ui) {
         $projectPercent.val(ui.value);
         },
         change: function (event, ui) {
         var $projectRange = $(this).find('.ui-slider-range');
         var percent = ui.value;
         if (percent < 30) {
         $projectPercent.css({
         'color': 'red'
         });
         $projectRange.css({
         'background': '#f20000'
         });
         } else if (percent > 31 && percent < 70) {
         $projectPercent.css({
         'color': 'gold'
         });
         $projectRange.css({
         'background': 'gold'
         });
         } else if (percent > 70) {
         $projectPercent.css({
         'color': 'green'
         });
         $projectRange.css({
         'background': 'green'
         });
         }
         }
         });
         });
         */

        // Determina si el form es solamente para visualizacion
        var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
        if (show_view) {
            $("input, textarea, select").attr('disabled', 'disabled');
        }

        $("#porcentaje_avance").numeric();

        //Inicia validacion
        $("form[name=seguimientoForm]").validate({
            lang: 'en'
            , rules: {
                id_compromiso: {required: true}
                , diferencia_tiempo: {required: true}
                , estado: {required: true}
                , condicion: {required: true}
                , porcentaje_avance: {required: true, max: 100}
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
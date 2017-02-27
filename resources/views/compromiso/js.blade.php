<script>

    $(function () {

        $("#responsable").autocomplete({
            source: "{{ url('compromiso/get/json/responsable') }}",
            minLength: 3,
            select: function (event, ui) {
                $("#responsable").val(ui.item.value);
                $("#fono_responsable").val(ui.item.fono_responsable);
                $("#email_responsable").val(ui.item.email_responsable);
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            html_val = "<a>" + item.value;
            if (item.email_responsable != null) {
                html_val += ' <span style="font-size:11px">(' + item.email_responsable + ")</span>";
            }
            html_val += "</a>";
            return jQuery("<li></li>").data("item.autocomplete", item).append(html_val).appendTo(ul);
        };

        $("#responsable2").autocomplete({
            source: "{{ url('compromiso/get/json/responsable') }}",
            minLength: 3,
            select: function (event, ui) {
                $("#responsable2").val(ui.item.value);
                $("#fono_responsable2").val(ui.item.fono_responsable);
                $("#email_responsable2").val(ui.item.email_responsable);
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            html_val = "<a>" + item.value;
            if (item.email_responsable != null) {
                html_val += ' <span style="font-size:11px">(' + item.email_responsable + ")</span>";
            }
            html_val += "</a>";
            return jQuery("<li></li>").data("item.autocomplete", item).append(html_val).appendTo(ul);
        };
    });

    $(document).ready(function () {


        $('#box_nomenclatura_historico').hide();
        $('#btn_nomenclatura_historico').click(function () {

            $('#box_nomenclatura_historico').toggle();

        });

        $('#plazo_estimado').datepicker({
            startDate: "{{ $proceso_fecha }}",
            format: "dd-mm-yyyy",
            language: "es",
            autoclose: true
        });

        $('#plazo_comprometido').datepicker({
            startDate: "{{ $proceso_fecha }}",
            format: "dd-mm-yyyy",
            language: "es",
            autoclose: true
        });

<?php if ($compromiso->id_compromiso_padre > 0) : ?>
            $('#compromiso_padre').load("{{ URL::to('/') }}/compromiso/show/modal/{{ $compromiso->id_compromiso_padre}}");
<?php endif; ?>


        // Determina si el form es solamente para visualizacion
        var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
        if (show_view) {
            $("input, textarea, select").attr('disabled', 'disabled');
        }

        //Inicia validacion
        $("form[name=compromisoForm]").validate({
            lang: 'en'
            , rules: {
                id_hallazgo: {required: true},
                nombre_compromiso: {required: true},
                plazo_estimado: {required: true},
                plazo_comprometido: {required: true},
                responsable: {required: true},
                email_responsable: {required: true, email: true},
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
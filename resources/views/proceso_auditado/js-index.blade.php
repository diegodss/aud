<script>

    $(function () {
        // Enables popover
        $("[data-toggle=popover]").popover({html: true});
    });




    $(document).ready(function () {



        $("[rel=tooltip]").tooltip({html: true});


        var myDate = new Date();
        var year = myDate.getFullYear();
        var finalYear = year - 10;
        selectNumeroInformeUnidad = '<select id="numero_informe_unidad" class="form-control form-100" name="numero_informe_unidad"><option value="" selected="selected">Unidad</option><option value="UAI">UAI</option><option value="UAE">UAE</option><option value="UAS">UAS</option><option value="DAM">DAM</option></select>';
        selectAno = '<select id="ano" class="form-control" name="ano">';
        selectAno += '<option value="" selected="selected">Año</option>'

        for (var i = year; i >= finalYear; i--) {
            selectAno += '<option value="' + i + '">' + i + '</option>';
        }
        selectAno += '</select>';

        $("#fg_numero_informe_unidad").html(selectNumeroInformeUnidad);
        $("#fg_ano").html(selectAno);

    });
</script>
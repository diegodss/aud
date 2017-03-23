<script>

    $(function () {
        // Enables popover
        $("[data-toggle=popover]").popover({html: true});
    });


	function getParameterByName(name, url) {
		if (!url) {
		  url = window.location.href;
		}
		name = name.replace(/[\[\]]/g, "\\$&");
		var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			results = regex.exec(url);
		if (!results) return null;
		if (!results[2]) return '';
		return decodeURIComponent(results[2].replace(/\+/g, " "));
	}

    $(document).ready(function () {



        $("[rel=tooltip]").tooltip({html: true});

		var numero_informe_unidad_request = getParameterByName('numero_informe_unidad');
		var ano_request = getParameterByName('ano');

		console.log(numero_informe_unidad + ' = ' + ano);
		
        var myDate = new Date();
        var year = myDate.getFullYear();
        var finalYear = year - 10;
		var anoSelected;
		var unidadSelected;
		
        selectNumeroInformeUnidad = '<select id="numero_informe_unidad" class="form-control form-100" name="numero_informe_unidad">';
		selectNumeroInformeUnidad += '<option value="" selected="selected">Unidad</option>';
		selectNumeroInformeUnidad += '<option value="UAI">UAI</option>';
		selectNumeroInformeUnidad += '<option value="UAE">UAE</option>';
		selectNumeroInformeUnidad += '<option value="UAS">UAS</option>';
		selectNumeroInformeUnidad += '<option value="DAM">DAM</option>';
		selectNumeroInformeUnidad += '</select>';
		
        selectAno = '<select id="ano" class="form-control" name="ano">';
        selectAno += '<option value="" selected="selected">Año</option>'

        for (var i = year; i >= finalYear; i--) {
			
			anoSelected = '';
			if (parseInt(numero_informe_unidad_request) == parseInt(i)) {
				anoSelected = 'selected="selected"' ;
			}
			
			
            selectAno += '<option value="' + i + '" '+anoSelected+'>' + i + '</option>';
        }
        selectAno += '</select>';

        $("#fg_numero_informe_unidad").html(selectNumeroInformeUnidad);
        $("#fg_ano").html(selectAno);

    });
</script>
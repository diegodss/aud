<script>


 $(document).ready(function() {

        // Uso de select2 para campo de proceso_auditado
        $('#region').select2();
		$('#organismo').select2();
		$('#id_division').select2();
		$('#id_gabinete').select2();
		$('#id_seremi').select2();
		$('#id_servicio_salud').select2();
		$('#id_establecimiento').select2();
		$('#id_departamento').select2();
		$('#id_unidad').select2();
		
		
        // Determina si el form es solamente para visualizacion
        var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
        if (show_view) {
            $("input, textarea").attr('readonly', 'readonly');
        }

        // Inicia switch para estado activo/inactivo
        $("[name='fl_status']").bootstrapSwitch();
        //Inicia validacion
        $("form[name=proceso_auditadoForm]").validate({
            rules: {
                nombre_proceso_auditado: {
                    required: true
                },
                nombre_ministro: {
                    required: true
                }
            }
        });
        // Define si es un formulario de mantenedor o formluario rapido
        $(function() {
            $('form[name=proceso_auditadoForm]').submit(function() {
                //console.log($("#modal_input").val());
                is_modal = $("#modal_input").val();
                if (is_modal == "sim") {

                    $.post($(this).attr('action'), $(this).serialize(), function(json) {
                        $("#id_proceso_auditado").append('<option value=' + json['id_proceso_auditado'] + ' selected="selected">' + json['nombre_proceso_auditado'] + '</option>');
                        //console.log(json['id_proceso_auditado']);
                        $('#myModal').modal('toggle');
                    }, 'json');
                    return false;
                }

            });
        });
		
		$('.link_tab').on('click', function(e) {
			
			tipo = $(this).attr('href').replace(/^.*?(#|$)/,'');
			tipo = tipo.replace("tab_", "");
			
			$("#tipo").val(tipo);
			
			$(".div_subsecretaria_search").hide();
			$(".div_servicio_salud_search").hide();
			$(".div_tipo_centro_responsabilidad").hide();
			$(".div_centro_responsabilidad_search").hide();
			$(".div_departamento_search").hide();
			
			switch( tipo ){
				 case "division":
				 case "seremi":
				 case "gabinete":
				 case "servicio_salud":
					$(".div_subsecretaria_search").show();
				 break;
				 case "establecimiento":					
					$(".div_servicio_salud_search").show();
				 break;
				 case "departamento":
					$(".div_subsecretaria_search").show();
					$(".div_tipo_centro_responsabilidad").show();
					$(".div_centro_responsabilidad_search").show();
				 break;
				 case "unidad":
					$(".div_subsecretaria_search").show();
					$(".div_tipo_centro_responsabilidad").show();
					$(".div_centro_responsabilidad_search").show();
					$(".div_departamento_search").show();
				 break;
				}
			
		});
		//------------------------- Organismo y id_subsecretaria ------------------------------
        $('#id_ministerio').on('change', function(e) {

            $('#id_organismo').empty();
            $('#id_organismo').append("<option value=''>Seleccione</option>")
            var id_ministerio = e.target.value;
            $.get('{{ url('organismo') }}/get/json?id_ministerio=' + id_ministerio,
                function(data) {
                   // console.log(data);    
                    $.each(data, function(index, subCatObj) {
                        //console.log(subCatObj.nombre_organismo);
                        $('#id_organismo').append("<option value='" + subCatObj.id_organismo + "'>" + subCatObj.nombre_organismo + "</option>")
                    });
                }); //get
            $('#id_subsecretaria').empty();
            $('#id_subsecretaria').append("<option value=''>Seleccione</option>")
            var id_ministerio = e.target.value;
            $.get('{{ url('subsecretaria') }}/get/json?id_ministerio=' + id_ministerio,
                function(data) {
                   // console.log(data);    
                    $.each(data, function(index, subCatObj) {
                       // console.log(subCatObj.nombre_subsecretaria);
                        $('#id_subsecretaria').append("<option value='" + subCatObj.id_subsecretaria + "'>" 
						+ subCatObj.nombre_subsecretaria + "</option>")
						
						$('#subsecretaria_search').append("<option value='" + subCatObj.id_subsecretaria + "'>" 
						+ subCatObj.nombre_subsecretaria + "</option>")
                    });
                }); //.get 
        }); //onchange
		//----------------------- Subsecretaria ------------------------
		$('#subsecretaria_search').on('change', function(e) {
		var id_subsecretaria = e.target.value;
		var tipo = $("#tipo").val();
		//var tipo = tipo.toLowerCase();

		if (tipo == 'gabinete' || tipo == 'seremi' || tipo == 'division') {

			$('#id_' + tipo).empty();
			$('#id_' + tipo).append("<option value=''>Seleccione</option>");
			
			$.get("{{ url('centro_responsabilidad') }}/get/json?id_subsecretaria=" + id_subsecretaria+"&tipo="+tipo,
			function(data) {
				console.log(data);    
				$.each(data, function(index, subCatObj) {
					console.log(subCatObj.nombre_centro_responsabilidad);
					$('#id_' + tipo).append("<option value='" + subCatObj.id_centro_responsabilidad + "'>" + subCatObj.nombre_centro_responsabilidad + "</option>")
				});
			}); //get
						
		}

        }); //onchange

		//----------------------- tipo_centro_responsabilidad ------------------------
		$('.tipo_centro_responsabilidad').on('click', function(e) {
		var id_subsecretaria =  $("#subsecretaria_search").val(); // e.target.value;
		var tipo = $('input[name=tipo_centro_responsabilidad]:checked', '#proceso_auditadoForm').val(); //$("#tipo_centro_responsabilidad").val();

			$('#lbl_centro_responsabilidad_search').text(tipo);
			tipo = tipo.toLowerCase();
			$('#centro_responsabilidad_search').empty();
			$('#centro_responsabilidad_search').append("<option value=''>Seleccione</option>");
			
			$.get("{{ url('centro_responsabilidad') }}/get/json?id_subsecretaria=" + id_subsecretaria+"&tipo="+tipo,
			function(data) {
				console.log(data);    
				$.each(data, function(index, subCatObj) {
					console.log(subCatObj.nombre_centro_responsabilidad);
					$('#centro_responsabilidad_search').append("<option value='" + subCatObj.id_centro_responsabilidad + "'>" + subCatObj.nombre_centro_responsabilidad + "</option>")
				});
			}); //get
					
        }); //onchange
		
		//----------------------- tipo_centro_responsabilidad ------------------------
		$('#centro_responsabilidad_search').on('change', function(e) {
		var id_centro_responsabilidad = e.target.value;

			$('#id_departamento').empty();
			$('#id_departamento').append("<option value=''>Seleccione</option>");

			$('#departamento_search').empty();
			$('#departamento_search').append("<option value=''>Seleccione</option>");

			
			$.get("{{ url('departamento') }}/get/json?id_centro_responsabilidad=" + id_centro_responsabilidad,
			function(data) {
				console.log(data);    
				$.each(data, function(index, subCatObj) {
					console.log(subCatObj.nombre_departamento);
					$('#id_departamento').append("<option value='" + subCatObj.id_departamento + "'>" + subCatObj.nombre_departamento + "</option>")
					$('#departamento_search').append("<option value='" + subCatObj.id_departamento + "'>" + subCatObj.nombre_departamento + "</option>")
				});
			}); //get
					
        }); //onchange		
    		//----------------------- tipo_departamento ------------------------
		$('#departamento_search').on('change', function(e) {
		var id_departamento = e.target.value;

			$('#id_unidad').empty();
			$('#id_unidad').append("<option value=''>Seleccione</option>");
			
			$.get("{{ url('unidad') }}/get/json?id_departamento=" + id_departamento,
			function(data) {
				console.log(data);    
				$.each(data, function(index, subCatObj) {
					console.log(subCatObj.nombre_unidad);
					$('#id_unidad').append("<option value='" + subCatObj.id_unidad + "'>" + subCatObj.nombre_unidad + "</option>")
				});
			}); //get
					
        }); //onchange	
        		
		//----------------------- Servicio Salud  ------------------------
		$('#servicio_salud_search').on('change', function(e) {
		var id_servicio_salud = e.target.value;

			$('#id_establecimiento').empty();
			$('#id_establecimiento').append("<option value=''>Seleccione</option>");
			
			$.get("{{ url('establecimiento') }}/get/json?id_servicio_salud=" + id_servicio_salud,
			function(data) {
count= 0;
				$.each(data, function(index, subCatObj) {
					//console.log(subCatObj.nombre_establecimiento);
					count++;
					$('#id_' + tipo).append("<option value='" + subCatObj.id_establecimiento + "'>" + count + ". "+ subCatObj.nombre_establecimiento + "</option>")
				});
				console.log( count );    
			}); //get
						

        }); //onchange		
		
		$('#fecha').datepicker({
        format: "dd-mm-yyyy",
        language: "es",
        autoclose: true
    });
	$("#pulso").numeric();
	
	
    }); // document.ready



    /* Functions */
    function setTr(v) {

        document.getElementById("txt_centro_responsabilidad").innerHTML = v;
        document.getElementById('div_serviciosalud').style.display = 'none';
        document.getElementById('div_depto').style.display = 'none';
        document.getElementById('div_organismo').style.display = 'none';
        document.getElementById('div_subsecretaria').style.display = 'none';
        document.getElementById('div_centroresposabilidad').style.display = 'none';
        document.getElementById('div_serviciosalud').style.display = 'none';
        document.getElementById('div_depto').style.display = 'none';
        //alert(v);
        console.log(v);
        switch (v) {
            case "Organismo":
                document.getElementById('div_organismo').style.display = 'block';
                break;
            case "Subsecretaria":
                document.getElementById('div_subsecretaria').style.display = 'block';
                break;
            case "Servicio Salud":
                document.getElementById('div_subsecretaria').style.display = 'block';
                document.getElementById('div_centroresposabilidad').style.display = 'block';
                document.getElementById('div_serviciosalud').style.display = 'block';
                break;
            default:
                document.getElementById('div_centroresposabilidad').style.display = 'block';
                document.getElementById('div_subsecretaria').style.display = 'block';
                document.getElementById('div_depto').style.display = 'block';
                break;
        }


    }
</script>
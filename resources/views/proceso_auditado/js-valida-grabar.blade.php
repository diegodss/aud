<script>
    
    $(document).ready(function() {

        //Inicia validacion
        $("form[name=proceso_auditadoForm]").validate({
            ignore: [],
            lang: 'es',
            rules: {

                nombre_ministro: {
                    required: true
                },
                objetivo_auditoria: {
                    required: true
                },
                actividad_auditoria: {
                    required: true
                },
                tipo_auditoria: {
                    required: true
                },
                nomenclatura: {
                    required: true
                },
                numero_informe: {
                    required: true
                },
                numero_informe_unidad: {
                    required: true
                },
                ano: {
                    required: true
                },
                fecha: {
                    required: true
                },
                id_auditor_lider: {
                    required: true
                }
            },
            messages: {
                id_auditor_lider: "Por favor informe el lider del equipo"
            }
        });
        
        $("form[name=proceso_auditado_filtroForm]").validate();
		
        $('.grabar-proceso-auditado').attr('disabled', 'disabled');

		$("#numero_informe").on('focusout', function(e) {			
			validateNumeroInforme();
		});
		
		$("#numero_informe_unidad").on('focusout', function(e) {
			validateNumeroInforme();
		});
		
		$("#ano").on('change', function(e) {
			validateNumeroInforme();
		});
		
		$("#fecha").on('change', function(e) {
			validateNumeroInforme();
		});
		
        function validateNumeroInforme() {

			numero_informe = $("#numero_informe").val();
			numero_informe_unidad = $("#numero_informe_unidad").val();
			ano = $("#ano").val();
			fecha = $("#fecha").val();
			
			if ((numero_informe != "") && (numero_informe_unidad != "") && (ano != "") && (fecha != "") ){
			
			$('#mensaje').html('enviando...');			
			$('#mensaje').removeAttr('class');
		
			var cda = {					
					numero_informe : $("#numero_informe").val(),
					numero_informe_unidad : $("#numero_informe_unidad").val(),
					ano : $("#ano").val(),
					fecha : $("#fecha").val()
					}
		
			var request = $.ajax({
				  method: "GET",
				  url: "{{ url('/proceso_auditado/valida/numero_informe') }}",
				  data: cda			  			  		
				});
				
			request.done(function(data) {
			 				
				if (data == "OK") {		
					$('#mensaje').html("Ok");
					$('#mensaje').attr('class', 'alert alert-success mensaje_proceso_auditado');
					$('.grabar-proceso-auditado').removeAttr('disabled');					
				} else {
					$('#mensaje').html("Error: " ); //+ textStatus
					$('#mensaje').attr('class', 'alert alert-error mensaje_proceso_auditado');
					$('.grabar-proceso-auditado').attr('disabled', 'disabled');					
				}								
				$('#mensaje').html(data);
			});
			
			request.fail(function( data, textStatus ) {
				$('#mensaje').attr('class', 'alert alert-error mensaje_proceso_auditado');				
				$('#mensaje').html("Error: " + textStatus);
			}); // fail
			}
        }
    }); // document.ready    
</script>		
<script>
    
    $(document).ready(function() {

        $(".proceso_auditado_hallazgo").on('click', '.ver_compromiso', function(e) {
            //$('.ver_compromiso').click(function(){

            var id = $(this).attr('href').replace(/^.*?(#|$)/, '');
            if ($("table.proceso_auditado_hallazgo tbody").find('tr#' + id).length) {

                $("table.proceso_auditado_hallazgo tbody").find('tr#' + id).each(function() {

                    console.log(id);
                    //$(this).attr('bgcolor', '#ff0000');
                    //$(this).toggle( "highlight" );
                    $(this).effect("highlight", {}, 1000);
                });
            } else {

                var id_parent_tr = $(this).closest("tr").attr("id");
                $.get("{{ URL::to('/') }}/proceso_auditado/compromiso/" + id, function(data) {
                    ////console.log(data);
                    console.log(id_parent_tr);
                    $(data).insertAfter($('table.proceso_auditado_hallazgo tr#' + id_parent_tr + ':last'));
                    $("#" + id).effect("highlight", {}, 1000);
                });
            }




        });
        grid_equipo_auditor({{ $proceso_auditado->id_proceso_auditado }});
        $('#id_auditor').select2();
        $("#grid_equipo_auditor").on('click', '.btn-delete-equipo-auditor', function(e) {
            console.log("eliminar");
            $("#grid_equipo_auditor").html("<img src='{{ asset(" / js / loader.gif ") }}' width='80px' >");
            var id_auditor = $(this).attr('href').replace(/^.*?(#|$)/, '');
            $.get("{{ URL::to('/') }}/proceso_auditado/delete/auditor/{{ $proceso_auditado->id_proceso_auditado }}/" + id_auditor, function(data) {
                grid_equipo_auditor({{ $proceso_auditado->id_proceso_auditado }});
            });
        });
        $("#grid_equipo_auditor").on('click', '.btn-setlider-equipo-auditor', function(e) {
            console.log("eliminar");
            $("#grid_equipo_auditor").html("<img src='{{ asset(" / js / loader.gif ") }}' width='80px' >");
            var id_auditor = $(this).attr('href').replace(/^.*?(#|$)/, '');
            $.get("{{ URL::to('/') }}/proceso_auditado/setlider/auditor/{{ $proceso_auditado->id_proceso_auditado }}/" + id_auditor, function(data) {
                grid_equipo_auditor({{ $proceso_auditado->id_proceso_auditado }});
            });
        });
        $('#btn-agregar-equipo-auditor').on('click', function(e) {
            console.log("test");
            $("#grid_equipo_auditor").html("<img src='{{ asset(" / js / loader.gif ") }}' width='80px' >");
            var id_auditor = $("#id_auditor").val();
            $.get("{{ URL::to('/') }}/proceso_auditado/add/auditor/{{ $proceso_auditado->id_proceso_auditado }}/" + id_auditor, function(data) {
                grid_equipo_auditor({{ $proceso_auditado->id_proceso_auditado }});
                $("#id_auditor_lider").val(true);
            });
        });

        function grid_equipo_auditor(id) {
            if (typeof id != 'undefined') {
                $.get("{{ URL::to('/') }}/proceso_auditado/get/auditor/" + id, function(data) {
                    $('#grid_equipo_auditor').empty();
                    //$("#grid_equipo_auditor").append(data);

                    document.getElementById("grid_equipo_auditor").innerHTML = data;
                    //console.log(data);
                });
            }
        }



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
            $("input, textarea, select").attr('readonly', 'readonly');
        }



		
		
		
	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		

        $('.btn-ejecutar-envio').click(function() {

            var request = $.ajax({
                method: "GET",
                url: "{{ url('config/ejecutar/envio/alerta_a_vencer') }}"
            });
            request.done(function(data) {
                $('#mensaje').html(data);
                $('#mensaje').attr('class', 'alert alert-success');
            });
            request.fail(function(data, textStatus) {
                $('#mensaje').html("Error: " + textStatus);
                $('#mensaje').attr('class', 'alert alert-error');
            });
        });
        $('.link_tab').on('click', function(e) {

            tipo = $(this).attr('href').replace(/^.*?(#|$)/, '');
            tipo = tipo.replace("tab_", "");
            $("#tipo").val(tipo);
            $(".div_subsecretaria_search").hide();
            $(".div_servicio_salud_search").hide();
            $(".div_tipo_centro_responsabilidad").hide();
            $(".div_centro_responsabilidad_search").hide();
            $(".div_departamento_search").hide();
            switch (tipo) {
                case "organismo":
                    //$("#tab_organismo").show();
                    $(".div_subsecretaria_search").hide();
                    // -- validaciones --
                    $('#id_organismo').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "subsecretaria":
                    $(".div_subsecretaria_search").hide();
                    console.log("deveria esconder");
                    // -- validaciones --
                    $('#id_subsecretaria').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "division":
                    $(".div_subsecretaria_search").show();
                    // -- validaciones --
                    $('#id_division').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "seremi":
                    //  case "seremi": para seremi no hay subsecretaria
                    // -- validaciones --
                    $('#id_seremi').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "gabinete":
                    $(".div_subsecretaria_search").show();
                    // -- validaciones --
                    $('#id_gabinete').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "servicio_salud":
                    $(".div_subsecretaria_search").show();
                    // -- validaciones --
                    $('#id_servicio_salud').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "establecimiento":
                    $(".div_servicio_salud_search").show();
                    // -- validaciones --
                    $('#id_establecimiento').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "departamento":
                    $(".div_subsecretaria_search").show();
                    $(".div_tipo_centro_responsabilidad").show();
                    $(".div_centro_responsabilidad_search").show();
                    // -- validaciones --
                    $('#id_departamento').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "unidad":
                    $(".div_subsecretaria_search").show();
                    $(".div_tipo_centro_responsabilidad").show();
                    $(".div_centro_responsabilidad_search").show();
                    $(".div_departamento_search").show();
                    // -- validaciones --
                    $('#id_unidad').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
            }

        });

        function simulateTab(active) {

            var arraytab = ["tab_organismo", "tab_subsecretaria", "tab_division", "tab_seremi", "tab_gabinete", "tab_servicio_salud", "tab_establecimiento", "tab_departamento", "tab_unidad"]
            for (var i = 0; i <= arraytab.lenght; i++) {
                $("#" + arraytab[i]).hide();
            }
            $("#" + active).show();
        }



        $('.link_tab2').on('change', function(e) {


            //simulateTab($(this).val());
            tipo = $(this).val();
            $('#' + tipo).show();
            console.log(tipo);
            tipo = tipo.replace("tab_", "");
            $("#tipo").val(tipo);
            $(".div_subsecretaria_search").hide();
            $(".div_servicio_salud_search").hide();
            $(".div_tipo_centro_responsabilidad").hide();
            $(".div_centro_responsabilidad_search").hide();
            $(".div_departamento_search").hide();
            switch (tipo) {
                case "organismo":
                    //$("#tab_organismo").show();
                    $(".div_subsecretaria_search").hide();
                    // -- validaciones --
                    $('#id_organismo').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "subsecretaria":
                    $(".div_subsecretaria_search").hide();
                    console.log("deveria esconder");
                    // -- validaciones --
                    $('#id_subsecretaria').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "division":
                    $(".div_subsecretaria_search").show();
                    // -- validaciones --
                    $('#id_division').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "seremi":
                    //  case "seremi": para seremi no hay subsecretaria
                    // -- validaciones --
                    $('#id_seremi').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "gabinete":
                    $(".div_subsecretaria_search").show();
                    // -- validaciones --
                    $('#id_gabinete').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "servicio_salud":
                    $(".div_subsecretaria_search").show();
                    // -- validaciones --
                    $('#id_servicio_salud').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "establecimiento":
                    $(".div_servicio_salud_search").show();
                    // -- validaciones --
                    $('#id_establecimiento').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "departamento":
                    $(".div_subsecretaria_search").show();
                    $(".div_tipo_centro_responsabilidad").show();
                    $(".div_centro_responsabilidad_search").show();
                    // -- validaciones --
                    $('#id_departamento').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
                    break;
                case "unidad":
                    $(".div_subsecretaria_search").show();
                    $(".div_tipo_centro_responsabilidad").show();
                    $(".div_centro_responsabilidad_search").show();
                    $(".div_departamento_search").show();
                    // -- validaciones --
                    $('#id_unidad').each(function() {
                        $(this).rules("add", {
                            required: true
                        });
                    });
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
                        $('#id_subsecretaria').append("<option value='" + subCatObj.id_subsecretaria + "'>" +
                            subCatObj.nombre_subsecretaria + "</option>")

                        $('#subsecretaria_search').append("<option value='" + subCatObj.id_subsecretaria + "'>" +
                            subCatObj.nombre_subsecretaria + "</option>")
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
                $.get("{{ url('centro_responsabilidad') }}/get/json?id_subsecretaria=" + id_subsecretaria + "&tipo=" + tipo,
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
            var id_subsecretaria = $("#subsecretaria_search").val(); // e.target.value;
            var tipoCR = $('input[name=tipo_centro_responsabilidad]:checked').val(); //$("#tipo_centro_responsabilidad").val();

            $('#lbl_centro_responsabilidad_search').text(tipoCR);
            tipoCR = tipoCR.toLowerCase();
            $('#centro_responsabilidad_search').empty();
            $('#centro_responsabilidad_search').append("<option value=''>Seleccione</option>");
            $.get("{{ url('centro_responsabilidad') }}/get/json?id_subsecretaria=" + id_subsecretaria + "&tipo=" + tipoCR,
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
                    count = 0;
                    $.each(data, function(index, subCatObj) {
                        //console.log(subCatObj.nombre_establecimiento);
                        count++;
                        $('#id_' + tipo).append("<option value='" + subCatObj.id_establecimiento + "'>" + count + ". " + subCatObj.nombre_establecimiento + "</option>")
                    });
                    console.log(count);    
                }); //get


        }); //onchange

        //----------------------- Servicio Salud  ------------------------
        $('#id_equipo_auditor').on('change', function(e) {
            grid_equipo_auditor(e.target.value);
        }); //onchange



        $("#numero_informe").numeric();
        $('#fecha').datepicker({
            format: "dd-mm-yyyy",
            language: "es",
            autoclose: true,
        });
    }); // document.ready



    /* Functions */

    function grid_equipo_auditor(id) {
        $.get("{{ URL::to('/') }}/equipo_auditor/get/grid/" + id, function(data) {
            $('#grid_equipo_auditor').empty();
            $("#grid_equipo_auditor").html(data);
        });
    }
</script>
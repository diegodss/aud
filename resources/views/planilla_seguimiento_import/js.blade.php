<script>

    $(window).scroll(function () {
        var distanceFromTop = $(this).scrollTop();
        if (distanceFromTop >= $('#header').height()) {
            $('#sticky').addClass('fixed');
        } else {
            $('#sticky').removeClass('fixed');
        }
    });

	function resetDivs(){
		$('#div_import').removeAttr('class');
		$('#div_procesa').removeAttr('class');
		$('#div_compromiso_padre').removeAttr('class');
		$('#div_finalizar_importacion').removeAttr('class');
		
	}
    $(document).ready(function () {

        $('.btn-truncate_proceso_auditado').click(function () {

			resetDivs();
		
            $('#mensaje').html("cargando...");
            url = "{{ url('planilla_seguimiento_import/truncate_proceso_auditado') }}";
            var request = $.ajax({
                method: "GET",
                url: url
            });

            request.done(function (data) {
                $('#mensaje').html(data);
                $('#mensaje').attr('class', 'alert alert-success mensaje_proceso_auditado');
            });

            request.fail(function (data, textStatus) {
                $('#mensaje').html("Error: " + textStatus);
                $('#mensaje').attr('class', 'alert alert-error mensaje_proceso_auditado');
            });

        });
        // ----------------------------------------------------------
        $('#importar').click(function () {

            $('#div_import').show();
            $('#div_import').html("Cargando...");

            file_import = $('#file_import').find(":selected").text();

            url = "{{ url('planilla_seguimiento/excel/import/{file_import}') }}";
            url = url.replace('{file_import}', file_import);
            console.log(file_import);
            var request = $.ajax({
                method: "GET",
                url: url
            });

            request.done(function (data) {
                $('#div_import').html(data);
                $('#div_import').attr('class', 'alert alert-success');
				$("#div_import").animate({ scrollBottom: $('#div_import').prop("scrollHeight")}, 1000);
            });

            request.fail(function (data, textStatus) {
                $('#div_import').html("Error: " + textStatus);
                $('#div_import').attr('class', 'alert alert-error');
            });

        });
        // ----------------------------------------------------------
        $('#set_procesa').click(function () {

            $('#div_procesa').show();
            $('#div_procesa').html("Cargando...");

            url = "{{ url('planilla_seguimiento/excel/procesa') }}";
            var request = $.ajax({
                method: "GET",
                url: url
            });

            request.done(function (data) {
                $('#div_procesa').html(data);
                $('#div_procesa').attr('class', 'alert alert-success');
				$("#div_procesa").animate({ scrollBottom: $('#div_procesa').prop("scrollHeight")}, 1000);
            });

            request.fail(function (data, textStatus) {
                $('#div_procesa').html("Error: " + textStatus);
                $('#div_procesa').attr('class', 'alert alert-error');
            });

        });
        // ----------------------------------------------------------
        $('#set_compromiso_padre').click(function () {

            $('#div_compromiso_padre').show();
            $('#div_compromiso_padre').html("Cargando...");
            url = "{{ url('planilla_seguimiento/excel/compromiso_padre') }}";
            var request = $.ajax({
                method: "GET",
                url: url
            });

            request.done(function (data) {
                $('#div_compromiso_padre').html(data);
                $('#div_compromiso_padre').attr('class', 'alert alert-success');
				$("#div_compromiso_padre").animate({ scrollBottom: $('#div_compromiso_padre').prop("scrollHeight")}, 1000);
            });

            request.fail(function (data, textStatus) {
                $('#div_compromiso_padre').html("Error: " + textStatus + " " + data);
                $('#div_compromiso_padre').attr('class', 'alert alert-error');
            });

        });
        // ----------------------------------------------------------
        $('#finalizar_importacion').click(function () {

            $('#div_finalizar_importacion').show();
            $('#div_finalizar_importacion').html("Cargando...");
            url = "{{ url('planilla_seguimiento_import/finaliza_importacion') }}";
            var request = $.ajax({
                method: "GET",
                url: url
            });

            request.done(function (data) {
                $('#div_finalizar_importacion').html(data);
                $('#div_finalizar_importacion').attr('class', 'alert alert-success');
            });

            request.fail(function (data, textStatus) {
                $('#div_finalizar_importacion').html("Error: " + textStatus);
                $('#div_finalizar_importacion').attr('class', 'alert alert-error');
            });

        });        	
        // ----------------------------------------------------------
		
        $('.open_planilla_seguimiento').click(function () {

            $('#vw_planilla_seguimiento').html("Cargando...");
            var tipo = $(this).data('tipo');
            $('#vw_planilla_seguimiento').html($('#diegao').html());
        });

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
            format: "dd/mm/yyyy",
            language: "es",
            autoclose: true
        });

        $('#plazo_comprometido_fin').datepicker({
            format: "dd/mm/yyyy",
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
            lang: 'en'
            , rules: {
                nombre_region: {required: true}
            }
        });

        //----------------------- Subsecretaria ------------------------
        $('#subsecretaria_search').on('change', function (e) {
            var id_subsecretaria = e.target.value;
            var tipo = 'division';
            //var tipo = tipo.toLowerCase();

            $('#division_search').empty();
            $('#division_search').append("<option value=''>Seleccione</option>");
            $.get("{{ url('centro_responsabilidad') }}/get/json?id_subsecretaria=" + id_subsecretaria + "&tipo=" + tipo,
                    function (data) {
                        $.each(data, function (index, subCatObj) {
                            $('#division_search').append("<option value='" + subCatObj.id_centro_responsabilidad + "'>" + subCatObj.nombre_centro_responsabilidad + "</option>")
                        });
                    }); //get

        }); //onchange

    });


    $(function () {
        var slideToTop = $("<div />");
        slideToTop.html('<i class="fa fa-chevron-up"></i>');
        slideToTop.css({
            position: 'fixed',
            bottom: '20px',
            right: '25px',
            width: '40px',
            height: '40px',
            color: '#eee',
            'font-size': '',
            'line-height': '40px',
            'text-align': 'center',
            'background-color': '#222d32',
            cursor: 'pointer',
            'border-radius': '5px',
            'z-index': '99999',
            opacity: '.7',
            'display': 'none'
        });
        slideToTop.on('mouseenter', function () {
            $(this).css('opacity', '1');
        });
        slideToTop.on('mouseout', function () {
            $(this).css('opacity', '.7');
        });
        $('.wrapper').append(slideToTop);
        $(window).scroll(function () {
            if ($(window).scrollTop() >= 150) {
                if (!$(slideToTop).is(':visible')) {
                    $(slideToTop).fadeIn(500);
                }
            } else {
                $(slideToTop).fadeOut(500);
            }
        });
        $(slideToTop).click(function () {
            $("body").animate({
                scrollTop: 0
            }, 500);
        });
        $(".sidebar-menu li:not(.treeview) a").click(function () {
            var $this = $(this);
            var target = $this.attr("href");
            if (typeof target === 'string') {
                $("body").animate({
                    scrollTop: ($(target).offset().top) + "px"
                }, 500);
            }
        });
    });
</script>
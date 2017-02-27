<script>

    $(window).scroll(function () {
        var distanceFromTop = $(this).scrollTop();
        if (distanceFromTop >= $('#header').height()) {
            $('#sticky').addClass('fixed');
        } else {
            $('#sticky').removeClass('fixed');
        }
    });

    $(document).ready(function () {

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
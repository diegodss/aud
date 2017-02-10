<script>

    $(document).ready(function () {

    $('.alerta-semaforo').click(function () {
    $('#compromiso_vencido').html("Cargando...");
    var tipo = $(this).data('tipo');
    $('#compromiso_vencido').load("{{ URL::to('/') }}/compromiso/vencidos/modal/" + tipo);
    });
    //http://www.chartjs.org/docs/#bar-chart-introduction


    // ======== GRAFICO POR ESTADO GABINETE =======
    var porEstadoGabineteChartCanvas = $("#por_estado_gabinete_chart").get(0).getContext("2d");
    var porEstadoGabineteChart = new Chart(porEstadoGabineteChartCanvas);
    var porEstadoGabineteChartData = {
    labels: {!! $porEstadoLabel !!}
    , datasets: [
    {
    label: "PMG",
            fillColor: "rgb(210, 214, 222)",
            strokeColor: "rgb(210, 214, 222)",
            pointColor: "rgb(210, 214, 222)",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgb(220,220,220)",
            data: {!! $porEstadoGabineteDataPmg !!}
    },
    {
    label: "NO PMG",
            fillColor: "rgba(60,141,188,0.9)",
            strokeColor: "rgba(60,141,188,0.8)",
            pointColor: "#3b8bba",
            pointStrokeColor: "rgba(60,141,188,1)",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(60,141,188,1)",
            data: {!! $porEstadoGabineteDataNoPmg !!}
    }
    ]
    };
    // ======== GRAFICO POR ESTADO SSP =======
    var porEstadoSspChartCanvas = $("#por_estado_ssp_chart").get(0).getContext("2d");
    var porEstadoSspChart = new Chart(porEstadoSspChartCanvas);
    var porEstadoSspChartData = {
    labels: {!! $porEstadoLabel !!}
    , datasets: [
    {
    label: "PMG",
            fillColor: "rgb(210, 214, 222)",
            strokeColor: "rgb(210, 214, 222)",
            pointColor: "rgb(210, 214, 222)",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgb(220,220,220)",
            data: {!! $porEstadoSspDataPmg !!}
    },
    {
    label: "NO PMG",
            fillColor: "rgba(60,141,188,0.9)",
            strokeColor: "rgba(60,141,188,0.8)",
            pointColor: "#3b8bba",
            pointStrokeColor: "rgba(60,141,188,1)",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(60,141,188,1)",
            data: {!! $porEstadoSspDataNoPmg !!}
    }
    ]
    };
    // ======== GRAFICO POR ESTADO REA =======
    var porEstadoRaChartCanvas = $("#por_estado_ra_chart").get(0).getContext("2d");
    var porEstadoRaChart = new Chart(porEstadoRaChartCanvas);
    var porEstadoRaChartData = {
    labels: {!! $porEstadoLabel !!}
    , datasets: [
    {
    label: "PMG",
            fillColor: "rgb(210, 214, 222)",
            strokeColor: "rgb(210, 214, 222)",
            pointColor: "rgb(210, 214, 222)",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgb(220,220,220)",
            data: {!! $porEstadoRaDataPmg !!}
    },
    {
    label: "NO PMG",
            fillColor: "rgba(60,141,188,0.9)",
            strokeColor: "rgba(60,141,188,0.8)",
            pointColor: "#3b8bba",
            pointStrokeColor: "rgba(60,141,188,1)",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(60,141,188,1)",
            data: {!! $porEstadoRaDataNoPmg !!}
    }
    ]
    };
    var porEstadoChartOptions = {
    showScale: true,
            scaleShowGridLines: false,
            scaleGridLineColor: "rgba(0,0,0,.05)",
            scaleGridLineWidth: 1,
            scaleShowHorizontalLines: true,
            scaleShowVerticalLines: true,
            bezierCurve: true,
            bezierCurveTension: 0.3,
            pointDot: false,
            pointDotRadius: 4,
            pointDotStrokeWidth: 1,
            pointHitDetectionRadius: 20,
            datasetStroke: true,
            datasetStrokeWidth: 2,
            datasetFill: true,
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%=datasets[i].label%></li><%}%></ul>",
            maintainAspectRatio: true,
            responsive: true,
            multiTooltipTemplate: "<%= datasetLabel %>: <%= value %>"
    };
    porEstadoGabineteChart.Bar(porEstadoGabineteChartData, porEstadoChartOptions);
    porEstadoSspChart.Bar(porEstadoSspChartData, porEstadoChartOptions);
    porEstadoRaChart.Bar(porEstadoRaChartData, porEstadoChartOptions);
//-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var PieData = {!! $porCondicionOtros_doughnut!!};
    var pieOptions = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
            //String - The colour of each segment stroke
            segmentStrokeColor: "#fff",
            //Number - The width of each segment stroke
            segmentStrokeWidth: 1,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps: 100,
            //String - Animation easing effect
            animationEasing: "easeOutBounce",
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate: true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale: false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: false,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: false,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
            //String - A tooltip template
            multiTooltipTemplate: "<%=value %> <%=label%> users"
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);
    //-----------------
    //- END PIE CHART -
    //-----------------














    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $("#pieChart2").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var PieData = {!! $porEstadoOtros_doughnut!!};
    var pieOptions = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
            //String - The colour of each segment stroke
            segmentStrokeColor: "#fff",
            //Number - The width of each segment stroke
            segmentStrokeWidth: 1,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps: 100,
            //String - Animation easing effect
            animationEasing: "easeOutBounce",
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate: true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale: false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: false,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: false,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
            //String - A tooltip template
            multiTooltipTemplate: "<%=value %> <%=label%> users"
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);
    //-----------------
    //- END PIE CHART -
    //-----------------
    });
</script>
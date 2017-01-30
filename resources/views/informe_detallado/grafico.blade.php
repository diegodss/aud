<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

// Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages': ['corechart', 'bar', 'table']});


    //----------------------------------------------------------
    // google.charts.load('current', {'packages':['table']});
    google.charts.setOnLoadCallback(drawTable1);
    google.charts.setOnLoadCallback(drawTable2);
    google.charts.setOnLoadCallback(cuadro3);

    google.charts.setOnLoadCallback(cuadro5);
    google.charts.setOnLoadCallback(cuadro6);
    google.charts.setOnLoadCallback(cuadro7);
    google.charts.setOnLoadCallback(cuadro8);
    google.charts.setOnLoadCallback(cuadro9);
    /* */

    function drawTable1() {
        var data = new google.visualization.DataTable();
        {!! $columnaGoogleChart_ssp !!}
        data.addRows({!! $tabla_por_estado_ssp !!});
                var table = new google.visualization.Table(document.getElementById('tabla_por_estado_ssp'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
    }

    function drawTable2() {
        var data = new google.visualization.DataTable();
        {!! $columnaGoogleChart_ssp_cuadro2 !!}
        data.addRows({!! $tabla_ssp_cuadro2 !!});
                var table = new google.visualization.Table(document.getElementById('tabla_pmg_condicion'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
    }

    function cuadro3() {
        var data = new google.visualization.DataTable();
        {!! $columnaGoogleChart_cuadro3 !!}
        data.addRows({!! $tabla_cuadro3 !!});
                var table = new google.visualization.Table(document.getElementById('tabla_cuadro3'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
    }
    function cuadro5() {
        var data = new google.visualization.DataTable();
        {!! $columnaGoogleChart_cuadro5 !!}
        data.addRows({!! $tabla_cuadro5 !!});
                var table = new google.visualization.Table(document.getElementById('tabla_cuadro5'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
    }
    function cuadro6() {
        var data = new google.visualization.DataTable();
        {!! $columnaGoogleChart_cuadro6 !!}
        data.addRows({!! $tabla_cuadro6 !!});
                var table = new google.visualization.Table(document.getElementById('tabla_cuadro6'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
    }
    function cuadro7() {
        var data = new google.visualization.DataTable();
        {!! $columnaGoogleChart_cuadro7 !!}
        data.addRows({!! $tabla_cuadro7 !!});
                var table = new google.visualization.Table(document.getElementById('tabla_cuadro7'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
    }
    function cuadro8() {
        var data = new google.visualization.DataTable();
        {!! $columnaGoogleChart_cuadro8 !!}
        data.addRows({!! $tabla_cuadro8 !!});
                var table = new google.visualization.Table(document.getElementById('tabla_cuadro8'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
    }





    //--------------------------------------------------------
</script>
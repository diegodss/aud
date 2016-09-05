<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

// Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages': ['corechart', 'bar']});
// Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
    function drawChart() {

// Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Slices');
    data.addRows({!! $graficoCondicion !!});
// Set chart options
    var options = {'title': 'Condición',
            'width': 480,
            'height':310 };
// Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
    }
//      google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawStuff);
    function drawStuff() {
    var data = new google.visualization.arrayToDataTable({!! $graficoEstado !!});
    var options = {
    title: 'Estado',
            width: 480,
            legend: {position: 'none'},
            chart: {title: 'Estado'},
            bars: 'vertical', // Required for Material Bar Charts.
            axes: {
            x: {
            0: {side: 'bottom', label: 'Porcentaje'} // Top x-axis.
            }
            },
            bar: {groupWidth: "90%"}
    };
    var chart = new google.charts.Bar(document.getElementById('top_x_div'));
    chart.draw(data, options);
    }
    ;
</script>
<script>


    $(document).ready(function () {


    grid_equipo_auditor({{ $equipo_auditor->id_equipo_auditor }});
    $('#id_auditor').select2();
    $('#btn-agregar-equipo-auditor').on('click', function (e) {

    var id_auditor = $("#id_auditor").val();
    $.get("{{ URL::to('/') }}/equipo_auditor/add/auditor/{{ $equipo_auditor->id_equipo_auditor }}/" + id_auditor
            , function (data) {
            grid_equipo_auditor({{ $equipo_auditor->id_equipo_auditor }});
            });
    });
    // Determina si el form es solamente para visualizacion
    var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
    if (show_view) {
    $("input, textarea").attr('readonly', 'readonly');
    }

    // Inicia switch para estado activo/inactivo
    $("[name='fl_status']").bootstrapSwitch();
    //Inicia validacion
    $("form[name=equipo_auditorForm]").validate({
    rules: {
    nombre_equipo_auditor: {required: true}
    }
    });
    // Define si es un formulario de mantenedor o formluario rapido
    $(function () {
    $('form[name=equipo_auditorForm]').submit(function () {
    console.log($("#modal_input").val());
    is_modal = $("#modal_input").val();
    if (is_modal == "sim") {

    $.post($(this).attr('action'), $(this).serialize(), function (json) {
    $("#id_equipo_auditor").append('<option value=' + json['id_equipo_auditor'] + ' selected="selected">' + json['nombre_equipo_auditor'] + '</option>');
    //console.log(json['id_equipo_auditor']);
    $('#myModal').modal('toggle');
    }, 'json');
    return false;
    }

    });
    });
    });
    function grid_equipo_auditor(id) {
    $.get("{{ URL::to('/') }}/equipo_auditor/get/grid/" + id
            , function (data) {
            $('#grid_equipo_auditor').empty();
            $("#grid_equipo_auditor").html(data);
            });
    }


</script>
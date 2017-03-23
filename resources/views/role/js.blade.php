<script>
$(document).ready(function() {


    // ---------- Determina si el form es solamente para visualizacion
    var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
    if (show_view) {
        $("input, textarea").attr('readonly', 'readonly');
    }

    // ---------- Inicia switch para estado activo/inactivo
    $("[name='fl_status']").bootstrapSwitch();

    // ---------- Inicia validacion
    $("form[name=roleForm]").validate({
        rules: {
            role: {
                required: true
            }
        }
    });

    // ---------- Verificacion de usuarios afectados en caso de actualización
    $("#aplicar_role_usuario").on('click', function(e) {

        $("#box_tipo_acceso_customizado").html("<img src='{{ asset("/js/loader.gif ") }}' width='80px' >");

        var id_role = "<?php echo $role->id_role ; ?>";
        $.get("{{ URL::to('/') }}/usuario/get/tipo_acceso/customizado/" + id_role, function(data) {
            console.log(data);
            $("#box_tipo_acceso_customizado").html(data);
        });
    });

    // ---------- Hide o Show div de clave
    $('#lblactive_directory_users').hide();
    $('#active_directory').on('change', function(e) {
        var active_directory = e.target.value;
        if (active_directory == 0) {
            $('#lblPassword').show();
            $('#lblactive_directory_users').hide();
        } else {
            $('#lblPassword').hide();
            $('#lblactive_directory_users').show();
        }
    });  
});

</script>
<script>


    $(document).ready(function () {

        var action = $("input[name=action]").val();
                var field_password = '{{ Form::password('password', array('class' => 'form-control' )) }}';
                var link_password = " <a href='#' id='reset-password'>Haga click para resetear el password</a>";
        // Uso de select2 para campo de usuario
        $('#id').select2();
        // Determina si el form es solamente para visualizacion
        var show_view = <?php echo isset($show_view) ? $show_view : "false"; ?>;
        if (show_view) {
            $("input, textarea, select").attr('readonly', 'readonly');
            $('#groupPassword').hide();
        }

        // Inicia switch para estado activo/inactivo
        $("[name='fl_status']").bootstrapSwitch();
        //Inicia validacion
        $("form[name=usuarioForm]").validate({
            rules: {
                name: {required: true}
                , email: {required: true}
				, password: {required: true}
                
            }
        });
        if (action == 'edit') {
            $("#lblPassword").after(link_password);
        } else if (action == 'create') {
			//console.log(field_password);
            $("#lblPassword").after(field_password);
        }
        $("#reset-password").on("click", function () {

            $("#lblPassword").after(field_password);
            $("#reset-password").hide();
        });
        $('#lblactive_directory_users').hide();
        $('#active_directory').on('change', function (e) {
            var active_directory = e.target.value;
            if (active_directory == 0) {
                $('#groupPassword').show();
                $('#lblactive_directory_users').hide();
            } else {
                $('#groupPassword').hide();
                $('#lblactive_directory_users').show();
            }




        });
        $(".menu_item").on('click', function (e) {
            console.log('Customizado');
            $("#tipo_acceso").val('Customizado');
        });
            $('#id_role').on('change', function (e) {
                    //console.log(e);
                    var id_role = e.target.value;
                            $.get('{{ url('role') }}/get/json?id_role=' + id_role, function (data) {
                                //console.log(data);

                              // $('#idComuna').empty()
                                $.each(data, function (index, subCatObj) {
                          //console.log(subCatObj.Comuna);
                                       // $('#idComuna').append(''+subCatObj.Comuna+'');
//
                        var id_menu = subCatObj.id_menu;
                        var visualizar = subCatObj.visualizar;
                        var agregar = subCatObj.agregar;
                        var editar = subCatObj.editar;
                        var eliminar = subCatObj.eliminar;
                        $('#visualizar' + id_menu).prop('checked', visualizar);
                        $('#agregar' + id_menu).prop('checked', agregar);
                        $('#editar' + id_menu).prop('checked', editar);
                        $('#eliminar' + id_menu).prop('checked', eliminar);
                                    console.log('#visualizar' + id_menu);
                        //$('#idComuna').append("<option value='"+subCatObj.idComuna+"'>"+subCatObj.Comuna+"</option>")


                                }); /**/
                        });
        $("#tipo_acceso").val('Role');
        console.log('Role');
        });
    });
</script>
<script>


    $(document).ready(function () {
// $('#idComuna').empty();
        $('#lblactive_directory_users').hide();
        $('#active_directory').on('change', function (e) {
            var active_directory = e.target.value;
            if (active_directory == 0) {
                $('#lblPassword').show();
                $('#lblactive_directory_users').hide();
            } else {
                $('#lblPassword').hide();
                $('#lblactive_directory_users').show();
            }




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
            });
    });

</script>
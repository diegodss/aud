<script>
    $(document).ready(function () {
        $(".btn_subsecretaria").click(function () {
            var subsecretaria = $(this).attr('href').replace(/^.*?(#|$)/, '');

            $("#subsecretaria").val(subsecretaria);
            $("#informe_detallado_form").submit();

        });
    });
</script>

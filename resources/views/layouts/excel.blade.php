<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{!! $nombre_hoja !!}</title>
    </head>

    <body>
        <table width="100%" border="0" cellspacing="4" cellpadding="4">
            <tr>
                <td colspan="{!! count($datos[1]) !!}"><h3>{!! $titulo !!}</h3></td>
            </tr>
            <tr>
                <td colspan="{!! count($datos[1]) !!}">&nbsp;</td>
            </tr>
            @foreach ($datos as $row_id => $row)
            @if ($row_id == 1)
            <tr>
                @foreach ($row as $key => $value)
                <td width="10">{!! $key !!}</td>
                @endforeach
            </tr>
            @endif
            <tr>
                @foreach ($row as $key => $value)
                <td width="10">{!! $value !!}</td>
                @endforeach
            </tr>
            @endforeach
        </table>
    </body>
</html>


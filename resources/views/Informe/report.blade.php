<!DOCTYPE html>
<html>
<head>
    <title>Tabla de Estadísticas</title>
    <style>
        h2{
            text-align: center;
            font-family: Arial, sans-serif;
            font-weight: bold;
            color: #0277BD; /*#4CAF50;*/
        }
        h6{
            text-align: right;
            font-family: Arial, sans-serif;
            font-weight: bold;
            color: #9E9E9E; /*#4CAF50;*/
        }
        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            font-size: 15px;
            font-family: Arial, sans-serif;
			line-height: 1.5;
			text-align: left;
        }
        th {
            text-align: center;
            padding: 8px;
            border: 1px solid #ccc;
            background-color: #01579B; /*#0000FF#4CAF50;*/
            color: white;
			font-weight: bold;
        }
        td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ccc;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
    @php
        $fechaHora = date("d/m/Y H:i:s");
    @endphp
<div class="container">
    <div class="date">
        <h6>Fecha y hora: {{ $fechaHora }}</h6>
    </div>
    <a href="{{ url('/') }}"><img style="width:140px;border:0px;display: inline!important;" src="{{ config('pleb.mail.top_logo') }}" width="140" border="0"       alt="logo"></a>
</div>
<div>
    <h2>Reporte de Estadísticas</h2>
    <table>
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Reportes en espera</th>
                <th>Reportes aceptados</th>
                <th>Reportes rechazados</th>
                <th>Reportes finalizados</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportes_por_categoria as $categoria)
            <tr>
                <td>{{ $categoria['Categoria'] }}</td>
                <td>{{ $categoria['espera'] }}</td>
                <td>{{ $categoria['aceptados'] }}</td>
                <td>{{ $categoria['rechazados'] }}</td>
                <td>{{ $categoria['finalizados'] }}</td>
                <td>{{ $categoria['total'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

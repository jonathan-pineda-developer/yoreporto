<div class="container">
    <h1>Reporte de Estadísticas</h1>

    <table class="table">
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
                <td>{{ $categoria['reportes en espera'] }}</td>
                <td>{{ $categoria['reportes aceptados'] }}</td>
                <td>{{ $categoria['reportes rechazados'] }}</td>
                <td>{{ $categoria['reportes finalizados'] }}</td>
                <td>{{ $categoria['total'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
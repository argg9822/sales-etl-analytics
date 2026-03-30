@extends('main')

@section('title', 'Listado de archivos importados')

@section('content')

<div class="imports-results">
    <table>
        <thead>
            <tr>
                <th>Archivo</th>
                <th>Fecha de carga</th>
                <th>Total de registros</th>
                <th>Registros procesados</th>
                <th>Errores</th>
                <th>Estado</th>
                <th>...</th>
            </tr>
        </thead>
        <tbody id="imports-table-body">
            
        </tbody>
    </table>
</div>

@endsection
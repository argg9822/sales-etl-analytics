@extends('main')

@section('title', 'Importar CSV de Ventas')

@section('content')

<div class="summary-grid">
    <div class="summary-card card-total">
        <span class="label">Total Registros</span>
        <span class="value">{{ $import->total_records }}</span>
    </div>
    <div class="summary-card card-success">
        <span class="label">Exitosos</span>
        <span class="value">{{ $import->processed_records }}</span>
    </div>
    <div class="summary-card card-errors">
        <span class="label">Errores</span>
        <span class="value">{{ count($import->errors) }}</span>
    </div>
    
</div>

<div class="error-log-container">
    <div class="error-log-header">
        <h4 style="margin:0; color: #ff8fa3; font-size: 14px;">Detalle de Errores</h4>
        <input type="text" class="error-search" placeholder="Buscar por fila o mensaje...">
    </div>
    
    <div class="error-scroll-area">
        <table class="error-mini-table">
            <thead>
                <tr>
                    <th>Fila</th>
                    <th>Descripción del Error</th>
                </tr>
            </thead>
            <tbody>
                @if(count($import->errors) > 0)
                    @foreach($import->errors as $error)
                        <tr>
                            <td class="row-number">#{{ $error->row_number }}</td>
                            <td class="error-msg">{{ $error->error_message }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="text-center">No se han encontrado errores en esta importación.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection
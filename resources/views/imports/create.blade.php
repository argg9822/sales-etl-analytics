@extends('main')

@section('title', 'Importar CSV de Ventas')

@section('content')

<h1>Carga de archivo CSV de ventas</h1>
<h3>Sube un archivo CSV para importar los datos de ventas</h3>

<form method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="container-dropzone upload-action">
        <i class="fa-solid fa-cloud-arrow-up fa-2xl"></i>
        <div class="mt-3">
            <span>Arrastra o selecciona un archivo CSV</span>
        </div>

        <span id="file-name"></span>
    </div>

    <div class="text-center hr"></div>

    <div class="flex-center">
        <button type="button" class="btn btn-secondary upload-action">Seleccionar archivo</button>
        <input type="file" class="form-control" id="file" name="csv_files" hidden>
    </div>
    
    <button type="submit" class="btn btn-primary mt-3" disabled>Cargar</button>
    <div class="alert alert-danger" role="alert" id="error-message" style="display: none;"></div>
    <div class="alert alert-success" role="alert" id="success-message" style="display: none;"></div>
</form>

@endsection

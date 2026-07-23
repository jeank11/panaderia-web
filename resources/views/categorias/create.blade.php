<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h2>Nueva Categoría</h2>

    <form action="{{ route('categorias.store') }}" method="POST">

        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">
            Guardar
        </button>

        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
            Cancelar
        </a>

    </form>

</div>

</body>
</html>
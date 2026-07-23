<div class="row">

    <div class="col-md-4 mb-3">
        <label class="form-label">Código</label>
        <input type="text"
               name="codigo"
               class="form-control @error('codigo') is-invalid @enderror"
               value="{{ old('codigo', $producto->codigo ?? '') }}">

        @error('codigo')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-8 mb-3">
        <label class="form-label">Nombre</label>
        <input type="text"
               name="nombre"
               class="form-control @error('nombre') is-invalid @enderror"
               value="{{ old('nombre', $producto->nombre ?? '') }}">

        @error('nombre')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Categoría</label>

        <select name="categoria_id"
                class="form-select @error('categoria_id') is-invalid @enderror">

            <option value="">Seleccione una categoría</option>

            @foreach($categorias as $categoria)

                <option value="{{ $categoria->id }}"
                    {{ old('categoria_id', $producto->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>

                    {{ $categoria->nombre }}

                </option>

            @endforeach

        </select>

        @error('categoria_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Precio Compra</label>

        <input type="number"
               step="0.01"
               name="precio_compra"
               class="form-control @error('precio_compra') is-invalid @enderror"
               value="{{ old('precio_compra', $producto->precio_compra ?? '') }}">

        @error('precio_compra')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Precio Venta</label>

        <input type="number"
               step="0.01"
               name="precio_venta"
               class="form-control @error('precio_venta') is-invalid @enderror"
               value="{{ old('precio_venta', $producto->precio_venta ?? '') }}">

        @error('precio_venta')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Stock</label>

        <input type="number"
               name="stock"
               class="form-control @error('stock') is-invalid @enderror"
               value="{{ old('stock', $producto->stock ?? '') }}">

        @error('stock')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Stock mínimo</label>

        <input type="number"
               name="stock_minimo"
               class="form-control @error('stock_minimo') is-invalid @enderror"
               value="{{ old('stock_minimo', $producto->stock_minimo ?? '') }}">

        @error('stock_minimo')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

</div>
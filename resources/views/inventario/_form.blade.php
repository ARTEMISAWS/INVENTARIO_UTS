{{-- resources/views/inventario/_form.blade.php --}}
{{-- Campo Nombre --}}
<div>
    <label for="nombre" class="block text-sm font-medium">Nombre del Artículo</label>
    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $articulo->nombre ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
</div>

{{-- Campo Código UTS --}}
<div>
    <label for="codigo_uts" class="block text-sm font-medium">Código UTS</label>
    <input type="text" name="codigo_uts" id="codigo_uts" value="{{ old('codigo_uts', $articulo->codigo_uts ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
</div>

{{-- Campo Descripción --}}
<div>
    <label for="descripcion" class="block text-sm font-medium">Descripción</label>
    <textarea name="descripcion" id="descripcion" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">{{ old('descripcion', $articulo->descripcion ?? '') }}</textarea>
</div>

{{-- Campos Marca y Modelo (en una fila) --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="marca" class="block text-sm font-medium">Marca</label>
        <input type="text" name="marca" id="marca" value="{{ old('marca', $articulo->marca ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
    </div>
    <div>
        <label for="modelo" class="block text-sm font-medium">Modelo</label>
        <input type="text" name="modelo" id="modelo" value="{{ old('modelo', $articulo->modelo ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
    </div>
</div>

{{-- Campo Número de Serie --}}

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="numero_serie" class="block text-sm font-medium">Número de Serie</label>
        <input type="text" name="numero_serie" id="numero_serie" value="{{ old('numero_serie', $articulo->numero_serie ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
    </div>
    
    <div>
        <label for="calcomania" class="block text-sm font-medium">Calcomania</label>
        <input type="text" name="calcomania" id="calcomania" value="{{ old('calcomania', $articulo->calcomania ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
    </div>
</div>


{{-- Campos Categoría y Ubicación (en una fila) --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="categoria_id" class="block text-sm font-medium">Categoría</label>
        <select name="categoria_id" id="categoria_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
            <option value="0">
                Seleccionar
            </option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('categoria_id', $articulo->categoria_id ?? '') == $categoria->id)>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="ubicacion_id" class="block text-sm font-medium">Ubicación</label>
        <select name="ubicacion_id" id="ubicacion_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
            <option value="0">
                Seleccionar
            </option>    
            @foreach($ubicaciones as $ubicacion)
                <option value="{{ $ubicacion->id }}" @selected(old('ubicacion_id', $articulo->ubicacion_id ?? '') == $ubicacion->id)>
                    {{ $ubicacion->nombre }}
                </option>
            @endforeach
        </select>
    </div>
</div>

{{-- Si estamos editando, mostramos el campo de estado --}}
@isset($articulo)
<div>
    <label for="estado" class="block text-sm font-medium">Estado</label>
    <select name="estado" id="estado" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
        <option value="disponible" @selected($articulo->estado == 'disponible')>Disponible</option>
        <option value="prestado" @selected($articulo->estado == 'prestado')>Prestado</option>
        <option value="en_mantenimiento" @selected($articulo->estado == 'en_mantenimiento')>En Mantenimiento</option>
        <option value="de_baja" @selected($articulo->estado == 'de_baja')>De Baja</option>
    </select>
</div>
@endisset


{{-- Botón de Guardar --}}
<div class="flex justify-end pt-4">
    <a href="{{ route('inventario.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">Cancelar</a>
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
        Guardar
    </button>
</div>
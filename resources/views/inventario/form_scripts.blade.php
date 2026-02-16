<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoriaSelect = document.getElementById('categoria_id');
        const subcategoriaSelect = document.getElementById('subcategoria_id');
        const subcategoriaOptions = Array.from(subcategoriaSelect.options);

        function filterSubcategorias() {
            const selectedCategoriaId = categoriaSelect.value;

            // Ocultar todas primero (excepto la opción por defecto)
            subcategoriaSelect.value = "";

            subcategoriaOptions.forEach(option => {
                if (option.value === "") return; // Mantener placeholder

                if (selectedCategoriaId && option.dataset.categoriaId == selectedCategoriaId) {
                    option.style.display = 'block';
                    option.disabled = false;
                } else {
                    option.style.display = 'none';
                    option.disabled = true;
                }
            });

            // Si hay un valor viejo (old input o edición), intentar restaurarlo si coincide con la categoría
            const oldSubcategoriaId = "{{ old('subcategoria_id', $articulo->subcategoria_id ?? '') }}";
            if (oldSubcategoriaId) {
                const matchingOption = subcategoriaOptions.find(opt => opt.value == oldSubcategoriaId && opt.dataset.categoriaId == selectedCategoriaId);
                if (matchingOption) {
                    subcategoriaSelect.value = oldSubcategoriaId;
                }
            }
        }

        // Event listener
        categoriaSelect.addEventListener('change', filterSubcategorias);

        // Auto-seleccionar categoría si falta pero tenemos subcategoría (Corrección para datos antiguos)
        if (!categoriaSelect.value) {
            const oldSubId = "{{ old('subcategoria_id', $articulo->subcategoria_id ?? '') }}";
            if (oldSubId) {
                const subOption = subcategoriaOptions.find(opt => opt.value == oldSubId);
                if (subOption && subOption.dataset.categoriaId) {
                    categoriaSelect.value = subOption.dataset.categoriaId;
                }
            }
        }

        // Ejecutar al cargar (para edición o old input)
        filterSubcategorias();
    });
</script>
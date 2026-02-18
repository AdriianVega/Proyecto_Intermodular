<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Seguro que quieres realizar esta operación?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Función que lanza el modal y gestiona el clic en eliminar
    function eliminar(id)
    {
        // Creamos la instancia del modal de Bootstrap
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        
        // Mostramos el modal
        modal.show();

        // Al pulsar en eliminar, redirigimos a la misma página mandando el id
        document.getElementById('confirmDeleteBtn').onclick = () => {
            // Sacamos el nombre del archivo actual para la URL
            const archivoActual = window.location.pathname.split("/").pop();

            // Mandamos el parámetro eliminar por GET
            window.location.href = archivoActual + '?eliminar=' + id;

            modal.hide();
        };
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

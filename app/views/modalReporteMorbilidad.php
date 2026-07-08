<div class="action-card">
    <h3 class="action-card__title">Generar Reporte de Morbilidad</h3>
    <form class="action-card__form--registrar-usuarios" action="index.php" method="POST" target="_blank" id="formReporteMorbilidad">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <input type="hidden" name="form" value="generar_reporte_morbilidad">

        <div class="action-card__form--grid">
            <label class="action-card__label" for="reporte_fecha_inicio">Fecha de Inicio
                <input type="date" id="reporte_fecha_inicio" name="fecha_inicio" class="action-card__input" required>
            </label>

            <label class="action-card__label" for="reporte_fecha_fin">Fecha de Fin
                <input type="date" id="reporte_fecha_fin" name="fecha_fin" class="action-card__input" required>
            </label>
        </div>

        <div class="action-card__button-grid" style="margin-top: 20px;">
            <button type="button" class="action-card__button action-card__button--red" onclick="const m = document.getElementById('modalReporteMorbilidad'); m.style.opacity = 0; setTimeout(() => m.close(), 150);">Cancelar</button>
            <button type="submit" class="action-card__button" style="background-color: #0284c7;">Generar Reporte (PDF)</button>
        </div>
    </form>
</div>
<svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalReporteMorbilidad" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
    <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
</svg>
<script>
document.getElementById('formReporteMorbilidad').addEventListener('submit', function(e) {
    const inicio = document.getElementById('reporte_fecha_inicio').value;
    const fin = document.getElementById('reporte_fecha_fin').value;
    if (inicio && fin && new Date(inicio) > new Date(fin)) {
        e.preventDefault();
        alert('La fecha de inicio no puede ser posterior a la fecha de fin.');
    }
});
</script>

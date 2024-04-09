<div class="contenedor restablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitios.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo Password</p>

        <form class="formulario" action="/restablecer" method="POST">
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Tu Password">
            </div>
            <input type="submit" class="boton" value="Guardar Password">
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
            <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
        </div>
    </div>
</div>
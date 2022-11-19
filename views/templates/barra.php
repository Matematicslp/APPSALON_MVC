<div class="barra">
    <p class="descripcion-pagina">Hola: <?php echo $nombre ?? ''; ?></p>
    <a class="boton" href="/logout">Cerrar sesión</a>
</div>

<?php if(isset($_SESSION['admin'])) { ?>
    <div class="barra-servicios">
        <a class="boton" href="/admin">Asistencia</a>
        <a class="boton" href="/servicios">Alumnos</a>
        <a class="boton" href="/servicios/crear">Administración</a>
    </div>
<?php } ?>

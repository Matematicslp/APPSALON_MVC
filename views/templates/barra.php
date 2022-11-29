<?php if($_SESSION['rol'] <= "4") { ?>
    <div class="barra-servicios">
        <a class="boton" href="/asistencia">Asistencia</a>
        <a class="boton" href="/servicios">Alumnos</a>
        <a class="boton" href="/servicios/crear">Usuarios</a>
    </div>
<?php } ?>

<div class="barra">
    <p class="descripcion-pagina">Hola: <?php echo $nombre ?? ''; ?></p>
    <a class="boton" href="/logout">Cerrar sesión</a>
</div>

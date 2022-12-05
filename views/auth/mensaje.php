<h1 class="nombre-pagina">Confirma tu cuenta</h1>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Se ha enviado un código a tu correo.</p>
        <?php include_once __DIR__ .'/../templates/alertas.php'; ?>
        <form class="formulario" method="POST" action="/mensaje">
        <div class="campo">
                <label for="codigo">Código:</label>
                <input
                    type="text"
                    name="codigo"
                    placeholder="Escribe tu código..."
                    name="codigo"
                />
            </div>
                <input type="hidden" name="email" value="<?php echo $email; ?>" />
            <input type="submit" class="boton" value="Comprobar">

    </div> <!-- .contenedor-sm -->
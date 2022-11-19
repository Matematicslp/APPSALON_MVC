<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;
    public $host;
    public $port;
    public $user;
    public $pass;
    public $servidor;

    public function __construct($email, $nombre, $token) {

        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
        $this->host = 'smtp.hostinger.com';
        $this->port = 465;
        $this->user = 'escandon@matemaatics.click';
        $this->pass = 'Isaac2901!';
        $this->servidor = 'localhost';
    }

    public function enviarConfirmacion($email) {
        // Crear el objeto de Email

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $this->host;
        $mail->SMTPAuth = true;
        $mail->Port = $this->port;
        $mail->Username = $this->user;
        $mail->Password = $this->pass;
        $mail->setFrom('admin@secundariaescandon.com');
        $mail->addAddress($email, 'secundariaescandon.com');
        $mail->Subject = "Confirma tu cuenta";

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ". $this->nombre ."</strong> Has creado tu cuenta en AppSalon, sólo debes confirmarla en el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://<?php echo $this->servidor; ?>/confirmar-cuenta?token=" . $this->token . "'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el email
        $mail->send();
    }

    public function enviarInstrucciones($email) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $this->host;
        $mail->SMTPAuth = true;
        $mail->Port = $this->port;
        $mail->Username = $this->user;
        $mail->Password = $this->pass;
        $mail->setFrom('admin@secundariaescandon.com');
        $mail->addAddress($email, 'secundariaescandon.com');
        $mail->Subject = "Reestablece tu password";

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ". $this->nombre ."</strong> Has solicitado reestablecer tu password.</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://<?php echo $this->servidor; ?>/recuperar?token=" . $this->token . "'>Reestablecer password</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el email
        $mail->send();
    }
}

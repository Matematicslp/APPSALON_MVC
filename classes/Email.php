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
        $this->user = 'escandon@matematics.click';
        $this->pass = 'Isaac2901!';
        $this->servidor = $_SERVER['SERVER_NAME'];
    }

    public function enviarConfirmacion() {
        // Crear el objeto de Email

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = $this->host;
        $mail->SMTPAuth = true;
        $mail->Port = $this->port;
        $mail->Username = $this->user;
        $mail->Password = $this->pass;
        $mail->SMTPSecure = 'ssl';
        $mail->setFrom('escandon@matematics.click','secundariaescandon.com');
        $mail->addAddress($this->email);
        $mail->Subject = "Confirma tu cuenta";

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ". $this->nombre ."</strong> Has creado tu cuenta en el SCE Escandón, sólo debes confirmarla,</p>";
        $contenido .= "<p>para ello debes escribir el siguiente código: <strong>".$this->token."</strong> en la aplicación</p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el email
        $mail->send();
    }

    public function enviarInstrucciones() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = $this->host;
        $mail->SMTPAuth = true;
        $mail->Port = $this->port;
        $mail->Username = $this->user;
        $mail->Password = $this->pass;
        $mail->SMTPSecure = 'ssl';
        $mail->setFrom('escandon@matematics.click','secundariaescandon.com');
        $mail->addAddress($this->email);
        $mail->Subject = "Reestablece tu password";

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ". $this->nombre ."</strong> Parece que has olvidado tu password, debes crear uno nuevo,</p>";
        $contenido .= "<p>para ello debes escribir el siguiente código: <strong>".$this->token."</strong> en la aplicación</p>";
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el email
        $mail->send();
    }
}

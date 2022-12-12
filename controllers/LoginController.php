<?php
namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;


    class LoginController {
        public static function login(Router $router) {
            $alertas = [];

            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $auth = new Usuario($_POST);

                $alertas = $auth->validarLogin();

                if(empty($alertas)) {
                    // Comprobar que exista el usuario
                    $usuario = Usuario::where('email', $auth->email);

                    if($usuario) {
                        if($usuario->comprobarPasswordAndVerificado($auth->password)) {
                            // Autenticar el usuario
                            session_start();
                            $_SESSION['id'] = $usuario->id;
                            $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apaterno;
                            $_SESSION['email'] = $usuario->email;
                            $_SESSION['login'] = true;

                            // Redireccionamiento
                            if($usuario->rol === "1" || $usuario->rol === "2") {
                                $_SESSION['rol'] = $usuario->rol ?? null;
                                header('Location: /asistencia');
                            } elseif($usuario->rol === "3") {
                                $_SESSION['rol'] = $usuario->rol ?? null;
                                header('Location: /control');
                            } elseif($usuario->rol === "4") {
                                $_SESSION['rol'] = $usuario->rol ?? null;
                                header('Location: /prefectura');
                            } elseif($usuario->rol === "5") {
                                $_SESSION['rol'] = $usuario->rol ?? null;
                                header('Location: /docente');
                            } elseif($usuario->rol === "6")  {
                                $_SESSION['rol'] = $usuario->rol ?? null;
                                header('Location: /inicio');
                            } else {
                                header('Location: /');
                            }
                        }
                    } else {
                        Usuario::setAlerta('error', 'Usuario no encontrado');
                    }
                }
            }

            $alertas = Usuario::getAlertas();

            $router->render('auth/login', [
                'alertas' => $alertas
            ]);
        }

        public static function logout() {
            session_start();

            $_SESSION = [];

            header('Location: /');
        }

        public static function olvide(Router $router) {

            $alertas = [];

            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $auth = new Usuario($_POST);
                $alertas = $auth->validarEmail();

                if(empty($alertas)) {
                    $usuario = Usuario::where('email', $auth->email);

                    if($usuario && $usuario->confirmado === "1") {
                        // Generar un token
                        $usuario->crearToken();
                        $usuario->guardar();

                        // Enviar el email
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarInstrucciones();

                        // Alerta de exito
                        Usuario::setAlerta('exito','Hemos enviado las instrucciones a tu email');
                        session_start();
                        $_SESSION['email'] = $usuario->email;
                        header('Location: /mensaje');
                    } else {
                        Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                    }
                }
            }

            $alertas = Usuario::getAlertas();

            $router->render('auth/olvide-password', [
                'alertas' => $alertas
            ]);
        }

        public static function recuperar(Router $router) {
            $alertas = [];
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = s($_POST['email']);
            } else {
                session_start();
                $email = s($_SESSION['email']);
                if(!$email) header('Location: /');
            }

            // Encontrar al usuario con el email
            $usuario = Usuario::where('email', $email);
            if(empty($usuario)) {
                // No se encontró el usuario con ese email
                Usuario::setAlerta('error', 'Usuario no encontrado');
            } else {
                // Confirmar la cuenta
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
                    // Añadir el nuevo password
                    $usuario->sincronizar($_POST);
    
                    // Validar el password
                    $alertas = $usuario->validarPassword();
    
                    if(empty($alertas)) {
                        // Hashear el nuevo password
                        $usuario->hashPassword();
                        $usuario->token = null;
    
                        // Guardar el usuario en la BD
                        $usuario->guardar();
                        $_SESSION = [];
    
                        // Redireccionar
                        header('Location: /');
                    }
                }
            }
            $alertas = Usuario::getAlertas();
            $router->render('auth/recuperar-password', [
                'alertas' => $alertas,
                'email' => $email
            ]);
        }

        public static function crear(Router $router) {
            $usuario = new Usuario();

            // Alertas vacías
            $alertas = [];
            if($_SERVER['REQUEST_METHOD'] === 'POST') {

                $usuario->sincronizar($_POST);
                $alertas = $usuario->validarNuevaCuenta();

                // Revisar que alertas esté vacío
                if(empty($alertas)) {
                    $existeUsuario = Usuario::where('email', $usuario->email);
                    if($existeUsuario) {
                        Usuario::setAlerta('error','El usuario ya está registrado');
                        $alertas = Usuario::getAlertas();
                    } else {
                        // Hashear el password
                        $usuario->hashPassword();

                        // Generar el token
                        $usuario->crearToken();

                        // Crear un nuevo usuario
                        $resultado = $usuario->guardar();

                        // Enviar Email
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarConfirmacion();

                        if($resultado) {
                            session_start();
                            $_SESSION['email'] = $usuario->email;
                            header('Location: /mensaje');
                        }
                    }
                }
                //debuguear($existeUsuario);
            }
            $router->render('auth/crear-cuenta', [
                'usuario' => $usuario,
                'alertas' => $alertas
            ]);
        }

        public static function mensaje(Router $router) {
            $nuevo = null;
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = s($_POST['email']);
            } else {
                $email = s($_SESSION['email']);
                if(!$email) header('Location: /');
            }

            // Encontrar al usuario con el email
            $usuario = Usuario::where('email', $email);
            if(empty($usuario)) {
                // No se encontró el usuario con ese email
                Usuario::setAlerta('error', 'Usuario no encontrado');
            } else {
                if($usuario->confirmado === "0") $nuevo = 1;
                // Confirmar la cuenta
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $token = s($_POST['codigo']);
                    if($usuario->token === $token) {
                        $usuario->token = null;
                        if($nuevo) {
                            $usuario->confirmado = 1;
                            $usuario->guardar();
                            $_SESSION = [];
                            header('Location: /confirmar');
                        } else {
                            header('Location: /recuperar');
                        }
                    } else {
                        Usuario::setAlerta('error', 'Código incorrecto');
                    }
                }
            }
            $alertas = Usuario::getAlertas();

            // Render a la vista
            $router->render('auth/mensaje', [
                'alertas' => $alertas,
                'email' => $email
            ]);
        }

        public static function confirmar(Router $router) {


            $router->render('auth/confirmar', [

            ]);
        }
    }

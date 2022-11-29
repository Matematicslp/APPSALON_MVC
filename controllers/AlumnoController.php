<?php

namespace Controllers;
use MVC\Router;
use Model\Alumno;

class AlumnoController {
    public static function index(Router $router) {
        if(!$_SESSION) { session_start(); }

        isAuth();
        isAdmin();

        $alertas = [];
        $alumno = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alumnoTemp = new Alumno($_POST);
            $alertas = $alumnoTemp->validarQR();

            if(empty($alertas)) {
                $alumno = Alumno::where('qr', $alumnoTemp->qr);

                if($alumno) {
                    // Guarda la asistencia
                    debuguear($alumno);
                    Alumno::setAlerta('exito', 'Revisa tu email');
                } else {
                    Alumno::setAlerta('error', 'El código no existe en la Base de Datos');
                }
            }
        }

        $alertas = Alumno::getAlertas();

        $alumnos = Alumno::all();

        $router->render('alumno/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id'],
            'rol' => $_SESSION['rol'],
            'alumnos' => $alumnos,
            'alertas' => $alertas
        ]);
    }
}
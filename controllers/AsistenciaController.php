<?php

namespace Controllers;
use MVC\Router;
use Model\Asistencia;
use Model\Alumno;

class AsistenciaController {
    public static function index(Router $router) {
        if(!$_SESSION) { session_start(); }

        isAuth();
        isAdmin();

        $alertas = [];
        $alumno = [];
        $asistencia = new Asistencia();
        $fecha = Date('Y-m-d');
        $hora = Date('H:m:s', strtotime('-6 hours'));

        $asistencias = Asistencia::all();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alumnoTemp = new Alumno($_POST);
            $alertas = $alumnoTemp->validarQR();

            if(empty($alertas)) {
                $alumno = Alumno::where('qr', $alumnoTemp->qr);
                if($alumno) {
                    // Guarda la asistencia
                    $alumnoId = intval($alumno->id);
                    $usuarioId = intval($_SESSION['id']);
                    $asistencia = $asistencia->crearAsistencia($alumnoId, $usuarioId, $fecha, $hora);
                    //debuguear($asistencia);
                    $asistencia->guardar();
                    Alumno::setAlerta('exito', 'Asistencia registrada');
                } else {
                    Alumno::setAlerta('error', 'El código no existe en la Base de Datos');
                }
            }
        }

        $alertas = Alumno::getAlertas();

        $alumnos = Alumno::all();

        $router->render('asistencia/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id'],
            'rol' => $_SESSION['rol'],
            'asistencias' => $asistencias,
            'alumno' => $alumno,
            'alumnos' => $alumnos,
            'alertas' => $alertas
        ]);
    }
}

// use Model\Servicio;

// class ServicioController {
//     public static function index(Router $router) {
//         if(!$_SESSION) { session_start(); }

//         isAdmin();

//         $servicios = Servicio::all();

//         $router->render('servicios/index', [
//             'nombre' => $_SESSION['nombre'],
//             'servicios' => $servicios
//         ]);
//     }

//     public static function crear(Router $router) {
//         if(!$_SESSION) { session_start(); }
//         isAdmin();
//         $servicio = new Servicio;
//         $alertas = [];

//         if($_SERVER['REQUEST_METHOD'] === 'POST') {
//             $servicio->sincronizar($_POST);

//             $alertas = $servicio->validar();

//             if(empty($alertas)) {
//                 $servicio->guardar();
//                 header('Location: /servicios');
//             }
//         }
//         $router->render('servicios/crear', [
//             'nombre' => $_SESSION['nombre'],
//             'servicio' => $servicio,
//             'alertas' => $alertas
//         ]);
//     }

//     public static function actualizar(Router $router) {
//         if(!$_SESSION) { session_start(); }
//         isAdmin();

//         $id = is_numeric($_GET['id']);
//         if(!$id) return;
//         $servicio = Servicio::find($_GET['id']);
//         $alertas = [];

//         if($_SERVER['REQUEST_METHOD'] === 'POST') {
//             $servicio->sincronizar($_POST);

//             $alertas = $servicio->validar();

//             if(empty($alertas)) {
//                 $servicio->guardar();
//                 header('Location: /servicios');
//             }
//         }
//         $router->render('servicios/actualizar', [
//             'nombre' => $_SESSION['nombre'],
//             'servicio' => $servicio,
//             'alertas' => $alertas
//         ]);
//     }

//     public static function eliminar() {
//         if(!$_SESSION) { session_start(); }
//         isAdmin();

//         if($_SERVER['REQUEST_METHOD'] === 'POST') {
//             $id = $_POST['id'];
//             $servicio = Servicio::find($id);
//             $servicio->eliminar();
//             header('Location: /servicios');
//         }
//     }
// }
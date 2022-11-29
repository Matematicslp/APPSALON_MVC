<?php
namespace Model;

class Asistencia extends ActiveRecord {
    protected static $tabla = 'asistencia';
    protected static $columnasDB = ['id','fecha','hora','alumnoId','usuarioId','estado'];

    public $id;
    public $fecha;
    public $hora;
    public $alumnoId;
    public $usuarioId;
    public $estado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->alumnoId = $args['alumnoId'] ?? 0;
        $this->usuarioId = $args['usuarioId'] ?? 0;
        $this->estado = $args['estado'] ?? 0;
    }

    public function existeAlumno() {
        $query = "SELECT * FROM ". self::$tabla . " WHERE alumnoId = '" . $this->alumnoId . "' LIMIT 1 ";
        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = 'El alumno ya está registrado';
        }
        return $resultado;
    }

    public function crearAsistencia($alumno, $usuario, $fecha, $hora) {
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->alumnoId = $alumno;
        $this->usuarioId = $usuario;
        $this->estado = 1;
        return $this;
    }
}
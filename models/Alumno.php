<?php

namespace Model;

class Alumno extends ActiveRecord {
    protected static $tabla = 'alumnos';
    protected static $columnasDB = ['id','grado','grupo','nl','apaterno','amaterno',
    'nombre','qr','curp','ncontrol','turno','eco','sexo','rol','foto'];

    public $id;
    public $grado;
    public $grupo;
    public $nl;
    public $apaterno;
    public $amaterno;
    public $nombre;
    public $qr;
    public $curp;
    public $ncontrol;
    public $turno;
    public $eco;
    public $sexo;
    public $rol;
    public $foto;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->grado = $args['grado'] ?? 0;
        $this->grupo = $args['grupo'] ?? 0;
        $this->nl = $args['nl'] ?? 0;
        $this->apaterno = $args['apaterno'] ?? '';
        $this->amaterno = $args['amaterno'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->qr = $args['qr'] ?? '';
        $this->curp = $args['curp'] ?? '';
        $this->ncontrol = $args['ncontrol'] ?? 0;
        $this->turno = $args['turno'] ?? '';
        $this->eco = $args['eco'] ?? '';
        $this->sexo = $args['sexo'] ?? '';
        $this->rol = $args['rol'] ?? 6;
        $this->foto = $args['foto'] ?? '';
    }

    public function existeQR() {
        $query = "SELECT * FROM ". self::$tabla . " WHERE qr = '" . $this->qr . "' LIMIT 1 ";
        $resultado = self::$db->query($query);

        if(!$resultado->num_rows) {
            self::$alertas['error'][] = 'No se encuentra el Código QR';
        }
        return $resultado;
    }

    public function validarQR() {
        if(!$this->qr) {
            self::$alertas['error'][] = "El Código QR es obligatorio";
        }
        return self::$alertas;
    }
}
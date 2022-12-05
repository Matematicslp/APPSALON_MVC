<?php
namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apaterno', 'amaterno', 'email',
    'password','telefono', 'rol','confirmado','token', 'a1Id','a2Id','a3Id','a4Id'];

    public $id;
    public $nombre;
    public $apaterno;
    public $amaterno;
    public $email;
    public $password;
    public $telefono;
    public $rol;
    public $confirmado;
    public $token;
    public $a1Id;
    public $a2Id;
    public $a3Id;
    public $a4Id;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apaterno = $args['apaterno'] ?? '';
        $this->amaterno = $args['amaterno'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->rol = $args['rol'] ?? '6';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
        $this->a1Id = $args['a1Id'] ?? 0;
        $this->a2Id = $args['a2Id'] ?? 0;
        $this->a3Id = $args['a3Id'] ?? 0;
        $this->a4Id = $args['a4Id'] ?? 0;
    }

    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if(!$this->apaterno) {
            self::$alertas['error'][] = 'El apellido paterno es obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        if(!$this->telefono) {
            self::$alertas['error'][] = 'El número de teléfono es obligatorio';
        }
        return self::$alertas;
    }

    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio";
        }
        if(!$this->password) {
            self::$alertas['error'][] = "El password es obligatorio";
        }
        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio";
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Email no es válido';
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = "El password es obligatorio";
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe tener al menos 6 caracteres";
        }
        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = "SELECT * FROM ". self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1 ";
        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = 'El usuario ya está registrado';
        }
        return $resultado;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = strval(rand(123456,999999));
    }

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);

        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = "passowrd incorrecto o tu cuenta no está confirmada";
        } else {
            return true;
        }
    }
}
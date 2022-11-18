<?php

 $db = mysqli_connect('212.1.208.201','u795211952_admin','Isaac2901','u795211952_appsalon_mvc');

 if (!$db) {
     echo "Error: No se pudo conectar a MySQL.";
     echo "errno de depuración: " . mysqli_connect_errno();
     echo "error de depuración: " . mysqli_connect_error();
     exit;
 }

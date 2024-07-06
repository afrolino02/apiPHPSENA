<?php

$host = "localhost";
$usuario = "root";
$password = "";  // Se requiere un signo "=" aquí
$basededatos = "persona";

$conexion = new mysqli($host, $usuario, $password, $basededatos);

if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);  // Corregido para usar un punto para concatenar y se cerró el die correctamente
}

header("Content-Type: application/json");
$metodo = $_SERVER["REQUEST_METHOD"];

switch($metodo){
    case 'GET':
        consulta($conexion);
        break;
    case 'POST':
        insertar($conexion);
        break;
    case 'PUT':
        actualizar($conexion);
        break;
    case 'DELETE':
        eliminar($conexion);
        break;
    default:
        echo json_encode(array("mensaje" => "No se ha reconocido el verbo"));
        break;        
}

function consulta($conexion) {
    $sql = "SELECT * FROM api";
    $resultado = $conexion->query($sql);

    $datos = array();
    while($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    }

    echo json_encode($datos);
}

function insertar($conexion) {
    $datos = json_decode(file_get_contents("php://input"), true);

    // Asumiendo que tu tabla tiene columnas 'nombre' y 'apellido'
    $nombre = $datos["nombre"];
    $apellido = $datos["apellido"];

    $sql = "INSERT INTO api (nombre, apellido) VALUES ('$nombre', '$apellido')";

    if ($conexion->query($sql) === TRUE) {
        echo json_encode(array("mensaje" => "Registro insertado correctamente"));
    } else {
        echo json_encode(array("mensaje" => "Error: " . $sql . " " . $conexion->error));
    }
}

function actualizar($conexion) {
    $datos = json_decode(file_get_contents("php://input"), true);

    // Asumiendo que tu tabla tiene una columna 'id', 'nombre' y 'apellido'
    $id = $datos["id"];
    $nombre = $datos["nombre"];
    $apellido = $datos["apellido"];

    $sql = "UPDATE api SET nombre='$nombre', apellido='$apellido' WHERE id=$id";

    if ($conexion->query($sql) === TRUE) {
        echo json_encode(array("mensaje" => "Registro actualizado correctamente"));
    } else {
        echo json_encode(array("mensaje" => "Error: " . $sql . " " . $conexion->error));
    }
}

function eliminar($conexion) {
    $datos = json_decode(file_get_contents("php://input"), true);

    // Asumiendo que tu tabla tiene una columna 'id'
    $id = $datos["id"];

    $sql = "DELETE FROM api WHERE id=$id";

    if ($conexion->query($sql) === TRUE) {
        echo json_encode(array("mensaje" => "Registro eliminado correctamente"));
    } else {
        echo json_encode(array("mensaje" => "Error: " . $sql . " " . $conexion->error));
    }
}

$conexion->close();
?>

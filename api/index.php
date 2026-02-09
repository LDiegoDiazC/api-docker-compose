<!-- Introducción a Microservicios: Arquitectura y Contenedores -->
<?php
header("Content-Type: application/json");
require 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = explode('/', trim($uri, '/'));
$id = (isset($path[0]) && is_numeric($path[0])) ? $path[0] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            echo json_encode($result ? $result : ["error" => "Usuario no encontrado"]);
        } else {
            $stmt = $pdo->query("SELECT * FROM usuarios");
            echo json_encode($stmt->fetchAll());
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!empty($data['nombre']) && !empty($data['email'])) {
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
            $stmt->execute([$data['nombre'], $data['email']]);
            echo json_encode(["status" => "Usuario creado", "id" => $pdo->lastInsertId()]);
        } else {
            echo json_encode(["error" => "Datos incompletos"]);
        }
        break;

    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
            $stmt->execute([$data['nombre'], $data['email'], $id]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(["status" => "Usuario $id actualizado"]);
            } else {
                echo json_encode(["status" => "No se realizaron cambios o ID no existe"]);
            }
        } else {
            echo json_encode(["error" => "ID no proporcionado"]);
        }
        break;

    case 'DELETE':
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(["status" => "Usuario $id eliminado"]);
        } else {
            echo json_encode(["error" => "ID no proporcionado"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
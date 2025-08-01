<?php
include './bd/conexion.php';
header('Content-Type: application/json');

// Leer el JSON del frontend
$data = json_decode(file_get_contents("php://input"), true);
$title = $data['title'] ?? 'Documento sin título';
$content = $data['content'] ?? '';
$uuid_doc = $data['uuid'] ?? null;

if ($uuid_doc && $content) {
    $sql = "UPDATE editor_docs 
            SET title = ?, html_content = ?, updated_at = NOW() 
            WHERE UUID_doc = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $title, $content, $uuid_doc);

    if ($stmt->execute()) {
        echo json_encode(["status" => "ok"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
}

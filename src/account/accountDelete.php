<?php
header('Content-Type: application/json');
require '../db/db_connect.php';

function deactivateAccount($user_id) {
    $conn = getDbConnection();
    $response = array('success' => false, 'message' => '');

    // Inicia la sesión
    session_start();

    try {
        // Inicia la transacción
        $conn->begin_transaction();

        // Recopilar datos del cliente
        $clientData = array();
        $sql = "SELECT * FROM cliente WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $clientData['cliente'] = $result->fetch_assoc();
            }
            $stmt->close();
        } else {
            $response['message'] = "Error preparando la consulta para obtener los datos del cliente.";
            $conn->rollback();
            echo json_encode($response);
            return;
        }

        // Recopilar datos de los pedidos
        $clientData['pedidos'] = array();
        $sql = "SELECT * FROM pedido WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $clientData['pedidos'][] = $row;
            }
            $stmt->close();
        } else {
            $response['message'] = "Error preparando la consulta para obtener los pedidos del cliente.";
            $conn->rollback();
            echo json_encode($response);
            return;
        }

        // Recopilar datos de las reservas
        $clientData['reservas'] = array();
        $sql = "SELECT * FROM reserva WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $clientData['reservas'][] = $row;
            }
            $stmt->close();
        } else {
            $response['message'] = "Error preparando la consulta para obtener las reservas del cliente.";
            $conn->rollback();
            echo json_encode($response);
            return;
        }

        // Recopilar datos de la retroalimentación
        $clientData['retroalimentacion'] = array();
        $sql = "SELECT * FROM retroalimentacion WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $clientData['retroalimentacion'][] = $row;
            }
            $stmt->close();
        } else {
            $response['message'] = "Error preparando la consulta para obtener la retroalimentación del cliente.";
            $conn->rollback();
            echo json_encode($response);
            return;
        }

        // Actualiza el estado de activación del cliente
        $sql = "UPDATE cliente SET estadoActivacion = 0 WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                // Añadir la fecha de desactivación
                $clientData['fechaDesactivacion'] = date("Y-m-d H:i:s");

                // Guardar los datos en un archivo JSON
                $jsonFilePath = "../../backups/users/user_" . $user_id . ".json";
                file_put_contents($jsonFilePath, json_encode($clientData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                $conn->commit();
                // Destruye la sesión
                session_destroy();
                $response['success'] = true;
                $response['message'] = "Cuenta desactivada correctamente y datos exportados a JSON.";
            } else {
                $response['message'] = "Error desactivando la cuenta: " . $stmt->error;
                $conn->rollback();
            }
            $stmt->close();
        } else {
            $response['message'] = "Error preparando la consulta para desactivar al cliente.";
            $conn->rollback();
        }

        $conn->close();
    } catch (Exception $e) {
        $response['message'] = 'Excepción capturada: ' . $e->getMessage();
        $conn->rollback();
    }

    echo json_encode($response);
}

// Manejo de la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén los datos JSON del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action']) && $data['action'] === 'deactivateAccount' && isset($data['user_id'])) {
        $user_id = intval($data['user_id']);
        deactivateAccount($user_id);
    } else {
        echo json_encode(['success' => false, 'message' => 'Acción no especificada o falta user_id']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido']);
}

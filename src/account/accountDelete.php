<?php
header('Content-Type: application/json');
require '../db/db_connect.php';

function deleteAccount($user_id) {
    $conn = getDbConnection();
    $response = array('success' => false, 'message' => '');

    // Inicia la sesión
    session_start();

    try {
        // Inicia la transacción
        $conn->begin_transaction();

        // Obtén el nombre del avatar antes de eliminar el registro del cliente
        $sql = "SELECT avatar FROM cliente WHERE idCliente = ?";
        $avatar = null;
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($avatar);
            $stmt->fetch();
            $stmt->close();
        } else {
            $response['message'] = "Error preparando la consulta para obtener el avatar.";
            echo json_encode($response);
            return;
        }

        // Elimina los detalles de los pedidos asociados al cliente
        $sql = "DELETE FROM pedidodetalle WHERE idPedido IN (SELECT idPedido FROM pedido WHERE idCliente = ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $response['message'] = "Error preparando la consulta para eliminar los detalles de los pedidos.";
            $conn->rollback();
            echo json_encode($response);
            return;
        }

        // Elimina los pedidos asociados al cliente
        $sql = "DELETE FROM pedido WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $response['message'] = "Error preparando la consulta para eliminar los pedidos.";
            $conn->rollback();
            echo json_encode($response);
            return;
        }

        // Elimina los detalles del carrito asociados al cliente
        $sql = "DELETE FROM carritodetalle WHERE idCarrito IN (SELECT idCarrito FROM carrito WHERE idCliente = ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $response['message'] = "Error preparando la consulta para eliminar los detalles del carrito.";
            $conn->rollback();
            echo json_encode($response);
            return;
        }

        // Elimina los carritos asociados al cliente
        $sql = "DELETE FROM carrito WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $response['message'] = "Error preparando la consulta para eliminar los carritos.";
            $conn->rollback();
            echo json_encode($response);
            return;
        }

        // Elimina las reservas asociadas al cliente
        $sql = "DELETE FROM reserva WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $response['message'] = "Error preparando la consulta para eliminar las reservas.";
            $conn->rollback();
            echo json_encode($response);
            return;
        }

        // Elimina las retroalimentaciones asociadas al cliente
        $sql = "DELETE FROM retroalimentacion WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $response['message'] = "Error preparando la consulta para eliminar las retroalimentaciones.";
            $conn->rollback();
            echo json_encode($response);
            return;
        }

        // Finalmente, elimina al cliente
        $sql = "DELETE FROM cliente WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);

            if ($stmt->execute()) {
                // Si la cuenta se eliminó correctamente, intenta eliminar el archivo del avatar
                if ($avatar) {
                    $avatarPath = "../../public/assets/img/avatars/" . $avatar;
                    if (file_exists($avatarPath)) {
                        if (!unlink($avatarPath)) {
                            // Error al eliminar el avatar
                            $response['message'] = "Error al eliminar el avatar. Intenta nuevamente.";
                            $conn->rollback();
                            echo json_encode($response);
                            return;
                        }
                    } else {
                        $response['message'] = "Avatar no encontrado. Puede que ya haya sido eliminado.";
                    }
                }

                $conn->commit();
                // Destruye la sesión
       
                    session_destroy();
                
                $response['success'] = true;
                $response['message'] = "Cuenta eliminada correctamente.";
                echo json_encode($response);
                exit();
            } else {
                $response['message'] = "Error eliminando la cuenta: " . $stmt->error;
                $conn->rollback();
                echo json_encode($response);
                return;
            }
        } else {
            $response['message'] = "Error preparando la consulta para eliminar al cliente.";
            $conn->rollback();
            echo json_encode($response);
            return;
        }

        $conn->close();
    } catch (Exception $e) {
        $response['message'] = 'Excepción capturada: ' . $e->getMessage();
        $conn->rollback();
        echo json_encode($response);
        return;
    }

    echo json_encode($response);
}

// Manejo de la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén los datos JSON del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action']) && $data['action'] === 'deleteAccount' && isset($data['user_id'])) {
        $user_id = intval($data['user_id']);
        deleteAccount($user_id);
    } else {
        echo json_encode(['success' => false, 'message' => 'Acción no especificada o falta user_id']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido']);
}
?>

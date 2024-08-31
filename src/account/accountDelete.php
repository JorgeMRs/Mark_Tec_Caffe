<?php
function deleteAccount($user_id) {
    $conn = getDbConnection();

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
        }

        // Elimina detalles del carrito asociados al cliente
        $sql = "DELETE cd FROM carritodetalle cd
                JOIN carrito c ON cd.idCarrito = c.idCarrito
                WHERE c.idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        }

        // Elimina los carritos asociados al cliente
        $sql = "DELETE FROM carrito WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        }

        // Elimina los pedidos asociados al cliente
        $sql = "DELETE pd FROM pedidodetalle pd
                JOIN pedido p ON pd.idPedido = p.idPedido
                WHERE p.idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        }

        $sql = "DELETE FROM pedido WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        }

        // Elimina las reservas asociadas al cliente
        $sql = "DELETE FROM reserva WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        }

        // Elimina las retroalimentaciones asociadas al cliente
        $sql = "DELETE FROM retroalimentacion WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        }

        // Finalmente, elimina al cliente
        $sql = "DELETE FROM cliente WHERE idCliente = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);

            if ($stmt->execute()) {
                // Si la cuenta se eliminó correctamente, elimina el archivo del avatar
                if ($avatar) {
                    $avatarPath = "/var/www/cafesabrosos/public/assets/img/avatars/" . $avatar;
                    if (file_exists($avatarPath)) {
                        unlink($avatarPath);
                    }
                }

                $conn->commit();
                session_destroy();
                header('Location: /index.php?accountDeleted=true');
                exit();
            } else {
                $conn->rollback();
                return "Error eliminando la cuenta: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $conn->rollback();
            return "Error preparando la consulta: " . $conn->error;
        }

        $conn->close();
    } catch (Exception $e) {
        $conn->rollback();
        return 'Excepción capturada: ' . $e->getMessage();
    }

    return null;
}
?>
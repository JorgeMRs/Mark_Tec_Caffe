<?php
require '../vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Nueva conexión: ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Mensaje recibido: $msg\n"; // Imprimir el mensaje recibido
    
        // Registrar el mensaje en un archivo de log
        $this->logMessage($msg);
        
        // Si el mensaje es "ping", responde con un mensaje de estado
        if ($msg === "ping") {
            $from->send("Servidor está en funcionamiento");
        } else {
            foreach ($this->clients as $client) {
                if ($from !== $client) {
                    $client->send($msg);
                }
            }
        }
    }
    
    protected function logMessage($msg) {
        $logDir = __DIR__ . '/../logs'; // Añadir '/' para corregir la ruta
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true); // Crea el directorio si no existe
        }
        
        $logFile = $logDir . '/server.log'; // Ruta completa al archivo de log
        $timestamp = date('Y-m-d H:i:s'); // Formato de fecha y hora
        file_put_contents($logFile, "[$timestamp] $msg\n", FILE_APPEND);
    }
    
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Conexión {$conn->resourceId} desconectada\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

echo "Servidor WebSocket en funcionamiento en ws://localhost:8080\n"; // Mensaje de estado

$server->run();
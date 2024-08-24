<?php
session_start();
session_unset(); 
session_destroy(); // eliminar o borrar la sesion del usuario en la web
header("Location: /public/login.html");
exit();

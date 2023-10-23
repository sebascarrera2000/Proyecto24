<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$user = $_POST["usuario"];
$pass = $_POST["password"];

$servurl = "http://localhost:3001/usuarios/$user/$pass";
$curl = curl_init($servurl);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

if ($response === false) {
    header("Location: index.html");
}

$resp = json_decode($response);

if (json_last_error() === JSON_ERROR_NONE) {
    // Decodificación exitosa, el JSON es válido
    $rol = $resp->rol;

    session_start();
    $_SESSION["usuario"] = $user;

    if ($rol === "vendedor") {
        header("Location: admin.php");
    } elseif ($rol === "cliente") {
        header("Location: usuario.php");
    }
} else {
    // Error de decodificación JSON
    header("Location: index.html");
}



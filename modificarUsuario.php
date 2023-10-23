<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['usuario'])) {
        $usuario = $_GET['usuario'];
        // Realiza una solicitud GET a la API para obtener los datos del usuario
        $apiUrl = 'http://localhost:3001/usuarios/' . $usuario;
        $response = file_get_contents($apiUrl);

        if ($response === false) {
            echo "Error al obtener los datos del usuario desde la API.";
            exit();
        }

        $datosUsuario = json_decode($response, true);
    } else {
        echo "ID de usuario no válido.";
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $nuevoNombre = $_POST['nombre'];
    $nuevoEmail = $_POST['email'];
    $nuevoPassword = $_POST['password'];
    $nuevoRol = $_POST['rol'];

    // Construye un array con los datos a actualizar
    $datosActualizados = array(
        "nombre" => $nuevoNombre,
        "email" => $nuevoEmail,
        "password" => $nuevoPassword,
        "rol" => $nuevoRol
    );

    // Realiza una solicitud PUT a la API para actualizar los datos del usuario
    $apiUrl = 'http://localhost:3001/usuarios/' . $usuario;
    $opciones = array(
        'http' => array(
            'method' => 'PUT',
            'header' => 'Content-type: application/json',
            'content' => json_encode($datosActualizados)
        )
    );

    $contexto = stream_context_create($opciones);
    $resultado = file_get_contents($apiUrl, false, $contexto);

    // Redirige a la página de usuarios después de la actualización
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h2>Editar Usuario</h2>
        <!-- Formulario para editar los datos del usuario -->
        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $datosUsuario['nombre']; ?>">
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $datosUsuario['email']; ?>">
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo $datosUsuario['password']; ?>">
            </div>
            <div class="form-group">
                <label for="rol">Rol:</label>
                <select class="form-control" id="rol" name="rol">
                    <option value="cliente" <?php echo ($datosUsuario['rol'] === 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                    <option value="vendedor" <?php echo ($datosUsuario['rol'] === 'vendedor') ? 'selected' : ''; ?>>Vendedor</option>
                </select>
            </div>

            <input type="hidden" name="usuario" value="<?php echo $usuario; ?>">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>

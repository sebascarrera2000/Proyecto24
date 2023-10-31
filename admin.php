<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Administración de Usuarios</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #007bff;
            color: white;
        }
        .navbar .navbar-brand {
            font-size: 2rem;
        }
        .navbar .navbar-toggler-icon {
            background-color: white;
        }
        .navbar .navbar-text a {
            color: white;
        }
        .container {
            padding: 2rem;
        }
        .table {
            background-color: white;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .modal-title {
            color: #007bff;
        }
        .modal-content {
            border: none;
        }
    </style>
</head>
<body>
    <?php
        session_start();
        if (!isset($_SESSION["usuario"])) {
            header("Location: index.html");
        }
        $usuario = $_SESSION["usuario"];
    ?>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="admin.php">Almacen ABC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="admin.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-prod.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-ord.php">Ordenes</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <a class='nav-link' href='logout.php'>Logout <?php echo $usuario; ?></a>
                </span>
            </div>
        </div>
    </nav>
    <div class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Email</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Password</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $servurl = "http://localhost:3001/usuarios";
                    $curl = curl_init($servurl);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($curl);

                    if ($response === false) {
                        curl_close($curl);
                        die("Error en la conexión");
                    }

                    curl_close($curl);
                    $resp = json_decode($response);

                    foreach ($resp as $user) {
                        $nombre = $user->nombre;
                        $email = $user->email;
                        $usuario = $user->usuario;
                        $password = $user->password;        
                        $rol = $user->rol;          ?>
                <tr>
                    <td><?php echo $nombre; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $usuario; ?></td>
                    <td><?php echo $password; ?></td>
                    <td><?php echo $rol; ?></td>
                    <td>
                        <button class="btn btn-danger" onclick="eliminarUsuario('<?php echo $usuario; ?>')">🗑️</button>
                        <button class="btn btn-primary modificar-usuario" onclick="modificarUsuario('<?php echo $nombre; ?>','<?php echo $email; ?>','<?php echo $usuario; ?>','<?php echo $password; ?>','<?php echo $rol; ?>')">✏️</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            CREAR USUARIO
        </button>
    </div>
    <div class="modal" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">CREAR USUARIO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="crearUsuario.php" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" id="nombre" required >
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control" id="email" required>
                            <div class="form-text">Nunca compartiremos su correo electrónico con nadie más.</div>
                        </div>
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" name="usuario" class="form-control" id="usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>
                        <label for="password"  name="password" class="form-label">Rol</label>
                        <select class="form-select"  name="rol" aria-label="Default select example" required>
                        <option selected>Selecciona un rol</option>
                        <option value="cliente">cliente</option>
                        <option value="vendedor">vendedor</option>
                        </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">EDITAR USUARIO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="editUsuario.php" method="post">
                        <div class="mb-3">
                            <label for="editNombre" class="form-label">Nombre</label>
                            <input type="text" name="editNombre" class="form-control" id="editNombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Correo electrónico</label>
                            <input type="email" name="editEmail" class="form-control" id="editEmail" required>
                            <div class="form-text">Nunca compartiremos su correo electrónico con nadie más.</div>
                        </div>
                        <div class="mb-3">
                            <label for="editUsuario" class="form-label">Usuario</label>
                            <input type="text" name="editUsuario" class="form-control" id="editUsuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Contraseña</label>
                            <input type="password" name="editPassword" class="form-control" id="editPassword" required>
                        </div>
                        <label for="editRol" name="editRol" class="form-label">Rol</label>
                        <select class="form-select" name="editRol" id="editRol" aria-label="Default select example" required>
                            <option selected>Selecciona un rol</option>
                            <option value="cliente">Cliente</option>
                            <option value="vendedor">Vendedor</option>
                        </select>
                        <input type="hidden" name="editUsuarioId" id="editUsuarioId" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script>
    // Verifica si la variable de sesión existe
    var mensajeExito = "<?php echo isset($_SESSION['mensaje_exito']) ? $_SESSION['mensaje_exito'] : ''; ?>";
    
    // Si el mensaje de éxito existe, muestra un mensaje emergente o una notificación
    if (mensajeExito !== "") {
        alert(mensajeExito); // Muestra una alerta simple, puedes personalizar esto
        <?php unset($_SESSION['mensaje_exito']); ?>;
   }
</script>

<script>
    function eliminarUsuario(usuario) {
        
        if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
            // Realiza una solicitud para eliminar al usuario
            var url = 'eliminarUsuario.php?usuario=' + usuario;
            fetch(url)
                .then(response => {
                    console.log(response)
                    if (!response.ok) {
                        throw new Error('Error en la solicitud.');
                    }
                    location.reload();
                    alert("✅🗑️ Se ha eliminado de manera exitosa el usuario" ); 
                    
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }
</script>
<script>
    // ...

    // Función para abrir el modal de edición de usuario
    function modificarUsuario(nombre, email, usuario, password, rol) {
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editEmail').value = email;
        document.getElementById('editUsuario').value = usuario;
        document.getElementById('editPassword').value = password;
        document.getElementById('editRol').value = rol;
        document.getElementById('editUsuarioId').value = usuario; // Puedes cambiar esto si usas un campo de ID único para identificar usuarios

        // Abre el modal
        $('#editUserModal').modal('show');
    }
</script>

</body>
</html>

<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS, LINK, HEAD");
header("Access-Control-Allow-Headers: Content-Type");



$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'DELETE') {
    // Procesar una solicitud DELETE para eliminar un registro

    // Obtener los datos del cuerpo de la solicitud
    parse_str(file_get_contents("php://input"), $data);

    // Verificar si se proporcionó un ID válido
    if (isset($data['id']) && is_numeric($data['id'])) {
        // Obtener el ID a eliminar
        $id = $data['id'];

        // Conexión a la base de datos
        $conexion = new mysqli('localhost', 'gael', '123', 'blog');

        // Verificar la conexión
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Preparar una consulta SQL para eliminar el registro con el ID proporcionado
        $query = "DELETE FROM targetas WHERE id = ?";

        // Preparar una sentencia
        $stmt = $conexion->prepare($query);

        if ($stmt === false) {
            die('Error al preparar la consulta: ' . $conexion->error);
        }

        // Vincular el ID al parámetro de la consulta
        $stmt->bind_param("i", $id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $response = array('message' => 'Registro eliminado exitosamente');
        } else {
            $response = array('error' => 'Error al eliminar el registro');
        }

        // Cerrar la conexión a la base de datos
        $stmt->close();
        $conexion->close();
    } else {
        $response = array('error' => 'ID no válido');
    }
} else if ($method === 'GET' || $method === 'HEAD') {
    // Procesar una solicitud GET para obtener datos

    // Conexión a la base de datos
    $conexion = new mysqli('localhost', 'root', '', 'movilidad');

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Realizar la consulta a la base de datos con la cláusula LIMIT
    $query = "SELECT * FROM movilidad LIMIT 10";
    $resultado = $conexion->query($query);

    // Crear un arreglo para almacenar los datos
    $data = array();

    while ($fila = $resultado->fetch_assoc()) {
        $data[] = $fila;
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();

    $response = $data;
    
    if ($method === 'HEAD') {
        // Si es una solicitud HEAD, elimina el contenido del cuerpo de la respuesta
        ob_end_clean();
    }
} else if ($method === 'PUT') {
    // Procesar una solicitud PUT para actualizar un registro

    // Obtener los datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);

    // Verificar si se proporcionaron datos válidos
    if (isset($data['id']) && is_numeric($data['id'])) {
        // Obtener los datos a actualizar
        $id = $data['id'];
        $newTitle = $data['newTitle'];
        $newDescription = $data['newDescription'];
        $newFecha = $data['newFecha'];
        $newType = $data['newType'];

        // Conexión a la base de datos
        $conexion = new mysqli('localhost', 'gael', '123', 'blog');

        // Verificar la conexión
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Preparar una consulta SQL para actualizar el registro con el ID proporcionado
        $query = "UPDATE targetas SET title = ?, description = ?, fecha = ?, type = ? WHERE id = ?";

        // Preparar una sentencia
        $stmt = $conexion->prepare($query);

        if ($stmt === false) {
            die('Error al preparar la consulta: ' . $conexion->error);
        }

        // Vincular los datos al parámetro de la consulta
        $stmt->bind_param("ssssi", $newTitle, $newDescription, $newFecha, $newType, $id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $response = array('message' => 'Registro actualizado exitosamente');
        } else {
            $response = array('error' => 'Error al actualizar el registro');
        }

        // Cerrar la conexión a la base de datos
        $stmt->close();
        $conexion->close();
    } else {
        $response = array('error' => 'Datos no válidos para la actualización');
    }} else if ($method === 'POST') {
    // Procesar una solicitud POST para agregar un nuevo registro

    // Obtener los datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);

    // Verificar si se proporcionaron datos válidos
    if (isset($data['title']) && isset($data['description']) && isset($data['fecha']) && isset($data['type'])) {
        // Obtener los datos para agregar
        $newTitle = $data['title'];
        $newDescription = $data['description'];
        $newFecha = $data['fecha'];
        $newType = $data['type'];

        // Conexión a la base de datos
        $conexion = new mysqli('localhost', 'gael', '123', 'blog');

        // Verificar la conexión
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Preparar una consulta SQL para agregar un nuevo registro
        $query = "INSERT INTO targetas (title, description, fecha, type) VALUES (?, ?, ?, ?)";

        // Preparar una sentencia
        $stmt = $conexion->prepare($query);

        if ($stmt === false) {
            die('Error al preparar la consulta: ' . $conexion->error);
        }

        // Vincular los datos al parámetro de la consulta
        $stmt->bind_param("ssss", $newTitle, $newDescription, $newFecha, $newType);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $response = array('message' => 'Registro agregado exitosamente');
        } else {
            $response = array('error' => 'Error al agregar el registro');
        }

        // Cerrar la conexión a la base de datos
        $stmt->close();
        $conexion->close();
    } else {
        $response = array('error' => 'Datos no válidos para la adición');
    }
}else if ($method === 'OPTIONS') {
    // Manejar solicitudes OPTIONS simplemente respondiendo con las cabeceras permitidas
    header("HTTP/1.1 200 OK");
    exit;
}else if ($method === 'PATCH') {
    // Procesar una solicitud PATCH para actualizar un registro

    // Obtener los datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);

    // Verificar si se proporcionaron datos válidos
    if (isset($data['id']) && is_numeric($data['id'])) {
        // Obtener el ID y otros campos a actualizar
        $id = $data['id'];

        // Conexión a la base de datos
        $conexion = new mysqli('localhost', 'gael', '123', 'blog');

        // Verificar la conexión
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Construir la consulta SQL dinámicamente basada en los campos proporcionados
        $query = "UPDATE targetas SET ";
        $updateValues = array();

        if (isset($data['newTitle'])) {
            $query .= "title = ?, ";
            $updateValues[] = $data['newTitle'];
        }

        if (isset($data['newDescription'])) {
            $query .= "description = ?, ";
            $updateValues[] = $data['newDescription'];
        }

        if (isset($data['newFecha'])) {
            $query .= "fecha = ?, ";
            $updateValues[] = $data['newFecha'];
        }

        if (isset($data['newType'])) {
            $query .= "type = ?, ";
            $updateValues[] = $data['newType'];
        }

        // Eliminar la coma adicional al final de la consulta
        $query = rtrim($query, ", ");

        // Agregar la cláusula WHERE para el ID
        $query .= " WHERE id = ?";

        // Preparar una sentencia
        $stmt = $conexion->prepare($query);

        if ($stmt === false) {
            die('Error al preparar la consulta: ' . $conexion->error);
        }

        // Vincular los datos al parámetro de la consulta
        $updateValues[] = $id;
        $bindTypes = str_repeat('s', count($updateValues));
        $stmt->bind_param($bindTypes, ...$updateValues);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $response = array('message' => 'Registro actualizado exitosamente');
        } else {
            $response = array('error' => 'Error al actualizar el registro');
        }

        // Cerrar la conexión a la base de datos
        $stmt->close();
        $conexion->close();
    } else {
        $response = array('error' => 'Datos no válidos para la actualización');
    }
}else if ($method === 'TRACE') {
    // Procesar una solicitud TRACE para habilitar la funcionalidad de diagnóstico

    // Aquí puedes realizar cualquier lógica necesaria para responder a solicitudes TRACE
    // Por ejemplo, puedes reflejar la solicitud de vuelta para fines de diagnóstico
    $response = 'TRACE request received: ' . print_r($_REQUEST, true);
 }else if ($method === 'LINK') {
    // Procesa la solicitud LINK
    // Analiza el encabezado Link para obtener información sobre la relación
    $linkHeader = $_SERVER['HTTP_LINK'];

    // Realiza las acciones necesarias para establecer la relación entre recursos
    // Puedes analizar y procesar los datos del encabezado Link según tus necesidades

    // Responde con una confirmación
    $response = array('message' => 'Relación establecida exitosamente');
} else {
    $response = array('error' => 'Método no permitido');
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'promedio_anio') {
    // Conexión a la base de datos
    $conexion = new mysqli('localhost', 'gael', '123', 'movilidad');

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Realizar la consulta para obtener el promedio del año
    $query = "SELECT AVG(año) AS promedio_anio FROM movilidad";

    // Ejecutar la consulta y obtener los resultados
    $resultado = $conexion->query($query);

    if ($resultado === false) {
        $response = array('error' => 'Error en la consulta: ' . $conexion->error);
    } else {
        // Verificar si se encontraron filas
        if ($resultado->num_rows > 0) {
            // Obtener el resultado
            $promedio_anio = $resultado->fetch_assoc();
            $response = array('promedio_anio' => $promedio_anio['promedio_anio']);
        } else {
            $response = array('error' => 'No se encontraron resultados para calcular el promedio del año');
        }
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();

    // Configurar la cabecera para indicar que se está enviando JSON
    header('Content-Type: application/json');

    // Imprimir la respuesta en formato JSON
    echo json_encode($response);
    exit;
    }


  if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'repetidos') {
    // Conexión a la base de datos
    $conexion = new mysqli('localhost', 'gael', '123', 'movilidad');

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $columnas = array('cve_municipio', 'desc_municipio', 'id_indicador', 'indicador', 'año', 'valor', 'unidad_medida');
    $repetidos = array();

    foreach ($columnas as $columna) {
        $query = "SELECT $columna, COUNT(*) as cantidad FROM movilidad GROUP BY $columna HAVING COUNT(*) > 1 LIMIT 10";
        $resultado = $conexion->query($query);

        $valoresRepetidos = array();
        while ($fila = $resultado->fetch_assoc()) {
            $valoresRepetidos[] = $fila;
        }

        $repetidos[$columna] = $valoresRepetidos;
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();

    // Devolver los datos de valores repetidos en formato JSON
    $response = $repetidos; // Asignar los valores a $response para que el echo a continuación funcione

    // Configurar la cabecera para indicar que se está enviando JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Asegúrate de salir del script aquí para evitar imprimir otra respuesta JSON más adelante
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'media_columna' && isset($_GET['columna'])) {
    // Conexión a la base de datos
    $conexion = new mysqli('localhost', 'gael', '123', 'movilidad');

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Obtener el nombre de la columna desde la URL
    $columna = $_GET['columna'];

    // Realizar la consulta para obtener la media de la columna específica
    $query = "SELECT AVG($columna) AS media FROM movilidad";

    // Ejecutar la consulta y obtener los resultados
    $resultado = $conexion->query($query);

    if ($resultado === false) {
        $response = array('error' => 'Error en la consulta: ' . $conexion->error);
    } else {
        // Obtener el resultado de la media
        $media = $resultado->fetch_assoc();

        // Verificar si se encontraron filas
        if ($resultado->num_rows > 0) {
            $response = array('media' => $media['media']);
        } else {
            $response = array('error' => 'No se encontraron resultados para calcular la media de la columna');
        }
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();

    // Devolver los datos de la media en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Asegúrate de salir del script aquí para evitar imprimir otra respuesta JSON más adelante
}

   


    

// Configurar la cabecera para indicar que se está enviando JSON
header('Content-Type: application/json');

// Imprimir la respuesta en formato JSON
echo json_encode($response);

?>

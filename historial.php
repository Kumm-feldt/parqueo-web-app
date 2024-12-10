<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>visualizar Datos CSV</title>
    <link href="historialSheet.css" rel="stylesheet" />
    
</head>
<body>
    <div class="container">
        <h1>Historial de archivos</h1>
    
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha creada</th>
                    <th>Hora creada</th>
                    <th>Turno</th>
                    <th>Nombre del archivo</th>
                    <th>Descargar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'conn.php';

                $sql = "SELECT id, created_date, created_time, username, file_name FROM excel_files";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['created_date']}</td>
                                <td>{$row['created_time']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['file_name']}</td>
                                <td><a class='download-link' href='download_excel.php?id={$row['id']}'>Download</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

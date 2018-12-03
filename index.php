<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Listado de productos</h1>
        <?php
        $dwes = new mysqli('localhost', 'dwes', 'dwes', 'dwes');
        if ($dwes->connect_errno != null) {
            echo "ERROR: " . $dwes->connect_error;
            exit();
        }
        $consulta = "SELECT nombre_corto,cod FROM producto;";
        $resultado = $dwes->query($consulta);
        $productos = $resultado->fetch_object();
        while ($productos != null) {
                    echo '<a href="archivoStock.php?cod='.$productos->cod.'"</a>'.$productos->nombre_corto.'<br>';
                    $productos = $resultado->fetch_object();
                }
        ?>
    </body>
</html>

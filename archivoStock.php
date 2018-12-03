<!DOCTYPE html>

<html>
    <head>
        <style>
            input[type=number] {
                width: 3%;
            }
        </style>
        <meta charset="UTF-8">
        <title>Stock</title>
    </head>
    <body>
        <?php
        $dwes = new mysqli('localhost', 'dwes', 'dwes', 'dwes');
        if ($dwes->connect_errno != null) {
            echo "ERROR: " . $dwes->connect_error;
            exit();
        }
        $consulta = "SELECT P.nombre_corto, S.unidades, (SELECT T.nombre from tienda T WHERE T.cod=S.tienda) FROM producto P, stock S WHERE S.producto=cod AND P.cod='" . $_GET['cod'] . "'";
        $resultado = $dwes->query($consulta);
        $stock = $resultado->fetch_array();
        echo '<h1>' . $stock[0] . '</h1>';
        echo '<h2>Stock actual</h2>';
        $i = 1;
        while ($stock != null) {
            echo
            "<form name=\"stock\" action=\"#\" method=\"post\"><input type=\"hidden\" name=\"tienda$i\" value=$stock[2]>$stock[2]<input type=\"number\" name=\"unidades$i\" value=\"$stock[1]\"> unidades<br>";
            $stock = $resultado->fetch_array();
            $i++;
        }
        echo "<br><input type=\"submit\"><br><br>
            </form>";
        echo '<a href="index.php">Volver</a><br>';

        if ($_POST) {
            $dwes->autocommit(false);
            $z = 1;
            try {
                while ($z < $i) {
                    $consulta = $dwes->stmt_init();
                    $consulta->prepare('UPDATE stock SET unidades = ? WHERE producto = ? AND tienda = (SELECT T.cod FROM tienda T WHERE T.nombre=?)');
                    $consulta->bind_param('iss', $_POST['unidades' . $z], $_GET['cod'], $_POST['tienda' . $z]);
                    if (!$consulta->execute()) {
                        $todo_bien=FALSE;
                        throw new Exception('Error update', 1);
                    }
                    else {
                        $dwes->commit();
                    }
                    $z++;
                }
                $dwes->commit();
            } catch (Exception $e) {
                $dwes->rollback();
            }
            header('Location: archivoStock.php?cod='.$_GET['cod']);
        }
        ?>
    </body>
</html>
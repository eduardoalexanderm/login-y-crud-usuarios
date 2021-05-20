<?php 
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php";?>
	<title>Listado de usuarios</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

    <?php 
        $busqueda = strtolower ($_REQUEST['busqueda']);
        if(empty($busqueda)){
            header("location: lista_usuarios.php");
        }
    ?>
		<h1>Lista de usuarios</h1>
        <a href="registro_usuario.php" class="btn_new_user">Crear un nuevo usuario</a>
        <a href="lista_usuarios.php" class="btn_all_users">Ver todos los usuarios</a>
        <form action="buscar_usuario.php" method="get"  class="form_search">
            <input type="text" name="busqueda" placeholder="Buscar usuario" id="busqueda" value="<?php echo $busqueda;?>">
            <input type="submit" value="Buscar" class="btn_search">
        </form>
    
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>

            <?php 
                //PAGINADOR
                $rol = '';
                if($busqueda == 'administrador'){
                    $rol = "OR rol LIKE '%1%'";
                }else if ($busqueda == 'supervisor'){
                    $rol = "OR rol LIKE '%2%'";
                } else if($busqueda == 'vendedor'){
                    $rol = "OR rol LIKE '%3%'";
                }
                $sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM usuario WHERE 
                                                                            ( 
                                                                              idusuario LIKE '%$busqueda%' OR 
                                                                                 nombre LIKE '%$busqueda%' OR 
                                                                                 correo LIKE '%$busqueda%' OR 
                                                                                usuario LIKE '%$busqueda%'
                                                                                 $rol) AND estatus = 1");

                $result_register = mysqli_fetch_array($sql_register);
                $total_registro = $result_register['total_registro'];

                $por_pagina = 5;
                if(empty($_GET['pagina'])){
                    $pagina = 1;
                }else {
                    $pagina = $_GET['pagina'];
                }

                $desde = ($pagina - 1) * $pagina;
                $total_paginas = ceil($total_registro / $por_pagina);
                //FIN paginador

            $query = mysqli_query($conection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol 
            FROM usuario u INNER JOIN rol r ON u.rol = r.idrol
             WHERE ( 
                    u.idusuario LIKE '%$busqueda%' OR 
                    u.nombre    LIKE '%$busqueda%' OR 
                    u.correo    LIKE '%$busqueda%' OR 
                    u.usuario   LIKE '%$busqueda%' OR
                    r.rol       LIKE '%$busqueda%') AND
            estatus = 1 
             ORDER BY idusuario ASC LIMIT $desde, $por_pagina");
            $result = mysqli_num_rows($query);

            if($result > 0){
                while($lista_user = mysqli_fetch_array($query)){          
            ?>
            
            <tr>
                <td><?php echo $lista_user['idusuario'];?></td>
                <td><?php echo $lista_user['nombre'];?></td>
                <td><?php echo $lista_user['correo'];?></td>
                <td><?php echo $lista_user['usuario']; ?></td>
                <td><?php echo $lista_user['rol']; ?></td>

                <td>
                    <a href="editar_usuario.php?id=<?php echo $lista_user['idusuario'];?>" class="link_edit">Editar</a>
                <?php if($lista_user['idusuario'] != 1){ //Configuración para el usuario maestro, este no se podría eliminar de la BD

                ?>
                |
                <a href="eliminar_confirmar_usuario.php?id=<?php echo $lista_user['idusuario']; ?>" class="link_delete">Eliminar</a>
                <?php  } ?>
            </td>
            </tr>
                    <?php
        } 
    }     else if($result == 0){
        echo '<td>No se encontraron resultados.</td>';
    }
                 ?>
        </table>

        <div class="paginador">
            <ul>
            <?php 
                if($pagina != 1){          
            ?>
                <li> <a href="?pagina=<?php echo  1;?>"> |< </a></li>
                <li> <a href="?pagina=<?php echo  $pagina - 1;?>"> << </a></li>

                <?php 
                }
                for ($i=1; $i <= $total_paginas; $i++) { 

                    if($i == $pagina){
                    echo ' <li class="pageSelected">  '.$i.' </li>';
                    } else {
                        echo '<li><a href="?pagina='.$i.'"> '.$i.'</a></li>';
                    }
                }

                if($pagina != $total_paginas){
                ?>
       
                <li> <a href="?pagina=<?php echo  $pagina + 1;?>"> >> </a></li>
                <li> <a href="?pagina=<?php echo  $total_paginas;?>"> >| </a></li>
                <?php }?>
            </ul>
        </div>
	</section>

	<?php include "includes/footer.php";?>
</body>
</html>
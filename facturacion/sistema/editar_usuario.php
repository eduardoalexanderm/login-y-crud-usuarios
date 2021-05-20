<?php 
 include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) 
         || empty($_POST['rol']))
         {
            $alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
        } else{
           
            $idusuario = $_POST['idusuario'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $usuario = $_POST['usuario'];
            $clave =  md5(mysqli_real_escape_string ($conection, $_POST['clave']));
            $rol = $_POST['rol'];

            //La siguiente linea de codigo es para que no se repita los campos usuario y correo en caso que ya estén  ingresados en la BD
            $query = mysqli_query($conection, "SELECT * FROM usuario
                                                    WHERE (usuario = '$usuario' AND idusuario != $idusuario) 
                                                    OR (correo='$correo' AND idusuario != $idusuario)"); //selecccionar todo de la tabla usuario donde usuario y idusuario sea distinto al que estamos enviando
            $result = mysqli_fetch_array($query);

            //evaluar result
            if($result > 0){
                $alert='<p class="msg_error"> El correo o usuario ya existe.</p>';
            }else //sino retorna nada, entonces SI procedemos a almancenar la información
                {
                    if(empty($_POST['clave'])){ //no va la clave

                            $sql_update = mysqli_query($conection, "UPDATE usuario SET nombre = '$nombre', correo = '$correo', usuario = '$usuario', rol = '$rol' WHERE idusuario = '$idusuario'");
                    } else{ //si va la clave
                        $sql_update = mysqli_query($conection, "UPDATE usuario SET nombre = '$nombre', correo = '$correo', usuario = '$usuario', rol = '$rol' WHERE idusuario = '$idusuario'");
                    }

               
                    if($sql_update == true){
                        $alert= '<p class="msg_save"> Usuario actualizado correctamente.</p>';
                    }else{
                        $alert='<p class="msg_error"> Error al actualizar el usuario</p>'; 
                    }
                }
           
        }
    }

    //Mostrar datos
    if(empty($_GET['id'])){ //si no existe el id cliqueado, entonces, redirigir la lista
        header("location: lista_usuarios.php");
    }

    $iduser =  $_GET['id'];
    
    $sql = mysqli_query($conection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, (u.rol) as idrol, (r.rol) as rol
                                        FROM usuario u
                                        INNER JOIN rol r
                                        ON u.rol = r.idrol
                                        WHERE idusuario = $iduser");

    $result_sql = mysqli_num_rows($sql);
    if($result_sql == 0){
        header("Location: lista_usuario.php");
    }else {
        $option = '';
        while($data = mysqli_fetch_array($sql)){

            $iduser   = $data['idusuario'];
            $nombre   = $data['nombre'];
            $correo   = $data['correo'];
            $usuario  = $data['usuario'];
            $idrol    = $data['idrol']; //identificador del rol
            $rol      = $data['rol']; //nombre del rol
        }

        if($idrol = 1){ //Admin
            $option = '<option value="'.$idrol.'" selected> '.$rol.' </option>';
        } else if($idrol = 2){
            $option = '<option value="'.$idrol.'" selected> '.$rol.' </option>';
        } else if($idrol  = 3){
            $option = '<option value="'.$idrol.'" selected> '.$rol.' </option>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php";?>
	<title>Actualizar usuario</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <div class="form_register">
            <h1> Actualizar usuario</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : '';?></div>
            <form action="" method="post">
                <input type="hidden" name="idusuario" value="<?php echo $iduser ?>"> <!--campo oculto del idusuario-->
                <label for="Nombre">Nombre</label> <!--Mandar a llamar las variables arriba definidas, para que el formulario recoja los datos a editar-->
                <input type="text" name="nombre" id="Nombre" placeholder="Ingrese su nombre completo" value = " <?php echo $nombre ?>">
                <label for="correo">Correo electrónico</label>
                <input type="email" name="correo" id="correo" placeholder="Ingrese su correo electrónico" value = "<?php echo  $correo ?>">
                <label for="usuario">Nombre de usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Ingrese un nombre de usuario" value = "<?php echo $usuario  ?>">
                <label for="clave">Ingrese una contraseña</label>
                <input type="password" name="clave" id="clave" placeholder="Ingrese una contraseña" value = "<?php ?>">
                <label for="rol">Rol</label>
 <?php //---------------------------------------------------------------------------------------------------?>
                <?php 
                    $query_rol = mysqli_query($conection, "SELECT * FROM rol");
                    $result_rol = mysqli_num_rows($query_rol); //cuenta las filas del query
                ?>
                <select name="rol" id="rol" class="notItemOne"> 
                
                        <?php 
                        echo $option;
                      if($result_rol > 0){
                        while ($rol = mysqli_fetch_array($query_rol)) {                         
                        ?>

                          <option value="<?php echo $rol["idrol"];?>"> <?php echo $rol["rol"];?> </option>
                    <?php 
                        }
                    }
                    ?>

                </select>
                    <input type="submit" value="Actualizar usuario" class="btn_save">
                    <a href="lista_usuarios.php" class="btn_back">Regresar</a>
 <?php //---------------------------------------------------------------------------------------------------?>
            </form>
        </div>
	</section>

	<?php include "includes/footer.php";?>
</body>
</html>
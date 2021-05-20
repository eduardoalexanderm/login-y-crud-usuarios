<?php 
include("../conexion.php");

    if(!empty($_POST)){

        if($_POST['idusuario'] == 1){ //estas linea de codigo es para que no se pueda eliminar el usuario MASTER desde "Inspeccionar elemento"
            header("location: lista_usuarios.php");
            exit; //para que ya no se ejecute lo demás
        }

        $idusuario = $_POST['idusuario'] ;

       // $query_delete = mysqli_query($conection , "DELETE FROM usuario WHERE idusuario = $idusuario");
       $query_delete = mysqli_query($conection,"UPDATE usuario SET estatus = 0 WHERE idusuario = $idusuario");

        if($query_delete){
            header("location: lista_usuarios.php");
        }else{
            echo "Error al eliminar";
        }
      
    }



        //no existe id        || id = 1 (Usuario maestro)
    if(empty($_REQUEST['id']) || $_REQUEST['id'] ==1){ //metodo request, recibe tambien post y get de un formulario - si no existe el id entonces enviar al sistema 
        header("location: lista_usuarios.php");
    } else{
        $idusuario = $_REQUEST['id'];
        $query = mysqli_query($conection, "SELECT u.nombre, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE idusuario = $idusuario ");
        $result = mysqli_num_rows($query);

        if($result  > 0){
            while($data = mysqli_fetch_array($query)){
                //guardar nombres en variables
                $nombre = $data['nombre'];
                $usuario =  $data['usuario'];
                $rol = $data['rol'];
            }
        } else{ //si no existe el usuario, entonces redireccionar a :
            header("location: lista_usuarios.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php";?>
    
	<title>Eliminar usuario</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
            <h2>¿Está seguro de eliminar el siguiente registro?</h2>
            <p>Nombre: <span><?php echo $nombre;?></span></p>
            <p>Usuario: <span><?php echo $usuario;?></span></p>
            <p>Tipo de usuario: <span><?php echo $rol;?></span></p>
            
            <form method="post" action="">
                <input  name="idusuario" value="<?php echo $idusuario;?>" type="hidden">
                <a href="lista_usuarios.php" class="btn_cancel">Cancelar</a>
                <input type="submit" value="Aceptar" class="btn_ok">
            </form>
        </div>
	</section>

	<?php include "includes/footer.php";?>
</body>
</html>
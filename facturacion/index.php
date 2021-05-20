<?php
$alert = '';
session_start();
if(!empty($_SESSION['active'])){  //"!empty" significa: si existe
    header('location: sistema/');
 } else{
        if(!empty($_POST))
        {
        if(empty($_POST['usuario']) || empty($_POST['clave']))
            {
                $alert = 'Por favor, ingrese su usuario y contraseña';
            } else{
            require_once "conexion.php";
            //guardar lo que hemos obtenido del post usuario
            $user = mysqli_real_escape_string($conection, $_POST['usuario']);
            //guardar lo que hemos obtenido de post clave del form
            $pass =  md5(mysqli_real_escape_string($conection, $_POST['clave']));

            $query = mysqli_query($conection, "SELECT * FROM usuario WHERE usuario = '$user'
            AND clave = '$pass'"); //esta funcion requiere de dos parametros ($conection, "sentencia SQL")
            //Posteriormente cuando se ejecute, se ejecutará un resultado, el cual lo guardaremos en la variable "$result", asi:
            $result = mysqli_num_rows($query);

            if($result > 0){
                $data = mysqli_fetch_array($query); //se guarda en un array con nombre $data lo que tenemos en $query

            //print_r($data);
            // Luego de obtener el los datos del usuario mediante el array, procederemos a iniciar la sesión
            
            //  variables de sesion
                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $data['idusuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['email'] = $data['correo'];
                $_SESSION['rol'] = $data['rol'];
                $_SESSION['user'] = $data['usuario'];

                header('location: sistema/');

            } else{
                $alert = 'Contraseña o nombre de usuario incorrectos, por favor, intentelo de nuevo.';
                //destruir la sesión en dado caso se ingresen mal los datos
                session_destroy();
            }
        }
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  type="text/css" href="css/style.css">

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

    <title>Login - SISTEMA FACTURACIÓN</title>
</head>
<body>
    <section id="container">
    <form action="" method="post" name="Login_Form" class="form-signin">       
		    <h3 class="form-signin-heading">Bienvenido</h3>
            <img src="img/login.png" alt="Login" width="90" height="90" class="img_login">
			  <hr class="colorgraph"><br>
			  
			  <input type="text" class="form-control" name="usuario" placeholder="Ingrese su nombre de usuario" required="" autofocus="" />
			  <input type="password" class="form-control" name="clave" placeholder="Ingrese su clave" required=""/>     		  
			  <div class="alert"><?php echo isset($alert)? $alert : '';?></div>
			  <button class="btn btn-lg btn-primary btn-block"  name="Submit" value="Login" type="Submit">Iniciar sesión</button>  			
		</form>			
    </section>
</body>
</html>



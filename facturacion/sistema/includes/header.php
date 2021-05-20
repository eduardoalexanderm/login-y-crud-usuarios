<?php 
session_start();
if(empty($_SESSION['active'])){  //"empty" significa:  no existe
    header('location: ../');
 }?>

<header>
		<div class="header">
			
			<h1>Sistema Facturación</h1>
			<div class="optionsBar">
				<p>El Salvador, <?php echo fechaC(); ?> </p>
				<span>|</span>
				<span class="user"><?php echo $_SESSION['nombre'] ?></span>
				<img class="photouser" src="img/user.png" alt="Usuario">
				<a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Cerrar sesión"></a>
			</div>
		</div>
	<?php include "nav.php"; ?>
	</header>
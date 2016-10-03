

<HTML>
	<?php
	
	session_start();
	if($_SESSION['create'] == 'Success'){
		?>
		<script>alert('Account succesfully created. Redirecting to login page');</script>
		<?php
	}
	$_SESSION['create'] = '';
	$_SESSION['user'] = "";
	$con = mysqli_connect("istavrit.eng.ku.edu.tr", "asirel", "qRGZDD", "asirel_db");

	if(mysqli_connect_errno()){
		echo "Failed to connect, please check your username and password: ".mysqli_connect_error();
	}

	if(isset($_POST["login_button"])){
		$username = $_POST['username'];
		$password = $_POST['password'];

		$sql = "SELECT password,type from user where username  = '$username'";
		$res = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res);

		if($row['password'] == ($password)){
			$_SESSION['user'] = $username;
			if(isset($_SESSION['user'])!= ""){						
				if($row['type'] == "Client"){
					header("Location: home_cl.php");
				} else if($row['type'] == "Owner") {
					header("Location: home_ow.php");
				}
			}
		}else {
			?>
			<script>alert('Wrong username or password');</script>
			
			<?php 
		} 
	}


	?>
	<HEAD>
		<TITLE>Welcome to FoodOrder - Log In or Sign Up</TITLE>

	</HEAD>
	<BODY background = "foodOrder.png">

		

		<center>
			<br><br><br>
			<center><img  src = "img3.png" > </center>
			<br><br>
			<font color = "green" ><b>Welcome to foodOrder. Please enter your login credentials to continue. <br> If you're a new user please click the sign up button</b></font>
			<br>
			<br>
			<div id ="login form">
				<form align = "center"  method = "POST">
					<table align = "center" width = "25%" border = "0">
						<tr>
							<td><input type = "text" name = "username" placeholder = "your_username_here" required></td>
						</tr>
						<tr>
							<td><input type = "text" name = "password" placeholder = "your_password_here"></td>
						</tr>

						<td><input type = "submit" name = "login_button"  value = "Log In" /></td>
					</tr>
					<tr>
						<td><a href = "register.php">Sign Up</a></td>
					</tr>
				</table>
			</form>
		</div>
	</center>

</BODY>



</HTML>
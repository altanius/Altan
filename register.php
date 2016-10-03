<HTML>
	<HEAD>
		<TITLE>Register</TITLE>
	</HEAD>
	<BODY background = "foodOrder.png">
		<?php

		session_start();
		$_SESSION['user'] = "";

		$con = mysqli_connect("istavrit.eng.ku.edu.tr", "asirel", "qRGZDD", "asirel_db");
		if(mysqli_connect_errno()){
			echo "Failed to connect, please check your username and password: ".mysqli_connect_error();
		}

		$sql = "SELECT * from districts";
		$res = mysqli_query($con,$sql);


		if(isset($_POST['register_btn'])){
			

			$user_type = $_POST['user_type'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$name = $_POST['name'];
			$surname = $_POST['surname'];
			$door = $_POST['door'];
			$street = $_POST['street'];
			$district = $_POST['district'];
			$phone = $_POST['phone'];
			$city = $_POST['city'];
			

			if(strlen($password)<6 || strlen($password)>15){
				?>
				<script>alert('Password should be 6-15 charaters long');</script>
				<?php
			} else if(!ctype_alpha($password[0])){
				?>
				<script>alert('First letter of password should be alphabetical.');</script>
				<?php
			} else {
				$sql = "SELECT username FROM user WHERE username = '$username'";
				$res = mysqli_query($con,$sql);
				$row = mysqli_fetch_array($res);
				if($row['username'] == $username){
					?>
					<script>alert('Username already exists, please choose another one');</script>
					<?php
				} else {
					$sql = "INSERT INTO user values('$username', '$password', '$name', '$surname','$phone', '$user_type')";

					$bool = mysqli_query($con,$sql);
					$sql = "INSERT INTO address values('$username', '$door', '$street', '$district', '$city')";

					$bool2 = mysqli_query($con,$sql);
					$bool3 = True;
					if($user_type == "Client"){
						$sql = "INSERT INTO cart values('$username',NULL,'0')";
						$bool3 = mysqli_query($con,$sql);
					}
					if(!$bool || !$bool2 || !$bool3){
						$sql = "DELETE FROM user where username = '$username'";
						mysqli_query($con,$sql);
						$sql = "DELETE FROM address where username = '$username'";
						mysqli_query($con,$sql);
						$sql = "DELETE FROM cart where username = '$username'";
						mysqli_query($con,$sql);
						?>
						<script>alert('Failed to create new account. Please try again.');</script>
						<?php
					} else {
						
						$_SESSION['user'] = $username;
						$_SESSION['create'] = 'Success';
						header("Location: welcome.php");

					}
				}
			}


		}

		?>
		<br>
		<center><img  src = "images (2).jpg" > 
			<br>
			<div id = "register form">
				<form method = "POST">
					<table align = "center" width = "25%" border = "0">
						<tr>
							<td> User Type : <select name = "user_type">
								<option  value = "Client">Client</option>
								<option value = "Owner">Owner</option>
							</select></td>
						</tr>
						<tr>
							<td>Username :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "username"   ></td>
						</tr>
						<tr>
							<td>Password :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "password"   ></td>
						</tr>
						<tr>
							<td>Name :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "text" name = "name" required></td>
						</tr>
						<tr>
							<td>Surname :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "surname" required></td>
						</tr>
						<tr>
							<td>Address Information</td>
						</tr>
						<tr>
							<td>Door No :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "door" required></td>
						</tr>
						<tr>
							<td>Street :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "street" required></td>
						</tr>
						<tr>
							<td>District :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <select name = "district">
								<?php 
								while($row = mysqli_fetch_array($res)){  
									?>
									<option value = "<?php echo $row['district_name']; ?>"> <?php echo $row['district_name'];?></option><?php } ?></select></td>
								</tr>
								<tr>
									<td>City :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "city" required></td>
								</tr>
								<tr>
									<td>Phone :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "phone" required></td>
								</tr>
								<tr>
									<td><input type = "submit" name = "register_btn" value = "Sign Up" /></td>
								</tr>
							</table>
						</form>
					</div>
				</center>
			</BODY>
		</HTML>
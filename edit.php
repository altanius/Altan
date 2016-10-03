<html>
<head>
	<title>Edit-<?php session_start();
		echo $_SESSION['user']; ?></title>
	</head>
	<body background = "foodOrder.png">
		<?php

		$con = mysqli_connect("istavrit.eng.ku.edu.tr", "asirel", "qRGZDD", "asirel_db");
		if(mysqli_connect_errno()){
			echo "Failed to connect, please check your username and password: ".mysqli_connect_error();

		}

		$sql = "SELECT * from districts";
		$res0 = mysqli_query($con,$sql);	

		$username = $_SESSION['user'];
		$sql = "SELECT password from user where username = '$username'";
		$res = mysqli_query($con,$sql);

		$sql = "SELECT name from user where username = '$username'";
		$res2 = mysqli_query($con,$sql);

		$sql = "SELECT surname from user where username = '$username'";
		$res3 = mysqli_query($con,$sql);

		$sql = "SELECT phone from user where username = '$username'";
		$res4 = mysqli_query($con,$sql);

		$sql = "SELECT door_no from address where username = '$username'";
		$res5 = mysqli_query($con,$sql);

		$sql = "SELECT street from address where username = '$username'";
		$res6 = mysqli_query($con,$sql);

		$sql = "SELECT district from address where username = '$username'";
		$res7 = mysqli_query($con,$sql);

		$sql = "SELECT city from address where username = '$username'";
		$res8 = mysqli_query($con,$sql);

		$row = mysqli_fetch_array($res);
		$password = $row['password'];

		$row = mysqli_fetch_array($res2);
		$name = $row['name'];

		$row = mysqli_fetch_array($res3);
		$surname = $row['surname'];

		$row = mysqli_fetch_array($res4);
		$phone = $row['phone'];

		$row = mysqli_fetch_array($res5);
		$door_no = $row['door_no'];

		$row = mysqli_fetch_array($res6);
		$street = $row['street'];

		$row = mysqli_fetch_array($res7);
		$district = $row['district'];

		$row = mysqli_fetch_array($res8);
		$city = $row['city'];

		if(isset($_POST['update'])){
			$p = $_POST['password'];
			if(strlen($p)<6 || strlen($p)>15){
				?>
				<script>alert('Password should be 6-15 charaters long');</script>
				<?php
			} else if(!ctype_alpha($p[0])){
				?>
				<script>alert('First letter of password should be alphabetical.');</script>
				<?php
			} else {
				$n = $_POST['name'];
				$sn = $_POST['surname'];
				$door = $_POST['door'];
				$st = $_POST['street'];
				$dist = $_POST['district'];
				$city = $_POST['city'];
				$phone = $_POST['phone'];

				$sql = "UPDATE user set password = '$p',  name = '$n',surname = '$sn',phone = '$phone' where username = '$username' ";
				$bool = mysqli_query($con,$sql);
				$sql = "UPDATE address set door_no = '$door',street = '$st', district = '$dist', city = '$city' where username = '$username' ";
				$bool2 = mysqli_query($con,$sql);
				if($bool && $bool2){
					?> 
					<script> alert ('Update succesfull');</script>
					<?php
				} else {
					?>
					<script>alert ('Failed to update');</script> <?php
				} 
				header("Refresh:0");
			}
		}

		if(isset($_POST['home'])){
			header("Location: home_cl.php");
		}

		if(isset($_POST['logout'])){
			header("Location: welcome.php");
		}

		?>
		<center><img  src = "images.jpg" >
			<br>
			<br>
			<font color = "red"> You can change your info here (username and user type cannot be altered). </font>

			<div id = "register form">
				<form method = "POST">
					<table align = "center" width = "25%" border = "0">

						<tr>
							<td>Password :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "password" value = "<?php echo $password ?>"  ></td>
						</tr>
						<tr>
							<td>Name :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "text" name = "name"   value = "<?php echo $name ?>"></td>
						</tr>
						<tr>
							<td>Surname :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "surname"  value = "<?php echo $surname ?>"></td>
						</tr>
						<tr>
							<td>Address Information</td>
						</tr>
						<tr>
							<td>Door No :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "door"  value = "<?php echo $door_no ?>"></td>
						</tr>
						<tr>
							<td>Street :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "street"  value = "<?php echo $street ?>"></td>
						</tr>
						<tr>
							<td>District :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <select name = "district">
								<?php 
								while($row = mysqli_fetch_array($res0)){  
									?>
									<option value = "<?php echo $row['district_name']; ?>" 
										<?php if($row['district_name'] == $district){
											?> selected = "selected" <?php
										} ?>
										> <?php echo $row['district_name'];?></option><?php } ?>

									</select></td>
								</tr>
								<tr>
									<td>City :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "city"  value = "<?php echo $city ?>" ></td>
								</tr>
								<tr>
									<td>Phone :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type = "text" name = "phone"  value = "<?php echo $phone ?>" ></td>
								</tr>
								<tr>
									<td><input type = "submit" name = "update" value = "Update" /></td>
									<td><input type = "submit" name = "home" value = "Home" /> </td>
									<td><input type = "submit" name = "logout" value = "Logout" /> </td>
								</tr>
							</table>
						</form>
					</div>


				</center>
			</body>
			</html>
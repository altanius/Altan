
<html>
<head>
	<title>Home-<?php 
		session_start();
		echo $_SESSION['user'];
		?></title>
	</head>
	<body background = "foodOrder.png">
		<center><img  src = "images.jpg" >
			<br><br><br>
			<?php
			if($_SESSION['order'] == 'ordered'){
				?>
						<script>alert('Order succesfully placed. Your cart is now empty, redirecting to home page');</script>
						<?php
			}


			$_SESSION['order'] = '';

			$username = $_SESSION['user'];
			$con = mysqli_connect("istavrit.eng.ku.edu.tr", "asirel", "qRGZDD", "asirel_db");
			if(mysqli_connect_errno()){
				echo "Failed to connect, please check your username and password: ".mysqli_connect_error();

			}

			$sql = "SELECT district from address where username = '$username'";
			$res = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res);
			$district = $row['district'];

			if(isset($_POST['edit'])){
				header("Location: edit.php");
			}

			if(isset($_POST['go'])){
				$_SESSION['rest_name'] = $_POST["rest"];
				$_SESSION['type']  = "All";
 				header("Location: restaurant.php");
			}

			if(isset($_POST['view'])){
				header("Location: cart.php");
			}

			if(isset($_POST['past'])){
				$_SESSION['user'] = $username;
				header("Location: past.php");
			}

			if(isset($_POST['logout'])){
				session_commit();
				header("Location: welcome.php");
			}


			?> <font color = "blue"> <i>Welcome <?php echo $username ?>! Below is the list of restaurants that serve to your district (<?php echo $district ?>). Your can choose a restaurant and be redirected to menu page. You can choose to see your past orders and edit your information by clicking the related buttons. Enjoy!</i> </font> <?php

			$sql = "SELECT name,rank from restaurant  where name in ( SELECT rest_name from  serves_to where district = '$district' ) or name not in (SELECT rest_name from serves_to); ";
			$res = mysqli_query($con,$sql);

			$sql = "SELECT name from restaurant  where name in ( SELECT rest_name from  serves_to where district = '$district' ) or name not in (SELECT rest_name from serves_to); ";
			$result = mysqli_query($con,$sql);

			?>
			<table>

				<tr>
					<td><font color = "green"> <u>RESTAURANT NAME</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td> 
					<td><font color = "green"> <u>POINTS</u></font></td>
				</tr>
				<tr> <br> </tr>
				<?php
				while($row = mysqli_fetch_array($res)){
					$rname = $row['name'];
					$rank = $row['rank'];
					?><br> <tr>
					<td><?php echo $rname; ?></td>&nbsp;&nbsp;&nbsp;<td><?php echo $rank; ?></td>
				</tr> <?php
			} ?>
		</table>
		<br><br>
		<form method = "post">
			<font color = "purple"> <i>Choose a restaurant</i></font><br>
			<select name = "rest">
			<?php while($row = mysqli_fetch_array($result)){
				?> 
				<option value = "<?php echo $row['name'];?>"> <?php echo $row['name']; ?></option>
				<?php
			} ?>
			</select>
			<input type = "submit" name = "go" value = "GO!" />

			<br><br>
			<input type = "submit" name = "edit" value = "Edit Info" />
			<input type = "submit" name = "view" value = "View My Cart" />
			<input type = "submit" name = "past" value = "Past Orders" />
			<input type = "submit" name = "logout" value = "Logout" />
		</center>
	</body>
	</html>
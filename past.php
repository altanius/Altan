<html>
<head>
	<title>
		<?php 
		session_start();
		$username = $_SESSION['user'];
		echo $username;
		?>
		-Past Orders
	</title>
</head>

<body background = "foodOrder.png">
	<center><img  src = "past.jpg" >
		<br><br>
		<?php
		$con = mysqli_connect("istavrit.eng.ku.edu.tr", "asirel", "qRGZDD", "asirel_db");
		if(mysqli_connect_errno()){
			echo "Failed to connect, please check your username and password: ".mysqli_connect_error();
		}

		$sql = "SELECT id,rest_name,cost,is_delivered from orders where client_name = '$username' ";
		$res = mysqli_query($con,$sql);	

		if(isset($_POST['home'])){
			header("Location: home_cl.php");
		}

		?>
	<table>

			<tr>
				<td><font color = "green"> <u>ORDER ID </u> </font></td> 
				<td></td>
				<td><font color = "green"> <u>RESTAURANT </u> &nbsp;&nbsp;&nbsp;</font></td>
				<td><font color = "green"> <u>COST</u></font></td>
				<td><font color = "green"> <u>DELIVERED?</u></font></td>
			</tr>
			<tr><br></tr>
			<?php while($row = mysqli_fetch_array($res)){
				?>
				<tr>
					<td><?php echo $row['id']; ?></td>
					<td><?php echo $row['rest_name']; ?></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['cost']; echo '$'; ?></td>
					<td><?php echo $row['is_delivered']; ?></td>
					
					</tr>
					<?php
					} ?>
				</table>
				<form method = "POST">
				<input type = "submit" name = "home" value = "Home" />
				</form>
	</center>
</body>

</html>
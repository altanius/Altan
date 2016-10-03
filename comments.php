<html>
<head>
	<TITLE>
		<?php
		session_start();
		$rest = $_SESSION['rest_name'];
		echo $rest; ?>
		-Comments
	</TITLE>
</head>

<body background = "foodOrder.png">
	<center><img  src = "comments.jpg" >
		<br><br>
		<?php
		$con = mysqli_connect("istavrit.eng.ku.edu.tr", "asirel", "qRGZDD", "asirel_db");
		if(mysqli_connect_errno()){
			echo "Failed to connect, please check your username and password: ".mysqli_connect_error();
		}

		$sql = "SELECT comments,rest_name from comments where rest_name = '$rest' ";
		$res = mysqli_query($con,$sql);


		if(isset($_POST['Back'])){
			$_SESSION['rest_name'] = $rest;
			header("Location: restaurant.php");
		}

		if(isset($_POST['Home'])){

			header("Location: home_cl.php");
		}

		if(isset($_POST['Logout'])){
			session_commit();
			header("Location: welcome.php");
		}

		?>

		<table>
			<?php
			$i = 1;
			while($row = mysqli_fetch_array($res)){
				?> <tr>
				<td><?php echo $i;
				echo '. '; 
					echo $row['comments']; ?></td>
				</tr>
				<?php
				$i++;
			} ?>
		</table>
		<br><br>
		<form method = "POST">
			<input type = "submit" name = "Back" value = "Back" />
			<input type = "submit" name = "Home" value = "Home" />
			<input type = "submit" name = "Logout" value = "Logout" />
		</form>
	</center>
</body>
</html>
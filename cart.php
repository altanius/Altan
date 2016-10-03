<html>
<head>
	<title>
		<?php 
		session_start();
		$username = $_SESSION['user'];
		echo $username
		?> -Cart
	</title>
</head>
<body background = "foodOrder.png">
	<center><img  src = "order.jpg" >
		<br><br>
		<?php
		$con = mysqli_connect("istavrit.eng.ku.edu.tr", "asirel", "qRGZDD", "asirel_db");
		if(mysqli_connect_errno()){
			echo "Failed to connect, please check your username and password: ".mysqli_connect_error();
		}	

		$sql = "SELECT id from cart where uname = '$username' ";
		$res = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res);
		$cart_id = $row['id'];


		$sql = "SELECT I.name,I.price,J.times from item as I, includes as J  where I.item_id = J.item_id and J.cart_id = '$cart_id' ";
		$res = mysqli_query($con,$sql);
		$cost = 0;
		while($row = mysqli_fetch_array($res)){
			$pr =  $row['price'];
			$tm = $row['times'];
			$temp = $pr*$tm;
			$cost = $cost + $temp;

		}

		$sql = "UPDATE cart set cost = '$cost' where uname = '$username' ";
		$q = mysqli_query($con,$sql);

		$sql = "SELECT I.name,I.price,J.times,I.item_id from item as I, includes as J where I.item_id = J.item_id and J.cart_id = '$cart_id' ";
		$res = mysqli_query($con,$sql);

		$sql = "SELECT includes.menu_id from includes,item where includes.item_id = item.item_id and cart_id = '$cart_id' ";
		$res2 = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res2);
		$mid = $row['menu_id'];
		$sql = "SELECT name from restaurant where menu_id = '$mid' ";
		$res2 = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res2);
		$rest_name = $row['name'];




		if(isset($_POST['Delete'])){
			$id = $_POST['id'];
			$sql = "SELECT times from includes as I where item_id = '$id' and cart_id = '$cart_id' ";
			$result = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($result);
			$quan = $row['times'];
			if($quan>1){
				$quan = $quan -1;
				$sql = "UPDATE includes set times = '$quan' where item_id = '$id' and cart_id = '$cart_id' ";
				$q = mysqli_query($con,$sql);
			} else {
				$sql = "DELETE from includes where item_id = '$id' and cart_id = '$cart_id' ";
				$q = mysqli_query($con,$sql);
			}

			header('Refresh:0');
		}

		if(isset($_POST['order'])){
			$sql = "SELECT menu_id from includes where cart_id = '$cart_id' ";
			$res = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res);
			$mid = $row['menu_id'];
			$sql = "SELECT name from restaurant where menu_id = '$mid' ";
			$res = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res);
			$rest = $row['name'];
			$sql = "SELECT cost from cart where uname = '$username' ";
			$res = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res);
			$c = $row['cost'];
			$sql = "INSERT INTO orders values(NULL, '$username', '$rest', '$c', 'No'  ) ";
			$q = mysqli_query($con,$sql);
			$sql = "SELECT item_id,times from includes where cart_id = '$cart_id' ";
			$res = mysqli_query($con,$sql);
			while($row = mysqli_fetch_array($res)){
				$id = $row['item_id'];
				$tm = $row['times'];
				$sql = "UPDATE item set ord_count = ord_count+$tm where item_id = '$id' ";
				$q = mysqli_query($con,$sql);
			}
			$sql = "DELETE from includes where cart_id = '$cart_id' ";
			$q = mysqli_query($con,$sql);
			$_SESSION['order'] = 'ordered';
			header("Location: home_cl.php");
		}


		if(isset($_POST['return'])){
			header("Location: restaurant.php");
		}


		if(isset($_POST['home'])){
			header("Location: home_cl.php");
		}


		if(isset($_POST['logout'])){
			session_commit();
			header("Location: welcome.php");
		}

		?>
		<font color = "purple"> YOUR CART </font>
		<table>

			<tr>
				<td><font color = "green"> <u>ITEM NAME </u> </font></td> 
				<td></td>
				<td><font color = "green"> <u>PRICE </u> &nbsp;&nbsp;&nbsp;</font></td>
				<td><font color = "green"> <u>QUANTITY</u>&nbsp;&nbsp;&nbsp;</font></td>
				<td><font color = "green"><u>RESTAURANT</u></font></td>
			</tr>
			<tr><br></tr>
			<?php while($row = mysqli_fetch_array($res)){
				?>
				<tr>
					<td><form method = "POST"><?php echo $row['name']; ?></form></td>
					<td></td>
					<td><form method = "POST"><?php echo $row['price']; echo '$' ?></form></td>
					<td><form method = "POST"><?php echo $row['times']; ?></form></td>
					<td><form method = "POST"><?php echo $rest_name; ?></form></td>
					<td><form method = "POST"><input name = "Delete" type = "submit" value = "Delete Item" />
						<input name = "id" type = "hidden" value = <?php echo $row['item_id']; ?> /></form></td>
					</tr>
					<?php
				} 

				?>
			</table>
			<font color = "red">
			<?php    
			echo "<br>"; 
			echo "Total Cost :                     $cost".'$';
			?> </font> 
			<form method = "POST">
				<input type = "submit" name = "order" value = "Make Order" />
				<input type = "submit" name = "return" value = "Back" />
				<input type = "submit" name = "home" value = "Home" />
				<input type = "submit" name = "logout" value = "Logout" />
			</form>
		</center>

	</body>
	</html>
<html>
<head>
	<title>
		<?php
		session_start();
		$rest = $_SESSION['rest_name'];
		echo $rest;
		?>
	</title>
</head>

<body background = "foodOrder.png">
	<br><br><br>
	<?php

	$username = $_SESSION['user'];
	$con = mysqli_connect("istavrit.eng.ku.edu.tr", "asirel", "qRGZDD", "asirel_db");
	if(mysqli_connect_errno()){
		echo "Failed to connect, please check your username and password: ".mysqli_connect_error();
	}
	
	$type = $_SESSION['type'];
	if($type  == 'All'){
		$sql = "SELECT I.name,I.item_id,price from item as I, restaurant as R where R.menu_id = I.menu_id and R.name = '$rest' ";
	} else {
		$sql = "SELECT I.name,I.item_id,price from item as I, restaurant as R where R.menu_id = I.menu_id and R.name = '$rest' and I.type = '$type' ";
	}
	$res = mysqli_query($con,$sql);

	$sql = "SELECT menu_id from restaurant where name = '$rest' ";
	$result = mysqli_query($con,$sql);
	$row = mysqli_fetch_array($result);
	$m_id = $row['menu_id'];
	$sql = "SELECT max(ord_count) from item where menu_id = '$m_id' ";
	$result = mysqli_query($con,$sql);
	$row = mysqli_fetch_array($result);
	$top_val = $row['max(ord_count)'];
	if($top_val != 0){
		$sql = "SELECT name from item where ord_count = '$top_val' and menu_id = '$m_id' ";
		$result = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($result);
		$top_item = $row['name'];
	} else {
		$top_item = "Not available";
	}


	if(isset($_POST['Add'])){
		$id = $_POST['id'];
		$sql = "SELECT menu_id from item where item_id = '$id' ";
		$res = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res);
		$menu_id = $row['menu_id']; 
		$bool = True;
		$sql = "SELECT menu_id,item_id,times from cart as C,includes as I where C.uname = '$username' and C.id = I.cart_id ";;
		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($result)){
			if($row['menu_id'] != $menu_id){
				?>
				<script> alert ('Cant have items from different restaurants in your cart.'); </script>
				<?php
				$bool = False;
			}
			if($row['item_id'] == $id){
				$bool = False;
				$t = $row['times'];
				$t = $t+1;
				$sql = "UPDATE includes set times = '$t' where item_id = '$id' ";
				$reslt = mysqli_query($con,$sql);
			}
		}

		if($bool){
			$sql = "SELECT id from cart where uname = '$username'";
			$res = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res);
			$cid = $row['id'];
			$sql = "INSERT INTO includes values('$cid', '$id', '$menu_id', '1' )";
			$reslt = mysqli_query($con,$sql);

		}
		header('Refresh:0');
	}

	if(isset($_POST['filter'])){
		$_SESSION['type'] = $_POST['i_type'];
		header('Refresh:0');
	}

	if(isset($_POST['points'])){
		$sql = "SELECT count(id) from orders where client_name = '$username' and rest_name = '$rest' ";
		$res = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res);
		$q = False;
		if($row['count(id)'] == 0){
			?>
			<script> alert ('You cant give a ranking to a restaurant you never ordered from'); </script>
			<?php
		} else {
			$sql = "SELECT count(username) from rank where username = '$username' and rest_name = '$rest' ";
			$res = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res);
			if($row['count(username'] > 0){
				$nwr = $_POST['point'];
				$sql = "UPDATE rank set rank = '$nwr' where username = '$username' and rest_name = '$rest' ";
				$q = mysqli_query($con,$sql);

			} else { 	
				$pt = $_POST['point'];
				$sql = "INSERT INTO rank values('$rest', '$pt', '$username')";
				$q = mysqli_query($con,$sql);
			}		
		}

		if($q){
			$sql =  "SELECT AVG(rank) from rank where rest_name = '$rest' ";
			$res = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res);
			$avg = $row['AVG(rank)'];
			$sql = "UPDATE restaurant set rank = '$avg' where name = '$rest' ";
			$q = mysqli_query($con,$sql);
		}

		header('Refresh:0');
	}

	if(isset($_POST['comment'])){
		$sql = "SELECT count(client_name) from orders where rest_name = '$rest' and client_name = '$username' ";
		$rslt = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($rslt);
		$co = $row['count(client_name)'];
		if($co>0){ 
			$comment  = $_POST['comment_text'];
			$sql = "INSERT INTO comments values('$rest', '$comment') ";
			$q = mysqli_query($con,$sql);
		} else {
			?>
			-- <script> alert ('Cant comment about a restaurant you havent ordered from'); </script> 
			<?php
		} 
		header('Refresh:0');
	}

	if(isset($_POST['see'])){
		$_SESSION['rest_name'] = $rest;
		header("Location: comments.php");

	}

	if(isset($_POST['view'])){
		header("Location: cart.php");
	}

	if(isset($_POST['home'])){
		header("Location: home_cl.php");
	}

	if(isset($_POST['logout'])){
		session_commit();
		header("Location: welcome.php");
	}

	?>



	<center><img  src = "order.jpg" >
		<br><br>
		<font color = "green"> Here is the menu of <?php echo $rest; ?> </font>
		<br><br>
		<table>

			<tr>
				<td><font color = "purple"> <u>ITEM NAME</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td> 
				<td><font color = "purple"> <u>PRICE</u></font></td>
				<td><br></td>
			</tr>
			<?php  while($row = mysqli_fetch_array($res)){
				?>
				<tr>
					<td><form method = "POST"><?php echo $row['name']; ?></form></td>
					<td><form method = "POST"><?php echo $row['price']; echo '$' ?></form></td>
					<td><form method = "POST"><input name = "Add" type = "submit" value = "Add To Cart" /> 
						<input name = "id" type = "hidden" value = "<?php echo $row['item_id'];?>" /></input> </form></td> 
					</tr>
					<?php }  ?>

				</table>
				<br>
				
				<font color = "purple"> <i> Most selected item(s) : <?php echo $top_item; 
					while($row = mysqli_fetch_array($result)){
						echo ",";
						echo $row['name'];
					} ?> </i></font>
					<br><br>
					<form method = "POST">
						<select name = "i_type">
							<option selected = "selected" name = "All">All</option>
							<option name = "Soup">Soup</option>
							<option name = "Appetizer">Appetizer</option>
							<option name = "Salad">Salad</option>
							<option name = "Main Dish">Main Dish </option>
							<option name = "Fish">Fish</option>
							<option name = "Side">Side</option>
							<option name = "Drink">Drink</option>
						</select>
						<input type = "submit" name = "filter" value = "Filter" />
						&nbsp;
						<font color = "purple"> <i>Give points to this restaurant</i></font>
						&nbsp;
						<select name  = "point">
							<option name = "1">1</option>
							<option name = "2">2</option>
							<option name = "3">3</option>
							<option name = "4">4</option>
							<option name = "5">5</option>
							<option name = "6">6</option>
							<option name = "7">7</option>
							<option name = "8">8</option>
							<option name = "9">9</option>
							<option name = "10">10</option>
						</select>
						<input type = "submit" name = "points" value = "Rank" />
						<br><br>
						<textarea name = "comment_text">Enter your comment here...</textarea>
						<br>
						<input type = "submit" name = "comment" value = "Send Comments" />
						<input type = "submit" name = "see" value = "See Comments" />
						<br><br>
						<input type = "submit" name = "view" value = "View My Cart" />
						<input type = "submit" name = "home" value = "Home" />
						<input type = "submit" name = "logout" value = "Logout" />
					</form>
					<br>


				</center>

			</body>
			</html>
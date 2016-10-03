
<html>
<head>
	<title>Home-<?php 
		session_start();
		echo $_SESSION['user'];
		?></title>
	</head>
	<body background = "foodOrder.png">
		<?php
		$con = mysqli_connect("istavrit.eng.ku.edu.tr", "asirel", "qRGZDD", "asirel_db");
		if(mysqli_connect_errno()){
			echo "Failed to connect, please check your username and password: ".mysqli_connect_error();
		}
		$owner = $_SESSION['user'];
		$sql = "SELECT name from restaurant where owner = '$owner'";
		$res = mysqli_query($con,$sql);

		$sql = "SELECT * from districts";
		$res2 = mysqli_query($con,$sql);

		$sql = "SELECT rest_name,id from orders where is_delivered = 'No' and rest_name in (SELECT name from restaurant where owner = '$owner')";
		$res3 = mysqli_query($con,$sql);

		$sql = "SELECT count(id) from orders where is_delivered = 'No' and rest_name in (SELECT name from restaurant where owner = '$owner')";
		$res4 = mysqli_query($con,$sql);
		$is_empty = mysqli_fetch_array($res4);

		$sql = "SELECT rest_name,id from orders where is_delivered = 'No' and rest_name in (SELECT name from restaurant where owner = '$owner')";
		$res5 = mysqli_query($con,$sql);




		if(isset($_POST['Add'])){
			$restaurant = $_POST['restaurant'];
			$item_name = $_POST['item_name'];
			$price = $_POST['price'];
			$item_type = $_POST['item_type'];
			$sql = "SELECT menu_id from restaurant where name = '$restaurant'";
			$res3 = mysqli_query($con,$sql);
			$row1 = mysqli_fetch_array($res3);
			$menu_id = $row1['menu_id'];
			$sql = "INSERT INTO item values(NULL, '$menu_id', '$item_name', '$price', '$item_type', '0')";
			$bool = mysqli_query($con,$sql);
			if($bool){
				?><script>alert('Item added succesfully.');</script>
				<?php
			} else {
				?><script>alert('Item adding failed.');</script>
				<?php
			}			

		} 

		if(isset($_POST['add_district'])){
			$name = $_POST['restaurant'];
			$district = $_POST['district'];
			$sql = "INSERT INTO serves_to values('$name','$district', '60' )";

			$bool = mysqli_query($con, $sql);
			if($bool){
				?><script> alert ('District added');</script>
				<?php
			} else {
				?><script> alert ('Error occured while adding district');</script>
				<?php
			}
			header("Refresh:0");

		}

		if(isset($_POST['remove_district'])){
			$name = $_POST['restaurant'];
			$district = $_POST['district'];
			$sql = "DELETE from serves_to where district = '$district'";

			$bool = mysqli_query($con, $sql);
			if($bool){
				?><script> alert ('District removed');</script>
				<?php
			} else {
				?><script> alert ('Error occured while removing district');</script>
				<?php
			}
			header("Refresh:0");

		}

		if(isset($_POST['Delete'])){

			$restaurant = $_POST['restaurant'];
			$sql = "SELECT count(id) from orders where rest_name = '$restaurant' and is_delivered = 'No'";
			$res6 = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res6);
			$count = $row['count(id)'];

			if($count == 0){
				$sql = "DELETE from restaurant where name = '$restaurant'";
				$bool = mysqli_query($con,$sql);
				if($bool){
					?><script> alert ('Restraurant deleted..');</script>
					<?php
				} else {
					?><script> alert ('Couldnt delete restaurant');</script>
					<?php

				}
			} else {
				?><script> alert ('This restaurant has undelivered orders. Please make sure to deliver all orders before deleting a restaurant.');</script>
				<?php
			} 
			header("Refresh:0");

		}

		if(isset($_POST['New'])){ //owner ismini yolla headerla. Yeni sayfada seç hangi restorana district eklemek isedigini. add district die submit yarat
			$new_rest = $_POST['new_rest'];
			$sql = "INSERT INTO restaurant values('$owner', '$new_rest', NULL, '5')";
			$bool = mysqli_query($con,$sql);
			if($bool){
				?> <script>alert ('Restaurant added succesfully!');</script>
				<?php
			} else {
				?> <script> alert ('Couldnt add restaurant');</script>
				<?php
			} 
			header("Refresh:0");	
		}

		if(isset($_POST['Deliver'])){
			$id = $_POST['ids'];
			$sql = "UPDATE orders set is_delivered = 'Yes' where id = '$id' ";
			$bool = mysqli_query($con,$sql);
			if($bool){
				?> <script> alert ('Delivered');</script>
				<?php } else {
					?> <script> alert ('Delivery problem..');</script> 
					<?php
				} 
				header("Refresh:0");	 
			} 

			if(isset($_POST['Logout'])){
				session_commit();
				header("Location: welcome.php");
			}

			?>
			<center><img  src = "images.jpg" >
				<br><br><br>
				<font color = "blue" > Your restaurants <br> If you want to add an item to one of your restaurants just fill in the item information select the restaurant you want to add item to and press add. <br> If you want to delete a restaurant, select it and press delete (This will be done only if there are no undelivered orders belonging to that restaurant. To see your undelivered orders please look at the bottom of the page). <br> If you want to add a new restaurant please click on new after you filled the new restaurant info. After that you can add serving districts to your new/old restaurants. Enjoy your session  </font>
				<br>
				<br>
				<form method = "POST">  
					<select name = "restaurant">  <?php while($row = mysqli_fetch_array($res)){ ?>
						<option name = "<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option><?php } ?>
					</select>
				</select>
				&nbsp;&nbsp;&nbsp; 
				<select name = "district">
					<?php 
					while($row = mysqli_fetch_array($res2)){  
						?>
						<option value = "<?php echo $row['district_name']; ?>"> <?php echo $row['district_name'];?></option><?php } ?></select>
						&nbsp;&nbsp;&nbsp;
						<input type = "submit" name = "add_district" value = "Add District" />
						<input type = "submit" name = "remove_district" value = "Remove District" />
						<input type = "submit" name = "Delete" value = "Delete Restaurant" />
						<br>
						<input type = "text" name = "item_name" placeholder = "New Item Name" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type = "text" name = "price" placeholder = "Item Price" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<select name = "item_type">
							<option value = "Appetizer">Appetizer</option>
							<option value = "Salad">Salad</option> 
							<option value = "Main Dish">Main Dish</option> 
							<option value = "Fish">Fish</option> 
							<option value = "Side">Side</option> 
							<option value = "Drink">Drink</option>   
						</select>
						<input type = "submit" name = "Add" value = "Add Item" />
						<br>
						<br>
						<input type = "text" name = "new_rest" placeholder = "New Restaurant Name" />
						<input type = "submit" name = "New" value = "New" />
						<br><br><br>
						<?php if($is_empty>0){ ?>
						<font color = "red">These are the undelivered orders from your restraurants</font><br><br>
						<table align = "center" width = "25%" border = "0">
							<tr>
								<td><font color = "blue">Order ID</font></td>  <td><font color = "blue">Restaurant Name</font></td>
							</tr>
							<?php while($row = mysqli_fetch_array($res3)){
								?> <tr><td><?php echo $row['id'];?></td> <td><?php echo $row['rest_name'];?></td></tr>
								<?php }  ?> 
							</table>
							<?php } else {
								?> <font color = "Red"> You have no undelivered orders </font>
								<?php } ?>
								<br>
								<font color = "green">Pending Orders:&nbsp;&nbsp;&nbsp; </font>
								<select name = "ids"> 
									<?php while($row = mysqli_fetch_array($res5)){
										?> <option name = "<?php echo $row['id'];?>"><?php echo $row['id'];?></option>
										<?php } ?>
									</select>
									<input type = "submit" name = "Deliver" value = "Deliver">
									<br>
									<input type = "submit" name = "Logout" value = "Logout" />
								</form>
							</center>
						</body>
						</html>
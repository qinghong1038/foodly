<?php
session_start();
if(!isset($_SESSION['restaurant_log_email'])){
	header("location:index.php");
}
include 'connection.php';
$restaurant_log_email= $_SESSION['restaurant_log_email'];
if(isset($_POST['update'])){
	$item_name=$_POST['item_name'];
	$item_price=$_POST['item_price'];
	$item_discount=$_POST['item_discount'];
	$item_desc=$_POST['item_desc'];
	for($i=0;$i<sizeof($item_name);$i++){
		$q="SELECT name from menu where name='$item_name[$i]' and restaurant_id='$restaurant_log_email' ";
		$q1=mysqli_query($con,$q);
		$rowcount=mysqli_num_rows($q1);
		if(empty($item_name[$i]) || empty($item_price[$i]) || empty($item_discount[$i]) || empty($item_desc[$i]) || $rowcount>0) continue;
		$q="INSERT INTO menu (`restaurant_id`,`name`,`price`,`discount`,`description`) VALUES ('$restaurant_log_email','$item_name[$i]', '$item_price[$i]','$item_discount[$i]','$item_desc[$i]');";
		$q1=mysqli_query($con,$q);
    	
	}	
}
    if(isset($_POST['delete'])){
        $del_name=$_POST['del_name'];
        $q="DELETE FROM menu where restaurant_id='$restaurant_log_email' and name='$del_name' ;";
        mysqli_query($con,$q);
    }
    if(isset($_POST['line'])){
        $line=$_POST['line'];
        $q="UPDATE restaurants SET status='$line' where email='$restaurant_log_email' ;";
        mysqli_query($con,$q);
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Restaurant Sign Up</title>
    <link rel="shortcut icon" href="logo.png" type="image/png">
</head>
<body>
	<h3><?php echo $_SESSION['restaurant_log_name'];?></h3>
	<a href="logout.php"><button>logout</button></a><br>
    <?php
        $q="select status from restaurants where email='$restaurant_log_email';";
        $q1=mysqli_query($con,$q);
        $row=mysqli_fetch_array($q1);
        echo "You are currently ";
        echo ($row['status'] == 'Go Online') ? 'Online':'Offline';
        ?>
        <form method="post">
            <input type="submit" name="line" value="<?php echo ($row['status'] == 'Go Online') ? 'Go Offline':'Go Online'; ?>" >
        </form> 
       <?php
        $q="select * from orders where order_from='$restaurant_log_email';";
        $q1=mysqli_query($con,$q);
    ?>
    <br>active orders<br><br>
    <div>
        <?php
        while ($row=mysqli_fetch_array($q1)){
           if($row['status']!="delivered"){
            ?>
                <div>
                    order id:<?php echo $row['order_id']; ?>
                    <br>ordered by:<?php echo $row['order_by']; ?>
                    <br>items:<?php echo $row['rider']; ?>
                    <br>total:<?php echo $row['total']; ?>
                    <br>address:<?php echo $row['address']; ?>
                    <br>rider:<?php echo $row['rider']; ?>
                    <br>status:<?php echo $row['status']; ?>
                    <br>instance:<?php echo $row['instance']; ?>
                </div>
                <br>    
        <?php }
        }
        ?>
    </div>
    <br>past orders
    <div>
        <?php
        while ($row=mysqli_fetch_array($q1)){
            if($row['status']=="delivered"){?>
                <div>
                    order id:
                    ordered by:
                    items:
                    total:
                    instance:
                    address:
                    status:
                </div>    
        <?php }
        }
        ?>
    </div>
	<form method="post" >
       <div id="item_fileds">
           <div>
            <div class='label'>Item 1:</div>
            <div class="content">
                <span>item name:<input type="text" name="item_name[]" /></span>
                <span>Price: <input type="text" name="item_price[]" /></span>
                <span>Discount: <input type="text" name="item_discount[]" required maxlength="3"/></span>
                <span>Description: <input type="text" name="item_desc[]" /></span>
            </div>
           </div>
        </div>
        <input type="button" id="more_fields" onclick="add_fields();" value="+"/><br>
       	<input type="submit" name="update" value="Update">
    </form>

    <div>
    	<table>
    	<?php
    	$q="SELECT * FROM menu where restaurant_id='$restaurant_log_email'; ";
		$q1=mysqli_query($con,$q);
		$rowcount=mysqli_num_rows($q1);
		if ($rowcount>0) {
    	?>	
    	<tr><td><b>name</b></td><td><b>price</b></td><td><b>discount</b></td><td><b>description</b></td></tr></pre>
    	<?php
    			while ($row=mysqli_fetch_array($q1)) {
    				echo "<tr><td>".$row['name']."</td><td>".$row['price']."</td><td>".$row['discount']."</td><td>".$row['description']."</td><td>";?>
                    <form method="post">
                    <input type="text" name="del_name" value="<?php echo $row['name'] ;?>" hidden>
                    <input type="submit" name="delete" value="delete">
                    </form></td></tr>
    		<?php }
    		}
    		else{
    			echo "<b>List of items will be displayed here</b>";
    		}	
    	?>
    	</table>
    </div>
    <script type="text/javascript">    
    	var item = 1;
		function add_fields() {
   			item++;
   			var objTo = document.getElementById('item_fileds');
   			var divtest = document.createElement("div");
   			divtest.innerHTML = '<div class="label">Item ' + item +':</div><div class="content"><span>item name:<input type="text" name="item_name[]"/></span> <span>Price: <input type="text" name="item_price[]" /><span> Discount: <input type="text" name="item_discount[]" /></span></span> <span>Description: <input type="text" name="item_desc[]" /></span></div>';
    		objTo.appendChild(divtest);
		}
    </script>
</body>
</html>
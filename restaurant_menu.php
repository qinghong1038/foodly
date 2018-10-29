<?php
	session_start();
if(!isset($_SESSION['log_email'])){
	header("location:index.php");
}
include 'connection.php';
$restaurant= $_GET['restaurant'];
$_SESSION['cur_restaurant']=$restaurant;
$q="SELECT * FROM restaurants where email='$restaurant'; ";
$q1=mysqli_query($con,$q);
$rdetails=mysqli_fetch_array($q1);
?>
<!DOCTYPE html>
<html>
<head>
	<title>foodly - <?php echo $rdetails['name'];?></title>
    <link rel="shortcut icon" href="logo.png" type="image/png">
</head>
<body style="font-family: Roboto,Arial,sans-serif;">
	<a href="home.php"><button>home</button></a>
	<a href="logout.php"><button>logout</button></a>
	<h2><?php echo $rdetails['name'];?></h2>
    <p><?php echo $rdetails['status'];?></p>
	<p><a href="tel:<?php echo $rdetails['phone'];?>"><?php echo $rdetails['phone'];?></a></p>
	<p><a target="_blank" href="mailto:<?php echo $rdetails['email'];?>"><?php echo $rdetails['email'];?></a></p>
	<p>Address: <?php echo $rdetails['address'];?></p>
	<p>Description: <?php echo $rdetails['description'];?></p>
	<div>
    		<?php
    		$q="SELECT * FROM menu where restaurant_id='$restaurant'; ";
			$q1=mysqli_query($con,$q);
			$rowcount=mysqli_num_rows($q1);
			if ($rowcount>0) {
    		?>	
    		<table><tr><td><b>name</b></td><td><b>price</b></td><td><b>discount</b></td><td><b>description</b></td><td><b>quantity</b></td></tr></pre>
    		<?php
    			while ($row=mysqli_fetch_array($q1)) {
    				$n=$row['sno'];
    				echo "<tr><td>".$row['name']."</td><td>".$row['price']."</td><td>".$row['discount']."</td><td>".$row['description']."</td><td>
    				<button onclick='remove_item(".$n.")'>-</button>
    				 <span class='buy' id='".$n."'>0</span> 
    				 <button onclick='add_item(".$n.")'>+</button>
    				 </td></tr>";
    			} ?>
    		</table>
            <?php if ($rdetails['status']=="Online") { ?>
    		<button  onclick="view_cart(<?php echo $rowcount; ?>)">Proceed to cart</button><?php }
    		}
    		else{
    			echo "<b>List of items will be displayed here</b>";
    		}	
    		?>
    	
    </div>
    <script type="text/javascript">
    	function add_item(cur_id){
    		var quan=document.getElementById(cur_id).innerHTML;
    		if(quan<10)
    			document.getElementById(cur_id).innerHTML=++quan;
    	}
    	function remove_item(cur_id){
    		var quan=document.getElementById(cur_id).innerHTML;
    		if(quan>0)
    			document.getElementById(cur_id).innerHTML=--quan;
    	}
    	function view_cart(n){
    		var j=0;
    		var str="?";
    		for (var i=0;i<n;i++) {
    			var nam = document.getElementsByClassName("buy")[i];
    			var quant=nam.innerHTML;
    			var name=nam.id;
    			if(quant>0) {
    				str+="item"+j+"="+name+"&quantity"+j+"="+quant+"&";
    				j++;
    			}
    		}
    		str+="count="+j;
            if(j>0)
    		window.location.href = "view_cart.php"+str;
    	}
    </script>

</body>
</html>
<?php 
session_start();
ob_start();
// error display configuration
error_reporting(E_ALL & ~E_NOTICE);

if(!isset($_SESSION['emaill'])){
	header('location: nhis_form.php');
}
//This block grabs the whole list for viewing
$msg="";
$email= $_SESSION['emaill'];
include "../dbconnect2.php";
$sql="SELECT first_name, id FROM `nhis_form` WHERE `emailAdd`='$email' LIMIT 1";
$check = mysqli_query($dbconnect, $sql);
$resultCount=mysqli_num_rows($check); //count the out amount 
if($resultCount>0){
	while($row=mysqli_fetch_array($check)){
		$id=$row["id"];
		$name=$row["first_name"];
	}
}else{
	$msg="<p style='color: red; text-align: center'>You have no Information yet in the Database</p>";
}

// sum the total earn by current user 
$sum = "";
$dynamic_list = "";
 if (isset($_POST['sum']))
 {
	$to_date = $_POST['to_date'];
	$from_date = $_POST['from_date'];
	if (isset($_POST['from_date']) && ($_POST['to_date'])){
	
		// display the specified information
		$display = "SELECT * FROM `cs_of_trt` WHERE `date` >= '$from_date' AND `date` <= '$to_date'";
		$displayCheck = mysqli_query($dbconnect, $display) or die (mysqli_error($dbconnect));
		$displayResult = mysqli_num_rows($displayCheck);
		if ($displayResult > 0){
			while($row=mysqli_fetch_assoc($displayCheck)){
				$date = $row['date'];
				$hmo = $row['hmo'];
				$amt = $row['amount3'];
				
				
				
				$dynamic_list .= "<tr>";
				$dynamic_list .= "<td style='background-color:#CECECE; text-align: center; font-family: Adobe Heiti Std R;'>" .$date . "</td>";
				$dynamic_list .= "<td style='background-color:#CECECE; text-align: center; font-family: Adobe Heiti Std R;'>".$hmo . "</td>";
				$dynamic_list .= "<td style='background-color:#CECECE; text-align: center; font-family: Adobe Heiti Std R;'>".$amt . "</td>";
				$dynamic_list .= "</tr>";
				

			}
		} else {
			$msg="<center><p style = 'color: #D8000C; background-color: #FFBABA; border-radius:.5em; width: 400px; border: 1px solid #D8D8D8; padding: 5px; border-radius: 5px; font-family: Arial; font-size: 11px; text-transform: uppercase; text-align: center; text-transform: uppercase; padding-left: 12px'>No result found</p></center>";
		}

		$sum = "SELECT  SUM(`amount3`) AS total_sum1 FROM `cs_of_trt` WHERE `date` >= '$from_date' AND `date` <= '$to_date'";
		$sum2 = mysqli_query($dbconnect, $sum) or die (mysqli_error($dbconnect));
		$result1 = mysqli_fetch_array($sum2);
		$sum_total = $result1['total_sum1'];
		$_SESSION['sum_total'] = $sum_total; 
	} else {
		?><script type="text/javascript">
		alert ('Please enter date');
		window.location = 'cost_of_treatmentInc.php';
		</script><?php
	}
}

?>
<!doctype html>
<html>
<link href="http://localhost/buth_net/pharmacySection/css/buth_net.css" rel="stylesheet" type="text/css">
<head>
<meta charset="utf-8">
<link rel="shortcut icon" href="/favicon.ico" >
<title>Income Summary</title>
</head>
<body>
<?php
include_once "../header.php";
?>
<div id="container">
  <div id="sidebar1"><br>
    <p class="subHeader">Menu</p>
    <ul id="navigation2">
<li class="page_title">Account Unit</li><br>
	  <li><a href="http://localhost/buth_net/index.php">Main Page</a></li><br>
	  <li><a href="nhis_home.php">Home Page</a></li><br>
	  <li><a href="register.php">Register Enrollee List</a></li><br>
	  <li><a href="nhis_income.php">NHIS Income</a></li><br>
	  <li><a href="cost_of_treatment.php" id="cost">Cost of Treatment</a></li>
	  <li><a href="cost_of_treatmentInc.php" id="cost2" style="display: none">Inc. Summary</a></li><br>
	  <li><a href="../acc_logout.php">Logout</a></li><br>
    </ul>
      <!-- end .sidebar1 --></div>
     <div class="margin" id="content">
	 	   <h1 style='text-align: center; font-family: tahoma; font-size: 16px; text-transform: uppercase; font-weight: bold; background-color: #000000; color: #CECECE'>Welcome <?php echo $name."!" . " "; ?>What would you like to do today?</h1><br>
	   <?php echo $msg; ?>
	  <div class="search_angle">
		<div class="total_earn">
		 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<h2 style='margin-top: auto; font-family: Calibri (Body); font-size: 18px; font-weight: bold; text-transform: uppercase; font-style: normal; color: #FFFFFF'>cost of treatment income summary</h2>
			<center><label style="font-family: monospace; font-size: 16px; font-weight: bold" >From</label>
			<input name="from_date" class="userarea0" type="text"  style="width: 150px" placeholder="YYYY-MM" value="<?php if (isset($_POST['sum'])){echo $from_date;} ?>">
			<label style="font-family: monospace; font-size: 16px; font-weight: bold">To</label>
			<input name="to_date" class="userarea0" type="text"  style="width: 150px" placeholder="YYYY-MM" value="<?php if (isset($_POST['sum'])){echo $to_date;} ?>"></center><br>
		<center><input type="submit" name="sum" class="submit4" value="Sum"></center><br>
		</div>
	   </div><br><br>
		<div class="product_formlist">
	  <table width="500px" style="margin-left: auto; margin-right: auto" cellpadding="5" cellspacing="3" border="1">
	   <tr>
	    <td width="26%" style='background-color:#C5DFFA; font-family: arial black; text-align: center; font-size: 14px'><b>Date</b></td>
	    <td width="37%" style='background-color:#C5DFFA; font-family: arial black; text-align: center; font-size: 14px'><b>HMO</b></td>
		<td width="37%" style='background-color:#C5DFFA; font-family: arial black; text-align: center; font-size: 14px'><b>Amount N</b></td>
		
		</tr>
		<?php echo $dynamic_list; ?>
		<!--tr>
		 <td>&nbsp;</td>
		 <td>&nbsp;</td>
		 <td>&nbsp;</td>
		 <td>&nbsp;</td>
		 <td>&nbsp;</td>
		</tr-->
		</table><br><br>
			<center><h3 class="heading_text">Total Income</h3></center>
			<center><input type="text"  id="total_earn_area" disabled="disabled" value="<?php if (isset($_POST['sum'])){echo "N".$sum_total;} ?>"></center><br>
		</form>
		 <form action="cost_of_treatmentRec.php?from=$from_date&to=$to_date" method="GET" id="jsform" target="_blank">
		<input type="hidden" name="from_date" value="<?php echo $from_date; ?>">
		<input type="hidden" name="to_date" value="<?php echo $to_date; ?>">
		<center><input type="button" onclick="document.getElementById('jsform').submit();" class="submit4" value="Print Receipt"></center>
	  </form><br>
	   <!-- end .content --></div>
	  <!-- end .container --></div>
	  </div>
     <?php include_once "../footer.php"; ?>
	 
</body>
</html>
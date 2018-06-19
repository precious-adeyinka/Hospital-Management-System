<?php
$msg = "";
if (isset($_POST['login'])){
	include_once "dbconnect.php";
	$password = $_POST['passWord'];
	$email = $_POST['email'];
	$last_log_date = date('Y-m-d H:i:s');
	if (empty($email && $password) == false){
		$sql = "SELECT * FROM nursing_admin WHERE `admin_email`='".$email."'";
		$check = mysqli_query($dbconnect, $sql) or die (mysqli_error($dbconnect));
		$result = mysqli_num_rows($check);
		if ($result > 0)
		{
			$row = mysqli_fetch_array($check);
			if (password_verify($password, $row['admin_pass']))
			{
				session_start();
				$_SESSION['emaill'] = $email;
				header('location: viewstudents.php');
				$sql = "UPDATE `nursing_admin` SET `last_log_date`='$last_log_date' WHERE `admin_email`='".$email."'";
				$check2 = mysqli_query($dbconnect, $sql) or die (mysqli_error($dbconnect));
			} else
			$msg = "<p style='color: #880000; padding-left: 0px'><span class='successbtn'><img src='../pharmacySection/images/error.png' alt='error' width='22px' height='22px'></span>Error: Invalid password</p>";
		} else
		$msg = "<p style='color: #880000; padding-left: 0px'><span class='successbtn'><img src='images/error.png' alt='error' width='22px' height='22px'></span>Error: Invalid Email or Password</p>";
	}else
	$msg = "<p style='color: #880000; padding-left: 0px'><span class='successbtn'><img src='images/error.png' alt='error' width='22px' height='22px'></span>Please enter and password</p>";
}
?>
<!doctype html>
<html>
<link href="http://localhost/buth_net/pharmacySection/css/buth_net.css" rel="stylesheet" type="text/css">
<head>
<meta charset="utf-8">
<link rel="shortcut icon" href="/favicon.ico">
<title>Admin Login Page</title>
</head>
<body>
<?php
include_once "header.php";
?>
<div id="container">
  <div id="sidebar1"><br>
    <p class="subHeader">Menu</p>
    <ul id="navigation2">
		<li class="page_title">Admin Unit</li><br>
		<li><a href="http://10.40.255.5/buth_net/index.php">Main Page</a></li><br>
		<li><a href="logout.php">Logout</a></li><br>
    </ul>
      <!-- end .sidebar1 --></div>
     <div class="margin" id="content">
	  <div class="login_form">
	   <fieldset>
		 <legend>Admin Login Section</legend>
		  <form action="" method="post">
		  <?php
		   echo $msg;
		  ?>
		    <p style='padding-left: 12px; font-family: impact; font-size: 14px; text-style: italic; color: #880000'>This page is restricted for non admin users</p>
		   <label class="labelEmail"><span class='successbtn'><img src="../pharmacySection/images/personal.png" alt="email" width="22px" height="22px"></span>Email</label>
		    <p><input type="text" id="email" name="email" autofocus></p>
			<label class="labelEmail"><span class='successbtn'><img src="../pharmacySection/images/pass.ico" alt="email" width="22px" height="22px"></span>Password</label>
		    <p><input type="password" id="passkey" name="passWord"></p>
			<input type="submit" value="Login" name="login" id="login22">
			<input type="submit" value="Forgot Password" name="forgotpass" id="forgotpass2">
			  </form>
			 </fieldset>
		    </div>
			   <!-- end .content --></div>
			  <!-- end .container --></div>
     <?php
      include_once "footer.php";
     ?>
</body>
</html>
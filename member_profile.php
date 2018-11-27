<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}
include('db.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>
function prtfun()
{
window.print();	
}
</script>
<title>Member Profile</title>
    <!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
<style>

* {
  box-sizing: border-box;
}

.row {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  -ms-flex-wrap: wrap; /* IE10 */
  flex-wrap: wrap;
  margin: 0 -16px;
}

.col-25 {
  -ms-flex: 25%; /* IE10 */
  flex: 25%;
}

.col-50 {
  -ms-flex: 50%; /* IE10 */
  flex: 50%;
}

.col-75 {
  -ms-flex: 75%; /* IE10 */
  flex: 75%;
}

.col-25,
.col-50,
.col-75 {
	float: left;
	padding-top: 0;
	padding-right: 16px;
	padding-bottom: 0;
	padding-left: 16px;
}

.container {
  background-color: #f2f2f2;
  padding: 5px 20px 15px 20px;
  border: 1px solid lightgrey;
  border-radius: 3px;
}
.btn_icon {
    background-color:#0f2453;
    border: none;
    color: white;
    padding: 9px 13px;
	display: inline-block;
    font-size: 14px;
    cursor: pointer;
	border-radius: 12px;
}
.btn_icon:hover{
text-decoration: none;	
background-color: #ffce14;
color:#0f2453;
}

input[type=text] {
	width: 90%;
	margin-bottom: 20px;
	padding: 5px;
	border: 1px solid #ccc;
	border-radius: 3px;
}

label {
	margin-bottom: 3px;
	display: block;
}

.icon-container {
  margin-bottom: 20px;
  padding: 7px 0;
  font-size: 24px;
}

.flat-table{
width:100%;
display: block;
}

table, th, td {
    border-collapse: collapse;
	
}
th{
background:#0f2453;
color:white;
}
th, td {
    border: 1px solid black;
    padding: 15px;
    text-align: left;
}



/* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (also change the direction - make the "cart" column go on top) */
@media (max-width: 800px) {
  .row {
    flex-direction: column;
  }
  .col-25 {
    margin-bottom: 20px;
  }
}
</style>
</head>
<body>

    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <!---<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>-->
                <a class="navbar-brand" href="home.php"> <?php echo $_SESSION["user"]; ?> </a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
					<li>
                        <a href="home.php"><i class="fa fa-home"></i> Home</a>
                    </li>
                        <li class="divider"></li>
					<li>
                        <a href="addmember.php"><i class="fa fa-bar-chart-o"></i> Membership Form</a>
                    </li>
                        <li class="divider"></li>
                    <li>
                        <a href="memberactivity.php"><i class="fa fa-qrcode"></i> Membership Activity</a>
                    </li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </nav>
        <!--/. NAV TOP  -->

        <div id="page-wrapper" style="min-height: auto; padding-left:0; padding-right:0;">
            <div id="page-inner" style="min-height: auto;">

<div class="row">
  <div class="col-75">
    <div class="container">
      <form name="form1" action="" method="post">
<?php
if($_GET["mtp_id"] != "")
{
$mtp_data="SELECT * FROM mem_profile where mtp_id = '$_GET[mtp_id]'";
$rs = mysqli_query($con,$mtp_data);	
$data = mysqli_fetch_array($rs);	
$mtp_activation_data="SELECT * FROM mem_activate where mtp_id = '$_GET[mtp_id]' and mem_type_status = 'Active'";
$rs_activ = mysqli_query($con,$mtp_activation_data);	
$data_activ = mysqli_fetch_array($rs_activ);		
$date_check = date("Y-m-d");
$valid_till = $data_activ["valid_till"];
$renewal_days = round(abs(strtotime($date_check) - strtotime($valid_till))/86400);
$query = "select a1.*, SUM(a2.no_days) as no_of_days from mem_activate as a1 LEFT JOIN mem_activity as a2 on a1.mtp_id = a2.mtp_id and a2.activity_date between a1.activated_on and a1.valid_till where a1.mem_type_status = 'Active' and a1.mtp_id = '$_GET[mtp_id]'";
$check_query = mysqli_query($con,$query);
$check_details = mysqli_fetch_array($check_query,MYSQLI_ASSOC);
$no_of_days=$check_details['no_of_days'];
$no_of_stays=$check_details['no_stays'];				
$query1 = "select a1.*, a2.* from mem_activate as a1 JOIN mem_activity as a2 on a1.mtp_id = a2.mtp_id and a2.activity_date between a1.activated_on and a1.valid_till where a1.mem_type_status = 'Active' and a1.mtp_id = '$_GET[mtp_id]'";
$check_query1 = mysqli_query($con,$query1);
} ?>      
        <div class="row">
          <div class="col-50"><br />
            <h3>Personal Information</h3><br />
            <div class="row">
              <div class="col-50">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo $data["mem_name"]; ?>">
              </div>
			  <div class="col-50">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" value="<?php echo $data["mem_email"]; ?>">
              </div>
			  <div class="col-50">
                <label for="gender">Gender</label>
                <input type="text" id="0gender" name="gender" value="<?php echo $data["mem_gender"]; ?>">
              </div>
			  <div class="col-50">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="<?php echo $data["mem_mobile"]; ?>">
              </div>
			  <div class="col-50">
                <label for="homephone">Home Phone </label>
                <input type="text" id="homephone" name="homephone" value="<?php echo $data["mem_landline"]; ?>">
              </div>
			  <div class="col-50">
                <label for="address">Address </label>
                <input type="text" id="address" name="address" value="<?php echo $data["mem_address"]; ?>">
              </div>
			  <div class="col-50">
                <label for="city">City </label>
                <input type="text" id="city" name="city" value="<?php echo $data["mem_city"]; ?>">
              </div>
			  <div class="col-50">
                <label for="proof_type">ID Proof Type</label>
                <input type="text" id="proof_type" name="proof_type" value="<?php echo $data["mem_idproof"]; ?>">
              </div>
			  <div class="col-50">
                <label for="proof_number">ID Proof Number</label>
                <input type="text" id="proof_number" name="proof_number" value="<?php echo $data["mem_proof_no"]; ?>">
              </div>
			  <div class="col-50">
                <label for="dob">DOB</label>
                <input type="text" id="dob" name="dob" value="<?php echo date("d/m/Y", strtotime($data["mem_dob"])); ?>">
              </div>

            </div>
          </div>

          <div class="col-50"><br /><br />
            <h3 class="row">Member Activities</h3><br />
            
<div class="row">
	<div class="table-responsive">
		<table class="flat-table">
		  <tr>
			<th>No</th>
			<th>Hotel</th>
			<th>Check In</th>
			<th>Check Out</th>
			<th>Persons</th> 
		 </tr>
<?php
$i = 1;
while($row = mysqli_fetch_array($check_query1,MYSQLI_ASSOC))
{ ?>		 
<tr>
  <td><?php echo $i; ?></td>
  <td><?php echo $row["hotel_name"]; ?></td>
  <td><?php echo date("d/m/Y", strtotime($row["activity_date"])); ?></td>
  <td><?php echo date("d/m/Y", strtotime($row["to_date"])); ?></td>
  <td><?php echo $row["no_persons"]; ?></td>
</tr>
<?php $i++; } ?>		  
		</table>
	</div>
	
</div><br>

<h3 class="row">Membership Details</h3><br />

<div class="row">

<div class="table-responsive">

<table class="flat-table">
  <tr>
    <th>Membership Type</th>
    <th>Fee Paid</th>
    <th>Payment Mode</th> 
    <th>Payment Date</th>
  </tr>
  <tr>
    <td><?php echo $data_activ["mem_type"]; ?></td>
    <td><?php echo $data_activ["mem_amt"]; ?></td>
    <td><?php echo $data_activ["payment_mode"]; ?></td>
    <td><?php echo date("d/m/Y", strtotime($data_activ["activated_on"])); ?></td>
  </tr>
</table>
</div>

</div>
			<div class="row">
              <div class="col-50">
                <label for="points_left"><br>
                Points Left</label>
                <input type="text" id="points_left" name="points_left" value="<?php echo $no_of_stays - $no_of_days; ?>" readonly>
              </div>
			  <div class="col-50">
                <label for="days_remaining"><br>
                Days Remaining for Renewal</label>
                <input type="text" id="days_remaining" name="days_remaining" value="<?php echo $renewal_days; ?>" readonly>
              </div>
			<div>
			  <!--<button class="btn_icon"><i class="fa fa-edit"></i> Edit</button>-->
              <a href="home.php" class="btn_icon"><i class="fa fa-backward"></i> Back</a>
              <button class="btn_icon"><i class="fa fa-save"></i> Save</button>
              <!-- <a class="btn_icon">&laquo; Previous</a>
              <a class="btn_icon">Next &raquo;</a>
			  <button class="btn_icon"><i class="fa fa-download"></i> Download</button> -->
              <button class="btn_icon" onclick="prtfun();"><i class="fa fa-print"></i> Print</button>
            </div>
          </div>
          
        </div>
        

      </form>
    </div>
  </div>
  
  </div>
</div>

            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->

    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- Morris Chart Js -->
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
    <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>

</body>
</html>

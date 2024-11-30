<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    include "app/Model/Notification.php";
    // include "app/Model/User.php";

    $notifications = get_all_my_notifications($conn, $_SESSION['id']);

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Notifications</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="CSS/style.css">

</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php" ?>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<section class="section-1">
			<h4 class="title">Toutes les notifications</h4>
			<?php if (isset($_GET['success'])) {?>
      	  	<div class="success" role="alert">
			  <?php echo stripcslashes($_GET['success']); ?>
			</div>
		<?php } ?>
			<?php if ($notifications != 0) { ?>
			<table class="main-table">
				<tr>
					<th>#</th>
					<th>Message</th>
					<th>Type</th>
					<th>Date</th>
				</tr>
				<?php $i=0; foreach ($notifications as $notification) { ?>
				<tr>
					<td><?=++$i?></td>
					<td><?=$notification['message']?></td>
					<td><?=$notification['type']?></td>
					<td><?=$notification['created_at']?></td>
				</tr>
			   <?php	} ?>
			</table>
		<?php }else { ?>
			<h3>Vous n'avez pas de notifications</h3>
		<?php  }?>
			
		</section>
	</div>


<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(4)");
	active.classList.add("active");


</script>


</body>
</html>
<?php }else{ 
   $em = "Veuillez vous connecter d'abord";
   header("Location: login.php?error=$em");
   exit();
}
 ?>
<?php
	$connect = mysqli_connect('localhost','root','','DULIEU');
	mysqli_set_charset($connect,"utf8");
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Devpro.edu.vn</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
 
	<!-- 'start thực hiện kiểm tra dữ liệu người dùng đăng ký' -->
	<?php
		if(isset($_POST["dangky"])){
						
			$user_name = $_POST["user_name"];
			$pass1 = $_POST["pass1"];
			$pass2 = $_POST["pass2"];
			
			
			
			//kiểm tra xem 2 mật khẩu có giống nhau hay không:
			if($pass1!=$pass2){
				header("location:index.php?page=dangky");
				setcookie("error", "Đăng ký không thành công!", time()+1, "/","", 0);
			}
			else{
				$pass = $pass1;
				mysqli_query($connect,"
					insert into admin (username,password)
					values ('$user_name','$pass');
				");
				header("location:index.php?page=dangky");
				setcookie("success", "Đăng ký thành công!", time()+1, "/","", 0);
			}
		}
 
	?>
	<!-- 'start thực hiện kiểm tra dữ liệu người dùng nhập ở form đăng nhập' -->
	<?php
		if(isset($_POST["dangnhap"])){
			$tk = $_POST["user_name_lg"];
			$mk = $_POST["passlg"];
			$sql = "select * from admin where username = '$tk' and password = '$mk' " ;
			$rows = mysqli_query($connect,$sql);
			$count = mysqli_num_rows($rows);
			$data = mysqli_fetch_array($rows);
			if($count==1&& isset($data)){
				$_SESSION["loged"] = true;
				
				header("location: danhsach_view.php");
				setcookie("success", "Đăng nhập thành công!", time()+1, "/","", 0);
			}
			else{
				header("location:index.php");
				setcookie("error", "Đăng nhập không thành công!", time()+1, "/","", 0);
			}		
		}
	?>
 
	<!-- 'start thực hiện đăng xuất' -->
	<?php
		if(isset($_GET["act"])&&$_GET["act"]=="logout"){
			unset($_SESSION["loged"]);
			header("location:index.php");
			setcookie("success", "Bạn đã đăng xuất!", time()+1, "/","", 0);
		}
	?>
	<!-- end thực hiện đăng xuất -->
 
	<div class="container">
		<div class="row">
			<a href="index.php?page=dangky" class="btn btn-success">Đăng ký</a>
			<a href="index.php" class="btn btn-info">Trang chủ</a>
			<?php if(isset($_SESSION["loged"])) echo "<a href='index.php?act=logout' class='btn btn-danger'>Đăng xuất</a>"; ?>
		</div>
 
		<div class="row">
			<!-- 'start nếu xảy ra lỗi thì hiện thông báo:' -->
			<?php
				if(isset($_COOKIE["error"])){
			?>
			<div class="alert alert-danger">
			  	<strong>'Có lỗi!'</strong> <?php echo $_COOKIE["error"]; ?>
			</div>
			<?php } ?>
			<!-- 'end nếu xảy ra lỗi thì hiện thông báo:' -->
 
 
			<!-- 'start nếu thành công thì hiện thông báo:' -->
			<?php
				if(isset($_COOKIE["success"])){
			?>
			<div class="alert alert-success">
			  	<strong>'Chúc mừng!'</strong> <?php echo $_COOKIE["success"]; ?>
			</div>
			<?php } ?>
			<!-- 'end nếu thành công thì hiện thông báo:' -->
 
 
 
 
			
			<?php
			//nếu tồn tại biến $_GET["page"] = "dangky" thì gọi trang đăng ký:
			if(isset($_GET["page"])&&$_GET["page"]=="dangky")
				include "register.php";
 
 
			//nếu không tồn tại biến $_GET["page"] = "dangky"
			if(!isset($_GET["page"])){
				//nếu tồn tại biến session $_SESSION["loged"] thì gọi nội dung trang admin.php vào
				if(isset($_SESSION["loged"]))
					include "admin.php";
				//nếu không tồn tại biến session $_SESSION["loged"] thì gọi nội dung trang login.php vào
				else
					include "login.php";
			}
			?>
		</div>
 
	</div>
</body>
</html>
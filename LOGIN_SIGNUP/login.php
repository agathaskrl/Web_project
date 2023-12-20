<?php
session_start();
include_once 'connect_db.php'; 
if(isset($_POST['username']))
{
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password =  mysqli_real_escape_string($conn,$_POST['password']); 

    $sql = "SELECT * FROM user WHERE username='$username' AND password='$password' AND role='CITIZEN' "; 
    $result = mysqli_query($conn, $sql);
	$resultCheck = mysqli_num_rows($result);
    
    $sql2="SELECT * FROM user WHERE username='$username' AND password='$password' AND role='ADMIN' ";
    $result2=mysqli_query($conn, $sql2);
    $resultCheck2 = mysqli_num_rows($result2); 

    $sql3="SELECT * FROM user WHERE username='$username' AND password='$password' AND role='SAVIOR' ";
    $result3=mysqli_query($conn, $sql3);
    $resultCheck3 = mysqli_num_rows($result3); 
    
	if($resultCheck > 0 ){
        $_SESSION['username'] = $username;
		header("Location: index.php");
	}
    else{
		echo "<script>alert('Wrong username or password!'); </script>";
        
	}

    if($resultCheck2 > 0)
    {
        $_SESSION['username'] = $username;
		header("Location: adindex.php"); //edw tha mpei h main selida tou admin
    }
    else{
    echo "<script>alert('Wrong username or password!'); </script>"; 
    }  
    
    if($resultCheck3 > 0)
    {
        $_SESSION['username'] = $username;
		header("Location: asindex.php"); //edw tha mpei h main selida tou admin
    }
    else{
    echo "<script>alert('Wrong username or password!'); </script>"; 
    }


}

?>

<!DOCTYPE html>
<html lan= "en" and dir="Itr">
 <head>
    <meta charset = "utf-8">
    <title> Login </title> 
    <link rel="stylesheet" href="logstyle.css?v=1">
    <scipt src="login.js"> </scipt> 
    
</head>

<body> 
<form class="box" method="POST" action="login.php"> 
<h1> 
    Login
</h1>

<input type="text" name="username" placeholder="Username" id="username" required > 
<input type="password" name="password" placeholder="Password" id="password"  required>
<input type="submit" name="" value="Login" >
<p class="massage"> <small> Not Registered? <a href="signup.php"> Register now! </small></a> </p>

</form>
</body>

</html>
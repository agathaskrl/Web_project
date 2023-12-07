<?php
session_start();
include_once 'connect_db.php'; 
?>
 
<!DOCTYPE html>
<html lan= "en" and dir="Itr">
 <head>
    <meta charset = "utf-8">
    <title> Sign up </title> 
    <link rel="stylesheet" href="adstyle.css?v=3">
    <script src="signup.js"></script>
    </head>
    <div class="main">
            <div class="navbar">
            <ul> 
                    <li><a href="adindex.php">HOME</a></li>  
                        <li><a href="warehouse.php">WAREHOUSE</a></li>
                        <li><a href="statistics.php">STATISTICS</a></li>
                        <li><a href="savsignup.php">CREATE ACCOUNT</a></li>
                        <li><a href="announcement.php">ANNOUNCEMENT</a></li>
                        <li><a href="logout.php">LOG OUT</a></li>              
                    </ul>
                </div>
            
            </div>
    
<body> 
    <br>
<button onclick="history.back()"> <i class="arrow left"></i>Go Back</button>
<form class = "box" method="POST">
    <h1> 
      Create an account
    </h1>

      <div class="input-box">
      <span class="details">Username</span>
      <input type="text" name="username" placeholder="Username" id="username" required>
      </div>

      <div class="input-box">
        <span class="details">Name</span>
        <input type="text" name="name" placeholder="Name" id="name" required> 
      </div>

       <div class="input-box">
        <span class="details">Surname</span>
        <input type="text" name="surname" placeholder="Surname" id="surname" required> 
    </div>

    <div class="input-box">
        <span class="details">Phone</span>
        <input type="number" name="phone" placeholder="Phone" id="phone" required > 
    </div>

    <div class="input-box">
        <span class="details">Password</span>
         <input type="password" name="password" placeholder="Password" id="password" min="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[!@#\$%\^&\*])(?=.*[A-Z]).{8,}" title="The password must contain at least one number one symbol one uppercase and one lowercase letter and must be at least 8 characters" required> 
    </div>  
    <div class="input-box">
    <span class="details">Role</span>
    <input name="savior" placeholder="Savior" id="savior" readonly> 
   </div> 

   <br>

   <div class="checkbox">
    <input type="checkbox" onclick="showpass()"> Show Password<br> 
   </div>
   
   <br>   

   <div class="button">
        <input type="submit" value="Create account">
    </div>

</form>

</body>


<?php
if(isset($_POST['phone']))
{
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $phone = intval($_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 
    $select = "SELECT * FROM user WHERE username='$username' && phone='$phone'"; 
    $result = mysqli_query($conn, $select);
    $resultCheck = mysqli_num_rows($result);

    if($resultCheck > 0)
    { 
        echo "<script>alert('User already exists!'); window.location.href = 'signup.php';</script>";
    } 
    else{
        $insert = "INSERT INTO user(username,name,surname,phone,password,role) VALUES('$username','$name','$surname','$phone','$password','SAVIOR')";
        mysqli_query($conn, $insert);
        echo "<script>alert('Account has been created!'); window.location.href = 'admindex.php';</script>";
        
    }

};
?>
</body>

</html>

<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Edit Warehouse</title>
        <link rel="stylesheet" href="adstyle.css?v=7">
    </head>
    <body>     
    
   
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
                </head>
<body>
<?php


    session_start();

?>


<form class = "box" method="POST">
    <h1> 
      Edit Warehouse
    </h1>

      <div class="input-box">
      <span class="details">Item</span>
      <input type="text" name="item" placeholder="Item" id="item" required>
      </div>

      <div class="input-box">
      <span class="details">Category</span>
      <input type="text" name="category" placeholder="Category" id="cateogry" required>
      </div>

      <div class="input-box">
        <span class="details">Quantity</span>
        <input type="number" name="name" placeholder="Quantity" id="quantity" required  min="0"> 
      </div>

      <div class="input-box">
        <span class="details">Details</span>
        <input type="text" name="details" placeholder="Details" id="details"> 
      </div>
   <br>
   

   <div class="button">
        <input type="submit" value="Edit Warehouse" onclick="window.location.href='warehouse.php'"">
    </div>

</form>

</body>

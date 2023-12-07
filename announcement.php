<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>ANNOUNCEMENT</title>
        <link rel="stylesheet" href="adstyle.css?v=3">
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
<br>
<button onclick="history.back()"> <i class="arrow left"></i>Go Back</button>
<br>
<br>

<form class = "box" method="POST">
    <h1> 
      Create an announcement
    </h1>

      <div class="input-box">
      <span class="details">Need for Item</span>
      <input type="text" name="item" placeholder="Item" id="item" required>
      </div>

      <div class="input-box">
        <span class="details">Quntity</span>
        <input type="number" name="name" placeholder="Quntity" id="quntity" required> 
      </div>
   <br>
   

   <div class="button">
        <input type="submit" value="Create Announcement">
    </div>

</form>

</body>
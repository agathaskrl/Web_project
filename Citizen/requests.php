<!DOCTYPE html>
<html lan="en" and dir="ltr">
    <head>
        <meta charset="utf-8">
        <title> Requests </title>
        <link rel="stylesheet" href="requests.css">
</head>

<body>
<div class="main"> 
    <div class="navbar">
        <ul>
            <li><a href= "homepolitis.php">HOME</a></li>
            <li><a href= "requests.php">REQUESTS</a></li>
            <li><a href= "announcements.php">ANNOUNCEMENTS</a></li>
            <li><a href= "offers.php">OFFERS</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
        </ul>
    </div>
</div>


<!-- koumpi gia selida me listes -->
    <a href="requestslist.php" >
    <button> REQUESTS LIST </button></a>

<!-- box formas -->
<form class="box" method="POST">

<h1> Create a new request </h1>
<div class="input-box">
<label for="item"> Item:</label>
<input type="text" id="item" name="item" placeholder="ex. water" required>
<!--<option value ="water"> Water</option>
<option value ="milk"> Milk</option>-->
</div>
<div class="input-box">
<label for="people"> People in need:</label>
<input type="number" id="people" name="people" placeholder="ex. 30" required>
</div>

<div class="button">
        <input type="submit" value="Submit Request">
    </div>
</form>

</body>
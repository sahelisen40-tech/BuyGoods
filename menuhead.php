<?php echo "<!-- menuhead loaded -->"; ?>

<style>
.topnav {
  overflow: hidden;
  background-color: #800000;
  //height: 25px;
  
}

.topnav a {
  float: left;
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
  margin-left: 50px;
  margin-top: 5px;
}

.topnav a:hover {
  background-color: #FF7F50;
  color: black;
  transition: 0.15s;
}

.topnav a.active {
 // background-color: ;
  color: white;
  transition: 0.15s;
}

.topnav .icon {
  display: none;
}

@media screen and (max-width: 600px) {
  .topnav a:not(:first-child) {display: none;}
  .topnav a.icon {
    float: right;
    display: block;
  }
}

@media screen and (max-width: 600px) {
  .topnav.responsive {position: relative;}
  .topnav.responsive .icon {
    position: absolute;
    right: 0;
    top: 0;
  }
  .topnav.responsive a {
    float: none;
    display: block;
    text-align: left;
  }
}



</style>





<body>

<div class="topnav" id="myTopnav">
  <a href="main_body.php" class="active">Home</a>
  <a href="#news">News</a>
  <a href="register.php">Register</a>
  <a href="login.php">Login</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
</div>

<div style="padding-left:16px">
  
</div>   
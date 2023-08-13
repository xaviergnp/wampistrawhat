<?php
  session_start();
	include("config.php");

  if(!isset($_SESSION['user'])){
    session_destroy();
    header("location: login.php");
  }

  //query for session
  $user_check=$_SESSION['user'];
  $sql="SELECT * FROM users WHERE username='$user_check'";
  $stmt=$conn->query($sql);
  if(!$row=$stmt->fetch(PDO::FETCH_ASSOC) || $stmt->rowCount()<1){
    session_destroy();
    header("location: login.php");
  }

 $user_check=$_SESSION['user'];
  $sql="SELECT * FROM users WHERE username='$user_check'";
  $stmt=$conn->query($sql);
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  $login_user=$row['first_name']." ".$row['last_name'];
  $login_access=$row['account_type'];
  $fname = $row['first_name'];
  $lname =$row['last_name'];
  $email = $row['email_address'];
  $bday = $row['birthday'];
  $gndr = $row['gender'];
  $usrn = $row['username'];
  $pswd = $row['password'];

  //Edit User
  if(isset($_POST['editUser'])){
    $sql="SELECT * FROM users WHERE user_id = '{$row['user_id']}'";
      $resultUser=$conn->query($sql);
      $rowUser = $resultUser->fetch(PDO::FETCH_ASSOC);
      $prevUsername = $rowUser['username'];


      $sql="SELECT * FROM users WHERE username = '{$_POST['upd_username']}'";
      $resultUser=$conn->query($sql);
      $resultUser->fetch(PDO::FETCH_ASSOC);
      if(($prevUsername != $_POST['upd_username']) && $resultUser->rowCount()>0){
        echo "<script> window.location.href = 'acc.php'; alert('Editing Account Failed! Username Already Exists!'); </script>";
      }
      else{
        $sql="UPDATE users SET first_name=:firstName, last_name=:lastName, email_address=:emailAddress, birthday=:birthDay, gender=:genDer, username=:userName, password=:passWord WHERE user_id=:update_id";
        $stmt=$conn->prepare($sql);
        $stmt->execute([
            ':firstName'=>$_POST['upd_firstname'],
            ':lastName'=>$_POST['upd_lastname'],
            ':emailAddress'=>$_POST['upd_emailaddress'],
            ':birthDay'=>$_POST['upd_birthday'],
            ':genDer'=>$_POST['upd_gender'],
            ':userName'=>$_POST['upd_username'],
            ':passWord'=>$_POST['upd_password'],
            ':update_id'=>$row['user_id']
          ]);
          if($_POST['upd_user_id'] == $_SESSION['userid']){
            $_SESSION['user']=$_POST['upd_username'];
          }
        echo "<script> window.location.href = 'acc.php'; alert('Account Updated Successfully'); </script>";
      }
    
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
    <title>One Piece (Strawhat)</title>
    <link rel="stylesheet" href="page-stylesheet.css" />
    <link rel="icon" type="image/x-icon" href="assets/wampis-logo.ico" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Lato"
    />
  </head>
  <body id="id-body-acc-page" >
   <!-- Navigation Bar -->
   <div class="top-stick">
      <div class="navbar-container">
        <!-- <i></i> -->
        <div class="navbar-menu-container">
          <a
            class="navbar-button-menu"
            id="id-menu-hidden"
            onclick="menuToggle()"
          >
            <div class="menu-icon-line-container">
              <div class="menu-icon-line"></div>
              <div class="menu-icon-line"></div>
              <div class="menu-icon-line"></div>
            </div>
          </a>
           <a class="navbar-button-sitelogo" href="index.php"
            ><img
              src="assets/wampis-logo.png"
              class="wampis-sitelogo"
              alt="wampis logo"
              width="35px"
            /><i class="navbar-button-sitelogo-text">Strawhat</i></a
          >
        </div>
        <div id="id-button-hide">
          <a class="navbar-button-container" href="index.php">
            <div class="navbar-button">Home</div>
          </a>
          <a class="navbar-button-container" href="p3-characters.php">
            <div class="navbar-button">Characters</div>
          </a>
          <a class="navbar-button-container" href="p4-episodes.php">
            <div class="navbar-button">Episodes</div>
          </a>
        </div>
        
        <!-- Log In Tab -->
        <div class="user-login-container">
          <div class="user-login-button" onclick="accToggle()"
            ><img src="assets/user-icon-light.png" alt="user icon" width="35px" class="user-icon"/><div class="login-icon-usertext"><i>Hi, <?php echo $login_user; ?></i></div>
            <img src="assets/drop-down-button-light.png" alt="dropdown icon" width="20px" class="user-dropdown-icon">
            </div
          >
        </div>
      </div>
      <!-- Dropdown Account -->
      <div class="dropdown-account-container" id="id-dropdown-account-container">
        <a class="dropdown-account-button" href="acc.php">My Account</a>
        <?php if($login_access=="Admin"){?>
          <a class="dropdown-account-button" href="list.php">User List</a>
        <?php }?>
        <a class="dropdown-account-button" href="logout.php">Log Out</a>
      </div>

      <!-- Dropdown Menu -->
      <div class="dropdown-menu-container" id="id-dropdown-menu-container">
        <a class="dropdown-menu-button" href="index.php">Home</a>
        <a class="dropdown-menu-button" href="p3-characters.php">Characters</a>
        <a class="dropdown-menu-button" href="p4-episodes.php">Episodes</a>
      </div>
    </div>

    <!-- Navigation Bar END -->

    <div class="signup-contain">
    <main id="signup-main-container">
    <div class="login-logo-container-acc" id="logologo">
        <a href="index.php">
            <img
            class="login-logo-acc"
            src="assets/onepice-logo.png"
            alt="One Piece Logo"
            />
        </a>
      </div>
      
      <!--- Sign Up Form --->
      <form action="" method="post" >
        <div class="signup-container" id="id-signup-container">
          <h3>Edit Info</h3>
            <div class="signup-box" id="editUserInfo">
                <div class="name-container">
                <div class="firstname-container">
                    <i>First Name</i>
                <input class="input-box-name" type="text" minlength="1"  maxlength="61" placeholder="Enter your firstname." pattern="[^&quot'<>\/,]{1,60}" title="Firstname cannot exceed 60 characters and cannot have any of these characters: &quot ' < > \ / ," name="upd_firstname" value="<?php echo $fname?? 'default value';?>">
                </div>
                <div class="lastname-container">
                    <i>Last Name</i>
                    <input class="input-box-name" type="text" minlength="1"  maxlength="61" placeholder="Enter your lastname." pattern="[^&quot'<>\/,]{1,60}" title="Lastname cannot exceed 60 characters and cannot have any of these characters: &quot ' < > \ / ," name="upd_lastname" value="<?php echo $lname?? 'default value';?>">
                </div>
                </div>
                <p>Email Address</p>
                <input class="signup-input-box" type="email" minlength="1"  maxlength="61" placeholder="Enter your email address." pattern="[^&quot'<>\/,]{1,60}" title="Email Address cannot exceed 60 characters and cannot have any of these characters: &quot ' < > \ / ," name="upd_emailaddress" value="<?php echo $email?? 'default value';?>">
                <div class="bday-container">
                <!-- value="<php echo date('Y-m-d');?> " -->
                  <div class="birthday-container">
                      <i>Birthday</i>
                      <input class="input-box-name" type="date"  min="1900-01-01" max="2022-12-31" placeholder="Input your birthday." name="upd_birthday" value="<?php echo $bday?? 'default value';?>">
                  </div>
                  <div class="gender-container">
                      <i>Gender</i>
                      <select class="input-box-name" id="id_upd_gender" name="upd_gender" value="<?php echo $gndr?? 'default value';?>">
                        <option value="Male"><i>Male<i></option>
                        <option value="Female"><i>Female<i></option>
                      </select>
                  </div>
                </div>
                <p>Username</p>
                <input class="signup-input-box" type="text" minlength="1"  maxlength="31" placeholder="Enter your username." pattern="^(?=.*[0-9])(?=.*[a-zA-Z])([^&quot'<>\/,]{6,30})$" title="Username must have 6-30 alphanumeric characters and cannot have any of these characters: &quot ' < > \ / ," name="upd_username" value="<?php echo $usrn?? 'default value';?>">
                <p>Password</p>
                <input class="signup-input-box" type="password"minlength="1"  maxlength="31" placeholder="Enter your password." pattern="^(?=.*[0-9])(?=.*[a-zA-Z])([^&quot'<>\/,]{8,30})$" title="Password must have 8-30 alphanumeric characters characters and cannot have any of these characters: &quot ' < > \ / ," name="upd_password" value="<?php echo $pswd?? 'default value';?>">
                <button type="submit" class="btn-signup" name="editUser">Save Changes</div>
                <input type="hidden" name="uid" id="upid">
                <!-- <div class="signup-breakline"></div>
                <div class="btn-login" onclick="loginTab()">Go to Log In</div> -->
            </div>
        </div>
      </form>
    </main>
    </div>

    <!-- Footer -->
   <!--  <footer class="end-footer">
      <p>
        Copyright © 2022 Kimetsu no Yaiba. All Rights Reserved.<br />
        This site does not store any files on the internet.<br />
        All contents are provided locally.
      </p>
    </footer> -->
    </div>
    <script src="page-script.js"></script>
  </body>
</html>

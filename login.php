<?php
	session_start();
	include("config.php");

  //redirect to home page if currently logged in
  if(isset($_SESSION['user'])){
    header("location: index.php");
  } 

  // --- Log In ---//
  if(isset($_POST['logIn'])){
		$sql="SELECT * FROM users WHERE username=:un AND password=:pw";
		$stmt=$conn->prepare($sql);
		$stmt->execute([':un'=>$_POST['uname'],
						':pw'=>$_POST['pword']]);
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
    // $_SESSION['userid'] = $row['user_id'];
		$count=$stmt->rowCount();

		if($count==1){
      $_SESSION['userid'] = $row['user_id'];
			$_SESSION['user']=$_POST['uname'];
			header("location: index.php");
		}
		else{
			$error="Invalid username and/or password!";
		}
	}

	//--- Sign Up --- // 
	if(isset($_POST['signUp'])){
    $sql="SELECT * FROM users WHERE username = '{$_POST['inp_username']}'";
      $resultUser=$conn->query($sql);
      $resultUser->fetch(PDO::FETCH_ASSOC);
      if($resultUser->rowCount()>0){
        echo "<script> window.location.href = 'list.php'; alert('Adding User Failed! Username Already Exists!'); </script>";
      }
      else{
        $sql="INSERT INTO users(first_name, last_name, email_address, birthday, gender, username, password) 
        VALUES(:firstName, :lastName, :emailAddress, :birthDay, :genDer, :userName, :passWord)";
        $stmt=$conn->prepare($sql);
        $stmt->execute([
                ':firstName'=>$_POST['inp_firstname'],
                ':lastName'=>$_POST['inp_lastname'],
                ':emailAddress'=>$_POST['inp_emailaddress'],
                ':birthDay'=>$_POST['inp_birthday'],
                ':genDer'=>$_POST['inp_gender'],
                ':userName'=>$_POST['inp_username'],
                ':passWord'=>$_POST['inp_password']
              ]);
        echo "<script> window.location.href = 'login.php'; alert('User Added Successfully'); </script>";
      }
 
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    <title>WamPis (Strawhat) - Log In or Sign Up</title>
    <link rel="stylesheet" href="page-stylesheet.css" />
    <link rel="icon" type="image/x-icon" href="assets/wampis-logo.ico" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Lato"
    />
</head>
<body id="id-body-login-page">
      <div class="login-logo-container" id="logologo">
        <a href="index.php">
            <img
            class="login-logo"
            src="assets/onepice-logo.png"
            alt="One Piece Logo"
            />
        </a>
      </div>
      <!--- Log In Form --->

        <div class="login-container" id="logintab-box">
          <h3>Log In</h3>
          <div class="login-box">
            <form action="" method="post">
              <p>Username</p>
              <input class="input-box" type="text" minlength="1"  maxlength="30" pattern="[^&quot'<>\/,]{1,30}" title="Input CANNOT have any of these characters: &quot ' < > \ / ," name="uname" id="username" placeholder="Enter your username.">
              <p>Password</p>
              <input class="input-box" type="password" minlength="1"  maxlength="30" pattern="[^&quot'<>\/,]{1,30}" title="Input CANNOT have any of these characters: &quot ' < > \ / ," name="pword" id="password" placeholder="Enter your password.">

              <button type="submit" name="logIn" class="button-login">Log In</button>
            </form>
            <?php if(isset($error)){ ?>
              <div class="login-error-text">  
                <?php echo $error; ?>
              </div>
            <?php } ?>
            <div class="login-breakline"></div>
            <div class="button-signup" onclick="signupTab()"><span class="login-signuptxt_long">Create New Account</span><div class="login-signuptxt_short">Sign Up</div></div>
          </div>
        </div>

      <!--- Sign Up Form --->
      <form action="" method="post">
        <div class="signup-container" id="signuptab-box">
          <h3>Sign Up</h3>
            <div class="signup-box">
                <div class="name-container">
                  <div class="firstname-container">
                      <i>First Name</i>
                  <input class="input-box-name" type="text" minlength="1"  maxlength="61" placeholder="Enter your firstname." pattern="[^&quot'<>\/,]{1,60}" title="Firstname cannot exceed 60 characters and cannot have any of these characters: &quot ' < > \ / ," name="inp_firstname" required>
                  </div>
                  <div class="lastname-container">
                      <i>Last Name</i>
                      <input class="input-box-name" type="text" minlength="1"  maxlength="61" placeholder="Enter your lastname." pattern="[^&quot'<>\/,]{1,60}" title="Lastname cannot exceed 60 characters and cannot have any of these characters: &quot ' < > \ / ," name="inp_lastname" required>
                  </div>
                </div>
                <p>Email Address</p>
                <input class="signup-input-box" type="email" minlength="1"  maxlength="61" placeholder="Enter your email address." pattern="[^&quot'<>\/,]{1,60}" title="Email Address cannot exceed 60 characters and cannot have any of these characters: &quot ' < > \ / ," name="inp_emailaddress" required>
                <div class="bday-container">
                  <div class="birthday-container">
                      <i>Birthday</i>
                      <input class="input-box-name" type="date"  min="1900-01-01" max="2022-12-31" placeholder="Input your birthday." name="inp_birthday" required>
                  </div>
                  <div class="gender-container">
                      <i>Gender</i>
                      <select class="input-box-name" id="id-gender" name="inp_gender" required>
                        <option value="Male"><i>Male<i></option>
                        <option value="Female"><i>Female<i></option>
                      </select>
                  </div>
                </div>
                <p>Username</p>
                <input class="signup-input-box" type="text" minlength="1"  maxlength="31" placeholder="Enter your username." pattern="^(?=.*[0-9])(?=.*[a-zA-Z])([^&quot'<>\/,]{6,30})$" title="Username must have 6-30 alphanumeric characters and cannot have any of these characters: &quot ' < > \ / ," name="inp_username" required>
                <p>Password</p>
                <input class="signup-input-box" type="password" minlength="1"  maxlength="31" placeholder="Enter your password." pattern="^(?=.*[0-9])(?=.*[a-zA-Z])([^&quot'<>\/,]{8,30})$" title="Password must have 8-30 alphanumeric characters characters and cannot have any of these characters: &quot ' < > \ / ," name="inp_password" required>
                <button type="submit" class="btn-signup" name="signUp">Sign Up</div>
                <div class="signup-breakline"></div>
                <div class="btn-login" onclick="loginTab()">Go to Log In</div>
                <!-- <div class="btn-login" onclick="loginTab()"><span class="signup-logintxt_long">Go to Log In</span><span class="signup-logintxt_short">Log In</span></div> -->
            </div>
        </div>
      </form>
      <script src="page-script.js"></script>
  </body>
</html>
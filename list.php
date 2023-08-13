<?php
    session_start();
    include("config.php");
    //redirect to login page if no session
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

    if($login_access !="Admin"){
        header("location: index.php");
    }

    //Add User
    if(isset($_POST['submit_add_user'])){
      $sql="SELECT * FROM users WHERE username = '{$_POST['inp_username']}'";
      $resultUser=$conn->query($sql);
      $resultUser->fetch(PDO::FETCH_ASSOC);
      if($resultUser->rowCount()>0){
        echo "<script> window.location.href = 'list.php'; alert('Adding User Failed! Username Already Exists!'); </script>";
      }
      else{
        $sql="INSERT INTO users(first_name, last_name, email_address, birthday, gender, username, password, account_type) 
        VALUES(:firstName, :lastName, :emailAddress, :birthDay, :genDer, :userName, :passWord, :accountType)";
        $stmt=$conn->prepare($sql);
        $stmt->execute([
                ':firstName'=>$_POST['inp_firstname'],
                ':lastName'=>$_POST['inp_lastname'],
                ':emailAddress'=>$_POST['inp_emailaddress'],
                ':birthDay'=>$_POST['inp_birthday'],
                ':genDer'=>$_POST['inp_gender'],
                ':userName'=>$_POST['inp_username'],
                ':passWord'=>$_POST['inp_password'],
                ':accountType'=>$_POST['inp_accounttype'],
              ]);
        echo "<script> window.location.href = 'list.php'; alert('User Added Successfully'); </script>";
      }
      
    }

    //Edit User
    if(isset($_POST['submit_edit_user'])){
      $sql="SELECT * FROM users WHERE user_id = '{$_POST['upd_user_id']}'";
      $resultUser=$conn->query($sql);
      $rowUser = $resultUser->fetch(PDO::FETCH_ASSOC);
      $prevUsername = $rowUser['username'];

      $sql="SELECT * FROM users WHERE username = '{$_POST['upd_username']}'";
      $resultUser=$conn->query($sql);
      $resultUser->fetch(PDO::FETCH_ASSOC);
      if(($prevUsername != $_POST['upd_username']) && $resultUser->rowCount()>0){
        echo "<script> window.location.href = 'list.php'; alert('Editing User Failed! Username Already Exists!'); </script>";
      }
      else{
        $sql="UPDATE users SET first_name=:firstName, last_name=:lastName, email_address=:emailAddress, birthday=:birthDay, gender=:genDer, username=:userName, password=:passWord, account_type=:accountType WHERE user_id=:update_id";
        $stmt=$conn->prepare($sql);
        $stmt->execute([
            ':firstName'=>$_POST['upd_firstname'],
            ':lastName'=>$_POST['upd_lastname'],
            ':emailAddress'=>$_POST['upd_emailaddress'],
            ':birthDay'=>$_POST['upd_birthday'],
            ':genDer'=>$_POST['upd_gender'],
            ':userName'=>$_POST['upd_username'],
            ':passWord'=>$_POST['upd_password'],
            ':accountType'=>$_POST['upd_accounttype'],
            ':update_id'=>$_POST['upd_user_id']
          ]);

        if($_POST['upd_user_id'] == $_SESSION['userid']){
          $_SESSION['user']=$_POST['upd_username'];
        }
      
        echo "<script> window.location.href = 'list.php'; alert('User Updated Successfully'); </script>";
      }
      
    }

    //Delete User
    if(isset($_POST['submit_delete_user'])){
      $sql="SELECT * FROM users WHERE user_id = '{$_POST['del_user_id']}'";
      $resultUser=$conn->query($sql);
      $rowUser =$resultUser->fetch(PDO::FETCH_ASSOC);
      $userDeleteAccType = $rowUser['account_type'];

      $sql="SELECT * FROM users WHERE account_type = 'Admin'";
      $resultUser=$conn->query($sql);
      $resultUser->fetch(PDO::FETCH_ASSOC);
      
      if($resultUser->rowCount()<2 && $userDeleteAccType =="Admin"){
        echo "<script> window.location.href = 'list.php'; alert('You CANNOT Delete the Last remaining Admin account!'); </script>";
      }
      else{
        $sql="DELETE FROM users WHERE user_id=:userid;";
        $stmt=$conn->prepare($sql);
        $stmt->execute([':userid'=>$_POST['del_user_id']]);
        echo "<script> window.location.href = 'list.php'; alert('User Deleted Successfully'); </script>";

        if($_POST['del_user_id'] == $_SESSION['userid']){
          session_destroy();
          header("location: login.php");
        }
       
      }
      
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
    <title>One Piece (Strawhat)</title>
    <!------ Bootstrap CSS ----->
    <link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.css"/>
    <!----- JS and JQuery ----->
    <script src="bootstrap-5.1.3-dist/js/jquery-3.6.0.js"></script>
    <script src="bootstrap-5.1.3-dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="list.css" />
    <link rel="icon" type="image/x-icon" href="assets/wampis-logo.ico" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Lato"
    />
  </head>
  <body>
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
            <div class="navbar-button">CrewMembers</div>
          </a>
          <a class="navbar-button-container" href="p4-episodes.php">
            <div class="navbar-button">Highlights</div>
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
        <a class="dropdown-menu-button" href="p3-characters.php">CrewMembers</a>
        <a class="dropdown-menu-button" href="p4-episodes.php">Highlights</a>
      </div>
    </div>
    <!-- Navigation Bar END -->

    <div class="list-container">
    <main class="container pt-3">
      <div class="card">
        <div class="card-header">
          <h2 class="list-head-text">List of Users
            <?php if($login_access=="Admin") {?>
            <span><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add_user_modal">Add</button></span>
            <?php }?>
          </h2>
        </div>
        <div class="list-overflow card-body">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email Address</th>
                <th>Birthday</th>
                <th>Gender</th>
                <th>Username</th>
                <th>Password</th>
                <th>Account Type</th>
                <?php if($login_access=="Admin") {?>
                <th>Action</th>
                <?php }?>
              </tr>
            </thead>
            <tbody>
              <?php
                $sql="SELECT * FROM users";
                $result=$conn->query($sql);
                while($row=$result->fetch(PDO::FETCH_ASSOC)){
              ?>
              <tr>
                <td><?php echo $row['user_id'];?></td>
                <td><?php echo $row['first_name'];?></td>
                <td><?php echo $row['last_name'];?></td>
                <td><?php echo $row['email_address'];?></td>
                <td><?php echo $row['birthday'];?></td>
                <td><?php echo $row['gender'];?></td>
                <td><?php echo $row['username'];?></td>
                <td><?php echo str_repeat('*', strlen($row['password']));?></td>
                <td><?php echo $row['account_type'];?></td>
                <td>
                <?php if($login_access=="Admin") {?>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit_user_modal"
                  onclick="userEdit( 
                    '<?php echo $row['user_id'];?>',
                    '<?php echo $row['first_name'];?>',
                    '<?php echo $row['last_name'];?>',
                    '<?php echo $row['email_address'];?>',
                    '<?php echo $row['birthday'];?>',
                    '<?php echo $row['gender'];?>',
                    '<?php echo $row['username'];?>',
                    '<?php echo $row['password'];?>',
                    '<?php echo $row['account_type'];?>'
                  )"
                  >Edit</button>
                  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_user_modal" onclick="userDelete(<?php echo $row['user_id'];?>)">Delete</button>
                <?php }?>
                </td>
              </tr>
              <?php }?>
            </tbody>
          </table>
            <?php echo ">> ".$result->rowCount()." user/s found";?>
        </div>
      </div>
    </main>
    </div>

    <!--------------------------- Add User Modal ------------------------>
    <div class="modal fade" id="add_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="POST">
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_inp_firstname" minlength="1"  maxlength="61" placeholder="Enter First Name" pattern="[^&quot'<>\/,]{1,60}" title="Firstname cannot exceed 60 characters and cannot have any of these characters: &quot ' < > \ / ," name="inp_firstname" required>
              </div>
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_inp_lastname" minlength="1"  maxlength="61" placeholder="Enter Last Name" pattern="[^&quot'<>\/,]{1,60}" title="Lastname cannot exceed 60 characters and cannot have any of these characters: &quot ' < > \ / ," name="inp_lastname" required>
              </div>
              <div class="mb-3 mt-3">
                <input type="email" class="form-control" id="id_inp_emailaddress" minlength="1"  maxlength="61" placeholder="Enter Email Address" pattern="[^&quot'<>\/,]{1,60}" title="Email Address cannot exceed 60 characters and cannot have any of these characters: &quot ' < > \ / ," name="inp_emailaddress" required>
              </div>
              <div class="mb-3 mt-3">
                <input type="date" class="form-control" id="id_inp_birthday" min="1900-01-01" max="2022-12-31" placeholder="Enter Birthday" name="inp_birthday" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Gender</label>
                <select class="form-select" id="id_inp_gender" name="inp_gender" required>
                  <option value="" readonly>---- Select ----</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
              <div class="mb-3 mt-3">
                <input type="text" minlength="1"  maxlength="31" class="form-control" id="id_inp_username" placeholder="Enter Username" pattern="^(?=.*[0-9])(?=.*[a-zA-Z])([^&quot'<>\/,]{6,30})$" title="Username must have 6-30 alphanumeric characters and cannot have any of these characters: &quot ' < > \ / ," name="inp_username" required>
              </div>
              <div class="mb-3">
                <input type="password"  minlength="1"  maxlength="31" pattern="^(?=.*[0-9])(?=.*[a-zA-Z])([^&quot'<>\/,]{8,30})$" title="Password must have 8-30 alphanumeric characters characters and cannot have any of these characters: &quot ' < > \ / ," class="form-control" id="id_inp_password" placeholder="Enter Password" name="inp_password" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Account Type:</label>
                <select class="form-select" name="inp_accounttype" required>
                  <option value="" readonly>---- Select ----</option>
                  <option value="Admin">Admin</option>
                  <option value="User">User</option>
                </select>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_add_user">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!--------------------------- Edit User Modal ------------------------>
    <div class="modal fade" id="edit_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="POST">
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_upd_firstname" placeholder="Enter First Name" name="upd_firstname" required>
              </div>
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_upd_lastname" placeholder="Enter Last Name" name="upd_lastname" required>
              </div>
              <div class="mb-3 mt-3">
                <input type="email" class="form-control" id="id_upd_emailaddress" placeholder="Enter Email Address" name="upd_emailaddress" required>
              </div>
              <div class="mb-3 mt-3">
                <input type="date" class="form-control" id="id_upd_birthday" min="1900-01-01" max="2022-12-31" placeholder="Enter Birthday" name="upd_birthday" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Gender</label>
                <select class="form-select" name="upd_gender" id="id_upd_gender" required>
                  <option value="" readonly>-----Select-----</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
              <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="id_upd_username" placeholder="Enter Username" name="upd_username" required>
              </div>
              <div class="mb-3">
                <input type="password" class="form-control" id="id_upd_password" placeholder="Enter Password" name="upd_password" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Account Type:</label>
                <select class="form-select" id="id_upd_accounttype" name="upd_accounttype"required>
                  <option value="" readonly>-----Select-----</option>
                  <option value="Admin">Admin</option>
                  <option value="User">User</option>
                </select>
              </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" id="id_upd_user_id" name="upd_user_id">
              <button type="submit" class="btn btn-primary" name="submit_edit_user">Save Changes</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!--------------------------- Delete User Modal ------------------------>
    <div class="modal fade" id="delete_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete?</p>
          </div>
          <div class="modal-footer">
            <form action="" method="POST">
              <input type="hidden" name="del_user_id" id="id_del_user_id">
              <button type="submit" class="btn btn-primary" name="submit_delete_user">Yes</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="page-script.js"></script>
  </body>
</html>
<script>
  //Delete User
  function userDelete($userId){
    document.getElementById('id_del_user_id').value = $userId;
  }
  
  //Edit User
  function userEdit(userId, userfirstnm, userlastnm, userEmail, userBday, userGender, username, password, userAcctype){
    $('#id_upd_user_id').val(userId);
    $('#id_upd_firstname').val(userfirstnm);
    $('#id_upd_lastname').val(userlastnm);
    $('#id_upd_emailaddress').val(userEmail);
    $('#id_upd_birthday').val(userBday);
    $('#id_upd_gender').val(userGender);
    $('#id_upd_username').val(username);
    $('#id_upd_password').val(password);
    $('#id_upd_accounttype').val(userAcctype);
  }
</script>

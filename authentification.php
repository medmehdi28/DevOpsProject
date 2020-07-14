<?php
session_start();
if (isset($_SESSION['connect']) && ($_SESSION['id'] !== '')){  
  header('location:user.php');
}
if (isset($_SESSION['adminPassword2']) && ($_SESSION['adminPassword'] !== '')){  
  header('location:admin.php');
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Authentification</title>
<meta CHARSET="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<link rel="stylesheet" href="style.css" type="text/css">
<script src="java.js"></script>
</head>
<body class='body' onload="getLocation()">
<header>
  <div class='left-header'><img src='./Photos/dxc.jpg'></div>
  <div class='right-header'>
       <div class='title'>DXC WEB PORTAL</div>
       <div class='square'><span class='date' id='date'><?php date_default_timezone_set("Europe/Paris"); echo "The ". date('Y-m-d h:i'); ?></span></div>       
  </div>    
</header>
<div class='menue'>
    <div class="form-menue">
        <form action="authentification.php" method="post" style="margin-top:20px;">  
                      <label style="color:orange;text-decoration : underline;">ADMIN ACCESS</label><br><br>
                      <label style="color:white;" for="adminName">Your Email :</label>
                      <input style="width:200px;" type='text' name='adminName' id='text' autocomplete='on'><br><br>
                      <label style="color:white;" for="adminPassword">Your Password :</label><br><br>
                      <input style="width:200px;" type='password' name='adminPassword' id='password'><br><br>    
                      <label style="color:white;" for="adminPassword2">Your Second Password :</label><br><br>          
                      <input style="width:200px;" type='password' name='adminPassword2' id='password'><br><br>           
                      <input type='submit' name='adminValidate' id='submit' class='button'>
        </form>
    </div>   

  <div class='vl'>
      <i class='fas fa-angle-double-right' id='open-menue'></i>
  </div>
</div>

<div class='container'> 
    <div class='header'><i class="fas fa-pen-nib" style="font-size:22px;color:white" aria-hidden="true"></i>&nbsp SIGN IN</div>    
        <form action="authentification.php" method="post" class="mainform" >
              <label for="userName">Your Email :</label><br>
              <input type='text' name='userName' id='text' class='input' autocomplete='on'><br><br>
              <label for="password">Your Password :</label><br><br>
              <input type='password' name='password' id='password' class='input'><br><br>
              <input type='submit' name='userValidate' id='submit' class='button'>
        </form>
    <div class='footer'><footer>DXC<sup> &copy;</sup></footer></div>
  </div>    

<?php
$_SESSION['position'] = "<span style='color:#64a4e8;text-align:center;fontsize:15px;' id='mapholder'></span>";
include 'connexion.php';
$conn = Database::connect();
if (isset($_POST['userValidate'])){    
      $userName = test_input($_POST['userName']);
      $password_verif  = test_input($_POST['password']); 
      $_SESSION['connect'] = false;
      $sql = $conn->prepare("SELECT * FROM users WHERE email = ?");
      $sql->execute([$userName]);  
      $values = $sql->fetchAll();  
      if($values) {
          foreach($values as $item){
              $password = $item["password"];            
              $id = $item['id'];        
              $email = $item['email'];
          }             
          if (empty($userName) || empty($password_verif)){
              echo '<script>alert ("UserName and Password was required");  </script>';
            }  
          else if ($password_verif !== $password) {
              echo '<script>alert ("Wrong Username or Password please verify");  </script>';
          }           
          else {     
              session_start();
              $_SESSION['connect'] = true;      
              $_SESSION['id'] = $id;             
              $_SESSION['email'] = $email;
              header('location: user.php?location='.$_SESSION['position']);
          } 
      }          
    } 
      function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
      Database::disconnect();    
?>

<?php
if (isset($_POST['adminValidate'])){    
      $adminName = test_input2($_POST['adminName']);
      $passwordVerif  = test_input2($_POST['adminPassword']); 
      $passwordVerif2  = test_input2($_POST['adminPassword2']); 
      $_SESSION['connect'] = false;
      $sql = $conn->prepare("SELECT * FROM users WHERE email = ? AND privilege = '1'");
      $sql->execute([$adminName]);  
      $values = $sql->fetchAll();  
      if($values) {
          foreach($values as $item){
              $adminPassword = $item["password"];              
              $adminPassword2 = $item["password2"];
              $email = $item['email'];        
              $adminId = $item['id'];
              $priv = $item['privilege'];
          }             
          if (empty($adminName) OR empty($passwordVerif) OR empty($passwordVerif2)){
              echo '<script>alert ("UserName and Password was required");  </script>';
            }  
          else if (($passwordVerif !== $adminPassword) OR ($passwordVerif2 !== $adminPassword2) OR ($priv == '3')){
              echo '<script>alert ("Wrong Username or Password please verif or you don\'t have Privilege to access");  </script>';
          }           
          else {     
              session_start();                          
              $_SESSION["email"] = $email;
              $_SESSION["adminPassword"] = $adminPassword;
              $_SESSION["adminPassword2"] = $adminPassword2;         
              $_SESSION['id'] = $adminId;
              header('location:admin.php');
          } 
      }          
    } 
    function test_input2($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    } 
    Database::disconnect();
?>
<span style='color:#64a4e8;text-align:center;fontsize=5px;' id='mapholder'></span> 
<?php
$_SESSION['position'] = int("<script>document.getElementById('mapholder').innerHTML</script>");   
    
print($_SESSION['position']) ;
?>
<br><br>
</body>
</html>
<?php require "includes/header.php"; ?>

<!-- require connection -->
<?php require "config.php"; ?>

<!-- process registration -->
<?php 
  #the try catch block to predict any issues
  try {
    
    # set the error attribute mode
    $connect -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    # start the transaction
    $connect->beginTransaction();    
    
    # on click submit btn
    if(isset($_POST['submit'])){

      # retrieve data from form
      $username = $_POST["username"];
      $email = $_POST["email"];
      $password = $_POST["password"];
      
      # check if the inputs are filled in
      if(empty($username) OR empty($email) OR empty($password)){
        echo "<script>alert('Please fill all fields')</script>";
      } else {
        # we need to check if the email is already registered
        $req = 'SELECT * FROM users WHERE email=:email';
        $checkEmail = $connect->prepare($req);
        $checkEmail->execute([':email' => $email]);
        if ($checkEmail->rowCount() != 0) {
          echo "<script>alert('Email already used')</script>";
        } else {
          # query to insert into database
          $query = 'INSERT INTO users (username, email, my_password) VALUES (:username, :email, :my_password)';
          $insert = $connect->prepare($query);
          
          # execute the query with the given values
          $insert->execute([
            ':username' => $username,
            ':email' => $email,
            'my_password' => password_hash($password, PASSWORD_DEFAULT)
          ]);

          // header("location : login.php");

        }
      }
    }

    # commit the transaction
    $connect->commit();

  } catch (PDOException $e) {
    # rollback
    $connect->rollback();
    echo $e->getMessage();
  }

?>

<main class="form-signin w-50 m-auto">
  <form method="POST" action="register.php">
   
    <h1 class="h3 mt-5 mb-5 fw-normal text-center">Create Account</h1>

     <div class="form-floating mb-3">
      <input name="username" type="text" class="form-control" id="floatingInput" placeholder="username" require>
      <label for="floatingInput">Username</label>
    </div>

    <div class="form-floating mb-3">
      <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" require>
      <label for="floatingInput">Email address</label>
    </div>

   

    <div class="form-floating mb-3">
      <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" require>
      <label for="floatingPassword">Password</label>
    </div>

    <button name="submit" class="w-100 btn btn-lg btn-primary" type="submit">register</button>
    <h6 class="mt-3">Already have an account?  <a href="login.php">Login</a></h6>

  </form>
</main>


<?php require "includes/footer.php"; ?>

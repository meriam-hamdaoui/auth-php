<?php require "includes/header.php"; ?>

<!-- require connection -->
<?php require "config.php"; ?>

<!-- process login credentials -->
<?php
  try {

    if(isset($_SESSION['username'])) {
      header("location: index.php");
    }

    # set the error attribute mode
    $connect -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    # start the transaction
    $connect->beginTransaction(); 
   
  
    # on click submit btn
    if(isset($_POST['submit'])){
      # get user input from form
      $email = $_POST["email"];
      $password = $_POST["password"];

       # check if the inputs are filled in
      if(empty($email) OR empty($password)){
        echo "<script>alert('Enter your email and password')</script>";
      } else {
         # we need to check if the email is already registered
        $req = "SELECT * FROM users WHERE email='$email'";
        $checkUser = $connect->prepare($req);
        $checkUser->execute();

        # we will need it to retrieve & decrypt the password
        $userData = $checkUser->fetch(PDO::FETCH_ASSOC);

        # check the email first
        if ($checkUser->rowCount() != 0) {
          # compare hashed passwords
          if(password_verify($password, $userData['my_password'])){
            $_SESSION['username'] = $userData['username'];
            $_SESSION['email'] = $userData['email'];

            header('location: index.php');
          } else {
            echo "<script>alert('password incorrect')</script>";

          }
          
        } else {
          echo "<script>alert('Verify your credentials')</script>";

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
  <form method="POST" action="login.php">
    <!-- <img class="mb-4 text-center" src="/docs/5.2/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
    <h1 class="h3 mt-5 mb-4 fw-normal text-center">Please sign in</h1>

    <div class="form-floating mb-3">
      <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
      <label for="floatingInput">Email address</label>
    </div>
    
    <div class="form-floating mb-3">
      <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>

    <button name="submit" class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    <h6 class="mt-3">Don't have an account  <a href="register.php">Create your account</a></h6>
  </form>
</main>


<?php require "includes/footer.php"; ?>

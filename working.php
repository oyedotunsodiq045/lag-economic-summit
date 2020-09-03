<?php

  // http://mymuse1.000webhostapp.com/conference/exhibit/index.html
  include 'config/Database.php';

  // Message Vars
  $msg = '';
  $msgClass = '';

  // Check For Submit
  if ($_POST) {
  // if (isset($_POST["submit"])) {
      // var_dump(isset($_POST['name']));
    //   exit();         
    // posted values
    $name     = htmlspecialchars($_POST['name']);
    $email    = htmlspecialchars($_POST['email']);
    $phone    = htmlspecialchars($_POST['phone']);
    $business = htmlspecialchars($_POST['business']);
    $address  = htmlspecialchars($_POST['address']);

    // Check Require Fields
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['business']) && !empty($_POST['address'])) {
      // Passed
      if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        // Failed
        $msg = 'Please use a valid email';
        $msgClass = 'alert-danger';
      } else {
        try {
          // (1)
          // Send Receipt to User/SME
          $toEmail = $email;
          $subject = 'Lagos State Economic Summit Registration Receipt';
          $body    = '<h2>Lagos State Economic Summit Registration Receipt</h2>
                      <b>Topic: </b> Lagos State Economic Summit - EHIGBETTI<br />
                      <b>Time: </b> Monday, September 1, 2020 10:00AM Africa/Lagos<br />
                      <br />
                      <b>Join Meeting: </b> <u>http://www.lagosstatevirtualconference.com/fluxtechtechafrica</u><br />
                      <br />
                      <b>Meeting ID: </b> '.$email.'<br />
                      <b>Passcode: </b> MySuperSecret<br />';
  
          // Email Headers
          $headers = "MIME-Version: 1.0" ."\r\n";
          $headers .="Content-Type:text/html;charset=UTF-8" . "\r\n";
  
          // Additional Headers
          $headers .= "From: " .$name. "<".$email.">". "\r\n";

          // (2)
          // Insert Registration Data in DB
          // insert query
          $query = "INSERT INTO user SET name=:name, email=:email, phone=:phone, business=:business, address=:address,date=:date";
    
          // prepare query for execution
          $stmt = $con->prepare($query);
                
          // bind the parameters
          $stmt->bindParam(':name', $name);
          $stmt->bindParam(':email', $email);
          $stmt->bindParam(':phone', $phone);
          $stmt->bindParam(':business', $business);
          $stmt->bindParam(':address', $address);
                      
          // specify when this record was inserted to the database
          $date = date('Y-m-d H:i:s');
          $stmt->bindParam(':date', $date);

          // Send Mail && Execute Query
          if (mail($toEmail, $subject, $body, $headers) && $stmt->execute()) {
              // Email Sent
              $msg = 'Your email has been sent';
              $msgClass = 'alert-success';
          } else {
            // Failed
            $msg = 'Your email was not sent';
            $msgClass = 'alert-danger';
          }
             
        } catch(PDOException $exception) {
          die('ERROR: ' . $exception->getMessage());
        }
      }
    } else {
      // Failed
      $msg = 'Please fill in all fields';
      $msgClass = 'alert-danger';
    }
  } 
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Lagos State Virtual Conference</title>
    <link rel="stylesheet" href="bootstrap.min.css" />
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="index.php">Lagos State Virtual Conference - DB</a>
      </div>
    </nav><br>

    <div class="container">

      <?php if($msg != ''): ?>
        <div class="alert <?php echo $msgClass; ?>">
          <?php echo $msg; ?>
        </div>
      <?php endif; ?>

      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
          <div class="form-group">
            <label for="">Name</label>
            <input type="text" name="name" class="form-control" id="" aria-describedby="" value="<?php echo isset($_POST['name']) ? $name : ''; ?>" placeholder="Enter name">
          </div>
          <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" id="" aria-describedby="" value="<?php echo isset($_POST['email']) ? $email : ''; ?>" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="">Phone Number</label>
            <input type="text" name="phone" class="form-control" id="" aria-describedby="" value="<?php echo isset($_POST['phone']) ? $phone : ''; ?>" placeholder="Enter phone number">
          </div>
          <div class="form-group">
            <labelw for="">Business Name</label>
            <input type="text" name="business" class="form-control" id="" aria-describedby="" value="<?php echo isset($_POST['business']) ? $business : ''; ?>" placeholder="Enter business name">
          </div>
          <div class="form-group">
            <label for="">Address</label>
            <textarea class="form-control" name="address" id="" rows="3"><?php echo isset($_POST['address']) ? $address : ''; ?></textarea>
          </div>
          <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </fieldset>
      </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>

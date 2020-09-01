<?php

  require_once 'sendgrid-php/sendgrid-php.php';

  // Message Vars
  $msg = '';
  $msgClass = '';

  // Check For Submit
  if (filter_has_var(INPUT_POST, 'submit')) {
    // Get form data-browse
    $name      = htmlspecialchars($_POST['name']);
    $formemail = htmlspecialchars($_POST['email']);
    $phone     = htmlspecialchars($_POST['phone']);
    $business  = htmlspecialchars($_POST['business']);
    $address   = htmlspecialchars($_POST['address']);

    // Check Reuire Fields
    if (!empty($name) && !empty($formemail) && !empty($phone) && !empty($business) && !empty($address)) {
      // Passed
      if (filter_var($formemail, FILTER_VALIDATE_EMAIL) === false) {
        // Failed
        $msg = 'Please use a valid email';
        $msgClass = 'alert-danger';
      } else {

        // Send to User / SME
        $email1 = new \SendGrid\Mail\Mail();
        $email1->setFrom("noreply@lagosstateeconomicconference.com", "Lagos State Economic Conference");
        $email1->setSubject("Lagos State Economic Conference Registration Receipt");
        $email1->addTo($formemail, $name);
        $email1->addContent(
            "text/html", "<h2>Lagos State Virtual Conference Registration Receipt</h2>
                            <h4>Venue</h4><p><u>http://www.lagosstatevirtualconference.com/fluxtechtechafrica</u></p>
                            <h4>Username</h4><p>'.$formemail.'</p>
                            <h4>Password</h4><p>MySuperSecret</p>"
        );
        $email1->addAttachment($memo_encoded, "application/text", $_FILES["memo"]["name"], "attachment");
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        try {
            $response1 = $sendgrid->send($email1);
            print $response1->statusCode() . "\n";
            print_r($response1->headers());
            print $response1->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }

        // Send to Business Owner
        $email2 = new \SendGrid\Mail\Mail();
        // $email->setFrom("test@example.com", "Example User");
        $email2->setSubject("Registration Receipt from " . $name);
        // $email->addTo("anthony@fluxtechafrica.com", "Example User");
        $email2->addTo("oyedotunsodiq045@gmail.com");
        $email2->addContent(
            "text/html", "<h2>Virtual Conference Registration Receipt</h2>
                            <h4>Name</h4><p>'.$name.'</p>
                            <h4>Email</h4><p>'.$formemail.'</p>
                            <h4>Contact Number</h4><p>'.$phone.'</p>
                            <h4>Business Name</h4><p>'.$business.'</p>
                            <h4>Address</h4><p>'.$address.'</p>"
        );
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        try {
            $response2 = $sendgrid->send($email2);
            print $response2->statusCode() . "\n";
            print_r($response2->headers());
            print $response2->body() . "\n";
            // Email Sent
            // $msg = 'Your email has been sent';
            // $msgClass = 'alert-success';
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
            // Failed
            // $msg = 'Your email was not sent';
            // $msgClass = 'alert-danger';
        }
        if ($response1 && $response2) {
          // Email Sent
          $msg = 'Your email has been sent';
          $msgClass = 'alert-success';
        } else {
          // Failed
          $msg = 'Your email was not sent';
          $msgClass = 'alert-danger';
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
        <a class="navbar-brand" href="index.php">Lagos State Virtual Conference</a>
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

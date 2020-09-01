<?php

    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Load Composer's autoloader
    require 'vendor/autoload.php';

  // Message Vars
  $msg = '';
  $msgClass = '';

  // Check For Submit
  if (filter_has_var(INPUT_POST, 'submit')) {
    // Get form data-browse
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $business = htmlspecialchars($_POST['business']);
    $address = htmlspecialchars($_POST['address']);

    // Check Reuire Fields
    if (!empty($name) && !empty($email) && !empty($phone) && !empty($business) && !empty($address)) {
      // Passed
      if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        // Failed
        $msg = 'Please use a valid email';
        $msgClass = 'alert-danger';
      } else {

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp1.example.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'user@example.com';                     // SMTP username
            $mail->Password   = 'secret';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress('ellen@example.com');               // Name is optional
            $mail->addReplyTo('info@example.com', 'Information');
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com');

            // Attachments
            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        // (1)
        // Send Registration to User/SME
        $toEmail1 = $email;
        $subject1 = 'Lagos State Virtual Conference Registration Receipt';
        $body1 = '<h2>Lagos State Virtual Conference Registration Receipt</h2>
                  <h4>Venue</h4><p><u>http://www.lagosstatevirtualconference.com/fluxtechtechafrica</u></p>
                  <h4>Time</h4><p>Monday, September 1, 2020 - 10:00AM West Central Africa</p>
                  <h4>Username</h4><p>'.$email.'</p>
                  <h4>Password</h4><p>MySuperSecret</p>';

        // Email Headers
        $headers1 = "MIME-Version: 1.0" ."\r\n";
        $headers1 .="Content-Type:text/html;charset=UTF-8" . "\r\n";

        // Additional Headers
        $headers1 .= "From: " .$name. "<".$email.">". "\r\n";

        // (2)
        // Send Registration to Business Owners
        $recipients = array(
          // "anthony@fluxtechafrica.com",
          "oyedotunsodiq045@gmail.com",
          "oyedotunsodiq045@yahoo.com",
          // more emails
        );
        $subject2 = 'Registration Receipt from ' .$name;
        $body2 = '<h2>Virtual Conference Registration Receipt</h2>
                  <h4>Name</h4><p>'.$name.'</p>
                  <h4>Email</h4><p>'.$email.'</p>
                  <h4>Contact Number</h4><p>'.$phone.'</p>
                  <h4>Business Name</h4><p>'.$business.'</p>
                  <h4>Address</h4><p>'.$address.'</p>';

        // Email Headers
        $headers2 = "MIME-Version: 1.0" ."\r\n";
        $headers2 .="Content-Type:text/html;charset=UTF-8" . "\r\n";

        // Additional Headers
        $headers2 .= "From: " .$name. "<".$email.">". "\r\n";

        // Save User Data in DB


        if (mail($recipients, $subject1, $body1, $headers1) && mail($toEmail2, $subject2, $body2, $headers2)) {

          // Send PDF - (Receipt) to User / SME

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
    <title>Lagos State Economic Summit</title>
    <link rel="stylesheet" href="bootstrap.min.css" />
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="index.php">Lagos State Economic Summit</a>
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

<?php

//Config
$adminEmail =  "andrewkeymolen@gmail.com";
$SendMailFailederrorMessage = "Sorry, something went wrong!";
$SendNameEmptyerrorMessage = "The name field is empty!";
$SendMailEmptyerrorMessage = "The email adress fiel is empty!";
$SendMailInvaliderrorMessage = "The provided email is invalid!";
$SendSubjectEmptyerrorMessage = "The subject field is empty!";
$SendMessageEmptyerrorMessage = "The message field is empty!";
$SendMailSuccessMessage = "Your message has been sent successfully! You should also have received a copy. Please check your SPAMS folder if you can't find it.";

//Headers
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Content-Type');

//JSON & POST
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

//Require for SendGrid
require 'vendor/autoload.php';

//Fields validation
if( empty($_POST['name'])) {
  echo json_encode(
    [
      "sent" => false,
      "message" => $SendNameEmptyerrorMessage,
      "fatalError" => false
    ]
  );
  exit();
}

if(empty($_POST['email']) ) {
  echo json_encode(
    [
      "sent" => false,
      "message" => $SendMailEmptyerrorMessage,
      "fatalError" => false
    ]
  );
  exit();
}  else {
  $email = $_POST['email'];
  // validating the email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(
      [
        "sent" => false,
        "message" => $SendMailInvaliderrorMessage,
        "fatalError" => false
      ]
    );
    exit();
  }
}

if( empty($_POST['subject'])) {
  echo json_encode(
    [
      "sent" => false,
      "message" => $SendSubjectEmptyerrorMessage,
      "fatalError" => false
    ]
  );
  exit();
}

if( empty($_POST['message']) ) {
  echo json_encode(
    [
      "sent" => false,
      "message" => $SendMessageEmptyerrorMessage,
      "fatalError" => false
    ]
  );
  exit();
}

//POST - Send mail
if ($_POST){

  http_response_code(200);

  $name = $_POST['name'];
  $senderadress = $_POST['email'];
  $subject = 'Contact from Centralize: ' . $name;
  $message = $_POST['message'];

  //copy to admin
  $email = new \SendGrid\Mail\Mail();
  $email->setFrom($adminEmail, "Centralize");
  $email->setSubject($subject);
  $email->addTo($adminEmail, "Andrew Keymolen");
  $email->addContent(
    "text/plain", 'Contact from: ' . $name . ', ' . $senderadress . ' - '  . $_POST['subject']
  );
  $email->addContent(
    "text/html", 'Contact from: ' . $name . ', ' . $senderadress . ' - '  . $_POST['subject'] . ' - '  . $message
  );
  $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
  try {
    $sendgrid->send($email);
  } catch (Exception $e) {
    // Tell the user about error
    echo json_encode(
      [
        "sent" => false,
        "message" => $SendMailFailederrorMessage,
        "fatalError" => true
      ]
    );
  }

  //copy to sender
  $email = new \SendGrid\Mail\Mail();
  $email->setFrom($adminEmail, "Andrew Keymolen");
  $email->setSubject("Your copy of " . $_POST['subject']);
  $email->addTo($senderadress, $name);
  $email->addContent(
    "text/plain", $message
  );
  $email->addContent(
    "text/html", $message
  );
  $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
  try {
    $sendgrid->send($email);
  } catch (Exception $e) {
    // Tell the user about error
    echo json_encode(
      [
        "sent" => false,
        "message" => $SendMailFailederrorMessage,
        "fatalError" => true
      ]
    );
  }

  //Success
  echo json_encode(
    [
      "sent" => true,
      "message" => $SendMailSuccessMessage,
      "fatalError" => false
    ]
  );

} else {
  // Tell the user about error
  echo json_encode(
    [
      "sent" => false,
      "message" => $SendMailFailederrorMessage,
      "fatalError" => true
    ]
  );
}

?>

<?php

$adminEmail =  "andrewkeymolen@gmail.com";
$SendMailFailederrorMessage = "Sorry, something went wrong!";
$SendNameEmptyerrorMessage = "The name field is empty!";
$SendMailEmptyerrorMessage = "The email adress fiel is empty!";
$SendMailInvaliderrorMessage = "The provided email is invalid!";
$SendSubjectEmptyerrorMessage = "The subject field is empty!";
$SendMessageEmptyerrorMessage = "The message field is empty!";
$SendMailSuccessMessage = "Your message has been sent successfully! You should also have received a copy. Please check your SPAMS folder if you can't find it.";

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Content-Type');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

require 'vendor/autoload.php';

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

if ($_POST){
  //@important: Please change this before using
  http_response_code(200);
  $subject = 'Contact from: ' . $_POST['name'] . ': '  . $_POST['subject'];
  $from = $_POST['email'];
  $name2 = $_POST['name'];
  $subjectCopy = $_POST['subject'];
  $message = $_POST['message'];
  var_dump("Validated");

  $email = new \SendGrid\Mail\Mail();
  $email->setFrom($from, $name2);
  $email->setSubject($subjectCopy);
  $email->addTo("andrewkeymolen@gmail.com", "Andrew Keymolen");
  $email->addContent(
      "text/plain", $message
  );
  $email->addContent(
      "text/html", $message
  );
  $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
  try {
      $response = $sendgrid->send($email);
      print $response->statusCode() . "\n";
      print_r($response->headers());
      print $response->body() . "\n";
  } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n";
  }






  //Actual sending email
  /*$sendEmail = new Sender($adminEmail, $from, $subject, $message, $subjectCopy, $name2);
  var_dump("Sender constructed");
  if ($sendEmail->send()){
    var_dump("Success ?");
    echo json_encode(
      [
        "sent" => true,
        "message" => $SendMailSuccessMessage,
        "fatalError" => false
      ]
    );
  } else {
    var_dump("Send Error");
    echo json_encode(
      [
        "sent" => false,
        "message" => $SendMailFailederrorMessage,
        "fatalError" => true
      ]
    );
  }*/
} else {
  // tell the user about error
  var_dump("POST Error");
  echo json_encode(
    [
      "sent" => false,
      "message" => $SendMailFailederrorMessage,
      "fatalError" => true
    ]
  );
}

/*
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

if ($_POST){
  //@important: Please change this before using
  http_response_code(200);
  $subject = 'Contact from: ' . $_POST['name'] . ': '  . $_POST['subject'];
  $from = $_POST['email'];
  $name2 = $_POST['name'];
  $subjectCopy = $_POST['subject'];
  $message = $_POST['message'];
  var_dump("Validated");
  //Actual sending email
  $sendEmail = new Sender($adminEmail, $from, $subject, $message, $subjectCopy, $name2);
  var_dump("Sender constructed");
  if ($sendEmail->send()){
    var_dump("Success ?");
    echo json_encode(
      [
        "sent" => true,
        "message" => $SendMailSuccessMessage,
        "fatalError" => false
      ]
    );
  } else {
    var_dump("Send Error");
    echo json_encode(
      [
        "sent" => false,
        "message" => $SendMailFailederrorMessage,
        "fatalError" => true
      ]
    );
  }
} else {
  // tell the user about error
  var_dump("POST Error");
  echo json_encode(
    [
      "sent" => false,
      "message" => $SendMailFailederrorMessage,
      "fatalError" => true
    ]
  );
}

class Sender
{
  public $sendTo;
  public $sendFrom;
  public $email;
  public $email_subject;
  public $email_message;
  public $email_headers;
  public $subject;
  public $message;
  public $headers;
  public $error = [];

  public function __construct($sendTo, $sendFrom = null, $subject, $message, $subjectCopy, $name)
  {
    var_dump("Begin construct Sender");
    $this->sendTo = $sendTo;
    $this->sendFrom = ($sendFrom) ? $sendFrom : 'keymolenandrew@gmail.com';
    $this->subject = $subject;
    $this->message = $message;
    $this->name = $name;
    $this->subjectCopy = $subjectCopy;
    var_dump("End construct Sender");
  }

  public function setTo($email, $name) {
    return $this->sendTo = $email;
  }

  public function getTo() {
    return $this->sendTo;
  }

  public function setFrom($email, $name)  {
    return $this->sendFrom = $email;
  }

  public function setSubject($subject) {
    return $this->subject = $subject;
  }

  public function getSubject() {
    return $this->subject;
  }

  public function setMessage($message) {
    $this->message = $message;
    return $this;
  }

  public function getMessage() {
    return $this->message;
  }

  public function setHeader() {
    $headers = 'From: '.$this->getFrom() . "\r\n" .
    'Reply-To: '.$this->getFrom() . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

    $this->headers = $headers;
    return $this;
  }

  public function getFrom() {
    return $this->sendFrom;
  }

  public function send() {
    var_dump("Begin send");

    //Mail to admin
    $to = $this->sendTo;
    $from = $this->sendFrom;
    $subject = $this->subject;
    $message = $this->message;
    //$headers = $this->headers;
    $headers = 'From: '.$this->getFrom() . "\r\n" .
    'Reply-To: '.$this->getFrom() . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    var_dump("End init first mail");

    mail($to, $subject, $message, $headers);
    var_dump("End first mail");


    //Copy to sender
    $email = $this->sendFrom;
    $name = $this->name;
    $subjectCopy = $this->subjectCopy;
    $email_subject = "Submission was successful";
    $email_message = "\nThank you for contacting me, I'll reply to you asap!\n\n\n";
    $email_message .= "Here's what you sent me:\n\n";
    $email_message .= "- Name: ".$name."\n\n";
    $email_message .= "- Email: ".$email."\n\n";
    $email_message .= "- Subject: ".$subjectCopy."\n\n";
    $email_message .= "- Message: \n\n".$message."\n\n";
    // create email headers
    $email_headers = 'From: '.$to."\r\n".
    'Reply-To: '.$to."\r\n" .
    "MIME-Version: 1.0\r\n" .
    "Content-Type: text/plain; charset=iso-8859-1\r\n";
    var_dump("End init second mail");
    mail($email, $email_subject, $email_message, $email_headers);
    var_dump("End second mail");
  }

}*/
?>

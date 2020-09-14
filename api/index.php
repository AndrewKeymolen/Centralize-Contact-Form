<?php
// Uncomment next line if you're not using a dependency loader (such as Composer)
// require_once '<PATH TO>/sendgrid-php.php';

use SendGrid\Mail\Mail;

$email = new Mail();

// For a detailed description of each of these settings,
// please see the
// [documentation](https://sendgrid.com/docs/API_Reference/api_v3.html).
$email->setSubject("Sending with Twilio SendGrid is Fun 2");

$email->addTo("test@example.com", "Example User");
$email->addTo("test+1@example.com", "Example User1");
$toEmails = [
    "test+2@example.com" => "Example User2",
    "test+3@example.com" => "Example User3"
];
$email->addTos($toEmails);

$email->addCc("test+4@example.com", "Example User4");
$ccEmails = [
    "test+5@example.com" => "Example User5",
    "test+6@example.com" => "Example User6"
];
$email->addCcs($ccEmails);

$email->addBcc("test+7@example.com", "Example User7");
$bccEmails = [
    "test+8@example.com" => "Example User8",
    "test+9@example.com" => "Example User9"
];
$email->addBccs($bccEmails);

$email->addHeader("X-Test1", "Test1");
$email->addHeader("X-Test2", "Test2");
$headers = [
    "X-Test3" => "Test3",
    "X-Test4" => "Test4",
];
$email->addHeaders($headers);

$email->addDynamicTemplateData("subject1", "Example Subject 1");
$email->addDynamicTemplateData("name1", "Example Name 1");
$email->addDynamicTemplateData("city1", "Denver");
$substitutions = [
    "subject2" => "Example Subject 2",
    "name2" => "Example Name 2",
    "city2" => "Orange"
];
$email->addDynamicTemplateDatas($substitutions);

$email->addCustomArg("marketing1", "false");
$email->addCustomArg("transactional1", "true");
$email->addCustomArg("category", "name");
$customArgs = [
    "marketing2" => "true",
    "transactional2" => "false",
    "category" => "name"
];
$email->addCustomArgs($customArgs);

$email->setSendAt(1461775051);

// You can add a personalization index or personalization parameter to the above
// methods to add and update multiple personalizations. You can learn more about
// personalizations [here](https://sendgrid.com/docs/Classroom/Send/v3_Mail_Send/personalizations.html).

// The values below this comment are global to an entire message

$email->setFrom("test@example.com", "Twilio SendGrid");

$email->setGlobalSubject("Sending with Twilio SendGrid is Fun and Global 2");

$email->addContent(
    "text/plain",
    "and easy to do anywhere, even with PHP"
);
$email->addContent(
    "text/html",
    "<strong>and easy to do anywhere, even with PHP</strong>"
);
$contents = [
    "text/calendar" => "Party Time!!",
    "text/calendar2" => "Party Time 2!!"
];
$email->addContents($contents);

$email->addAttachment(
    "base64 encoded content1",
    "image/png",
    "banner.png",
    "inline",
    "Banner"
);
$attachments = [
    [
        "base64 encoded content2",
        "banner2.jpeg",
        "image/jpeg",
        "attachment",
        "Banner 3"
    ],
    [
        "base64 encoded content3",
        "banner3.gif",
        "image/gif",
        "inline",
        "Banner 3"
    ]
];
$email->addAttachments($attachments);

$email->setTemplateId("d-13b8f94fbcae4ec6b75270d6cb59f932");

$email->addGlobalHeader("X-Day", "Monday");
$globalHeaders = [
    "X-Month" => "January",
    "X-Year" => "2017"
];
$email->addGlobalHeaders($globalHeaders);

$email->addSection("%section1%", "Substitution for Section 1 Tag");
$sections = [
    "%section3%" => "Substitution for Section 3 Tag",
    "%section4%" => "Substitution for Section 4 Tag"
];
$email->addSections($sections);

$email->addCategory("Category 1");
$categories = [
    "Category 2",
    "Category 3"
];
$email->addCategories($categories);

$email->setBatchId(
    "MWQxZmIyODYtNjE1Ni0xMWU1LWI3ZTUtMDgwMDI3OGJkMmY2LWEzMmViMjYxMw"
);

$email->setReplyTo("dx+replyto2@example.com", "DX Team Reply To 2");

$email->setAsm(1, [1, 2, 3, 4]);

$email->setIpPoolName("23");

// Mail Settings
$email->setBccSettings(true, "bcc@example.com");
$email->enableBypassListManagement();
//$email->disableBypassListManagement();
$email->setFooter(true, "Footer", "<strong>Footer</strong>");
$email->enableSandBoxMode();
//$email->disableSandBoxMode();
$email->setSpamCheck(true, 1, "http://mydomain.com");

// Tracking Settings
$email->setClickTracking(true, true);
$email->setOpenTracking(true, "--sub--");
$email->setSubscriptionTracking(
    true,
    "subscribe",
    "<bold>subscribe</bold>",
    "%%sub%%"
);
$email->setGanalytics(
    true,
    "utm_source",
    "utm_medium",
    "utm_term",
    "utm_content",
    "utm_campaign"
);

$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '.  $e->getMessage(). "\n";
}


/*

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

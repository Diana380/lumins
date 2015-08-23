<?php
include('lib/class.phpmailer.php');			
include('lib/class.smtp.php');				

class mailserver
{
	// ------------------------------------
	// configuration
	// ------------------------------------
	
	// your email address
	private $recipient = 'dtw12@brighton.ac.uk';



	
	public $response = array();  	// JSON output
	
	
	
    //////////////////////////////////////////////////////////////////////////////////////////
	function __construct()
    {
	
	}
	
    //////////////////////////////////////////////////////////////////////////////////////////
	function __destruct()
    {
		
	}

    //////////////////////////////////////////////////////////////////////////////////////////
	private function getParam($name)
	{
		if(isset($_GET[$name])) return urldecode($_GET[$name]);
		else				 	return null;
	}

    //////////////////////////////////////////////////////////////////////////////////////////
	private function postParam($name)
	{
		if(isset($_POST[$name])) return urldecode($_POST[$name]);
		else				 	 return null;
	}

    //////////////////////////////////////////////////////////////////////////////////////////
	public function handleRequest()
	{
		if(array_key_exists ('error', $this->response)) return;

		
		$email = $this->postParam('email');
		$subject = $this->postParam('subject');
		$message = $this->postParam('message');
		
		if($email == null) 				{$this->response['error'] = 'Parameter email missing';}	
		else if($subject == null) 		{$this->response['error'] = 'Parameter subject missing';}	
		else if($message == null) 		{$this->response['error'] = 'Parameter message missing';}	
		else 
		{
			//
			// forward message to our email address
			//
			$mail = new PHPMailer(true);
			try
			{
				$mail->IsSMTP(); 
				$mail->SMTPDebug = 0; // SMTP debug information: 1 = errors and messages, 2 = messages only
				$mail->SMTPAuth = true;  
				$mail->SMTPSecure = 'ssl'; 
				$mail->Host = 'smtp.gmail.com'; 
				$mail->Port = 465;
				$mail->Username = 'idm18mail@gmail.com';
				$mail->Password = 'watts234';
				$mail->AddReplyTo($email, $email);
				$mail->SetFrom('idm18mail@gmail.com', 'IDM18 Mailbox');
				$mail->AddAddress($this->recipient, $this->recipient);
				$mail->Subject = $subject;
				$mail->Body = $message;
				$mail->Send();
			}
			catch (phpmailerException $e) 
			{
				$this->response['error'] = $e->errorMessage(); 
			} 
			catch (Exception $e) 
			{
				$this->response['error'] = $e->getMessage(); 
			}	



			//
			// set response status
			//
			$this->response['status'] = (array_key_exists ('error', $this->response)) ? 'err' : 'ok';
			
		} 
	} // handleRequest()
} // end class ubiserver

header('Content-type: application/json; charset=UTF-8');
$server = new mailserver();
$server->handleRequest();
echo json_encode($server->response);
?>
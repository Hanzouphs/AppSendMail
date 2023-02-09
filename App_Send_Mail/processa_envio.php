<?php
	
	require "./bibliotecas/PHPMailer/OAuthTokenProvider.php";
	require "./bibliotecas/PHPMailer/Exception.php";
	require "./bibliotecas/PHPMailer/OAuth.php";
	require "./bibliotecas/PHPMailer/PHPMailer.php";
	require "./bibliotecas/PHPMailer/POP3.php";
	require "./bibliotecas/PHPMailer/SMTP.php";

	use PHPMailer\PHPMailer\PHPmailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	//print_r($_POST);

	class Mensagem {

		private $para = null;
		private $assunto = null;
		private $mensagem = null;
		public $status = array('codigo_status' => null, 'descricao_status' => '');


		public function __get($atributo) {
			return $this->$atributo;
		}

		public function __set($atributo, $valor) {
			$this->$atributo = $valor;
		}

		public function mensagemValida() {
			if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
				return false;
			}

			return true;
		}

	}


	$mensagem = new Mensagem();

	$mensagem->__set('para', $_POST['para']);
	$mensagem->__set('assunto', $_POST['assunto']);
	$mensagem->__set('mensagem', $_POST['mensagem']);

	//print_r($mensagem);

	if (!$mensagem->mensagemValida()) {
		echo 'A mensagem não é válida';
		header('Location:index.php');
	}


$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = false;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'iorinroll213@gmail.com';                     //SMTP username
    $mail->Password   = 'mlesftrmqolbefwy';                               //SMTP password
    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('iorinroll213@gmail.com', 'Projeto App');
    $mail->addAddress($mensagem->__get('para'));     //Add a recipient
   // $mail->addAddress('ellen@example.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
  //  $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'É necessário utilizar um cliente de e-mail que suporte o conteúdo da menssagem!';

    $mail->send();

    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = '<b>Menssagem enviada com sucesso! :D<b/>';
    
} catch (Exception $e) {

	$mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = 'Não foi possível enviar esse e-mail, por favor, tente novamente mais tarde. Detalhes do erro: ' . $mail->ErrorInfo;  
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>App Mail Send</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

	<div class="container">  

			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead"><h5 class="text-primary">Your personal email sending app!</h5></p>
			</div>

			<div class="row justify-content-center">

				<div class="col-md-12">

					<?php if ($mensagem->status['codigo_status'] == 1) { ?>

						<div class="container text-center mt-5">

							<h1 class="displa-4 text-success">Sucesso</h1>
							<p><?=$mensagem->status['descricao_status']?></p>
							<a class="btn btn-success btn-lg mt-5 text-white" href="index.php">Voltar</a>
							
						</div>

						
					<?php } ?>

					<?php if ($mensagem->status['codigo_status'] == 2) { ?>

						<div class="container">

							<h1 class="displa-4 text-danger">Ops!</h1>
							<p><?=$mensagem->status['descricao_status']?></p>
							<a class="btn btn-danger btn-lg mt-5 text-white" href="index.php">Voltar</a>
							
						</div>						
						
					<?php } ?>
					
				</div>
				
			</div>


</body>
</html>
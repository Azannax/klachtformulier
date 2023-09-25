<?php ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\Dotenv\Dotenv;

require '../vendor/autoload.php';

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('name');
$log->pushHandler(new StreamHandler('info.log', Level::Warning));

// add records to the log


$mail = new PHPMailer(true);

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');


$host = $_ENV["HOST"];
$username = $_ENV["USERNAME"];
$password = $_ENV["PASSWORD"];
$name = $_ENV["NAME"];



if (isset($_POST['submit'])) { // Controleer of het formulier is verzonden
    try {
        //Serverinstellingen
        $mail->isSMTP();
        $mail->Host       = $host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $username;
        $mail->Password   = $password;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = $_ENV["Port"];

        //Ontvangers
        $naam = $_POST['naam'];
        $email = $_POST['email'];
        $klacht = $_POST['klacht'];


        //Inhoud
        
        $mail->Subject = 'Uw klacht is in behandeling';
        $mail->Body    = $klacht;

        // Instellingen voor ontvanger en berichtinhoud
        $mail->setFrom ($username, $naam);
        $mail->addAddress($email);
        $mail->addAddress ($username);

        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Schakel gedetailleerde logboeken uit

        $mail->send();
        echo 'Uw klacht is verzonden';
    } catch (Exception $e) {
        echo "Er is een fout opgetreden: {$mail->ErrorInfo}";
    }
}

        $log->warning($naam);
        $log->error($email);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>klachtformulier</title>
</head>
<body>
   <form method="POST" action="index.php">
    <input type="text" name="naam" placeholder="naam" required>
    <input type="text" name="email" placeholder="email" required>
    <input type="text" name="klacht" placeholder="klacht" required>
    <input type="submit" name="submit" value="submit">
</form>
</body>
</html>

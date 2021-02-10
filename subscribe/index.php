<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('ETC/UTC');

require 'vendor/autoload.php';
require 'esputnik.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;
use ESputnikEmail\ESputnikEmail;

/* Данные из формы */
$contents = file_get_contents('php://input');
parse_str($contents, $data);
$groupName = 'Казахстан Greenhouse online (14.12.2020)';

$fio = explode(" ", $data['name']);
$clienName = isset($fio[0]) ? $fio[0] : '';
$clientLastName = isset($fio[1]) ? $fio[1] : '';
$clientFullName = $data['name'];
$clientEmail = $data['email'];
$clientPhone = $data['phone'];
$clientCompany = $data['company'];

/* Настройки для почты */
$setFrom = 'Bio Group';
$email = 'noreply@bio-group.net';
$clientId = '258691655239-ndvsiraaq6dibheovg0jlp6bc82lngjl.apps.googleusercontent.com';
$clientSecret = 'Q6aIZef7q616k499Svti_UWB';
$refreshToken = '1//0960e6uSsVBZECgYIARAAGAkSNwF-L9Ir04R2TYIE_FK8_B2YpZdRTlPwYxnWqSJA0d_xmRDdCQTq8jYKLj0vMXQ8CRx2peYBPSo';
/* --- */

/* Настройки для eSputnik */
$esputnikLogin = 'pro@bio-group.net';
$esputnikPassword = 'qwerty12345';
/* --- */


/* --- */

$mail = new PHPMailer();
$mail->isSMTP();

//Enable SMTP debugging
// SMTP::DEBUG_OFF = off (for production use)
// SMTP::DEBUG_CLIENT = client messages
// SMTP::DEBUG_SERVER = client and server messages
$mail->SMTPDebug = SMTP::DEBUG_OFF;

$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->SMTPAuth = true;
$mail->AuthType = 'XOAUTH2';

$provider = new Google(
    [
        'clientId' => $clientId,
        'clientSecret' => $clientSecret,
    ]
);

$mail->setOAuth(
    new OAuth(
        [
            'provider' => $provider,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'refreshToken' => $refreshToken,
            'userName' => $email,
        ]
    )
);

$mail->setFrom($email, $setFrom);
$mail->addAddress($clientEmail, $clientFullName);
$mail->addCC('pro@bio-group.net');
$mail->Subject = 'Благодарим за регистрацию';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->CharSet = PHPMailer::CHARSET_UTF8;
$mail->msgHTML(file_get_contents('email.html'), __DIR__);
$mail->AltBody = 'Благодарим за регистрацию на вебинар "КАЗАХСТАН GREENHOUSE ONLINE"! До встречи в эфире 14 декабря 2020 г. в 10:00.';

//send the message, check for errors
if (!$mail->send()) {
    $result = [
        "message" => 'Ошибка при регистрации',
        "error" => true,
        "info" => $mail->ErrorInfo,
    ];
    echo json_encode($result);

} else {
    $result = [
        "message" => 'Благодарим за регистрацию',
        "error" => false,
        "info" => "",
    ];

    $eSputnik = new ESputnikEmail($esputnikLogin, $esputnikPassword);
    $eSputnik
        ->addClient($groupName, $clienName, $clientLastName, $clientEmail, $clientPhone)
        ->saveContact();

    echo json_encode($result);
}
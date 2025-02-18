<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $formName = isset($_POST['FormName']) ? htmlspecialchars($_POST['FormName']) : 'Без имени';
    $name = isset($_POST['Name']) ? htmlspecialchars($_POST['Name']) : 'Не указано';
    $phone = isset($_POST['Phone']) ? htmlspecialchars($_POST['Phone']) : 'Не указан';
    
    // Получаем выбранный мессенджер
    $contact_method = isset($_POST['contact_method']) ? htmlspecialchars($_POST['contact_method']) : 'Не выбран';

    // Получаем тариф и программу
    $tarif = isset($_POST['Tarif']) ? htmlspecialchars($_POST['Tarif']) : 'Не указан';
    $program = isset($_POST['Program']) ? htmlspecialchars($_POST['Program']) : 'Не указана';

    // Создаем тело письма
    $body = "<h3>Новая заявка с формы: {$formName}</h3>";
    $body .= "<p><strong>Имя:</strong> {$name}</p>";
    $body .= "<p><strong>Телефон:</strong> {$phone}</p>";
    $body .= "<p><strong>Тариф:</strong> {$tarif}</p>";
    $body .= "<p><strong>Программа:</strong> {$program}</p>";
    $body .= "<p><strong>Выбранный мессенджер:</strong> {$contact_method}</p>";

    $mail = new PHPMailer(true);

    try {
        // Настройки PHPMailer
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'smtp.beget.com'; // Ваш SMTP-сервер Beget
        $mail->SMTPAuth = true;
        $mail->Username = 'zakaz@speloekids.ru'; // Логин для SMTP
        $mail->Password = '90ajV4a%FLZs'; // Пароль для SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Порт для TLS

        // Отправка письма
        $mail->setFrom('zakaz@speloekids.ru', 'Новая заявка');
        $mail->addAddress('kryukovs.ru@gmail.com', 'Получатель'); // Ваш адрес для получения

        // Настройки письма
        $mail->isHTML(true);
        $mail->Subject = 'Новая заявка с сайта';
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body); // Текстовое тело для клиентов, не поддерживающих HTML

        // Отправка письма
        $mail->send();

        // Перенаправление на страницу благодарности
        header('Location: /thank-you/');
        exit;
    } catch (Exception $e) {
        echo "Ошибка отправки: {$mail->ErrorInfo}";
    }
} else {
    echo 'Доступ запрещен';
}
?>

<!-- <?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../../vendor/autoload.php";

function sendResetEmail($toEmail, $resetLink) {

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = "smtp.hostinger.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = "no-reply@yourdomain.com"; // Hostinger email
        $mail->Password   = "EMAIL_PASSWORD";          // Hostinger email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom("no-reply@yourdomain.com", "MediLearn");
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = "Reset Your MediLearn Password";

        $mail->Body = "
            <p>Hello,</p>
            <p>You requested a password reset.</p>
            <p>
                <a href='{$resetLink}' 
                   style='background:#1d7afc;color:#fff;padding:10px 16px;
                   border-radius:6px;text-decoration:none;'>
                   Reset Password
                </a>
            </p>
            <p>This link will expire in 30 minutes.</p>
            <p>If you didn't request this, please ignore.</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
} -->

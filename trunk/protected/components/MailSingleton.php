<?php

/**
 * MailSingleton para gestionar el envío de correos
 */
class MailSingleton extends CApplicationComponent
{
    /**
     * @param $data array:
     *      to - Destinatario o array de destinatarios
     *      subject - Asunto
     *      body - Cuerpo
     */
    public function sendEmail($data)
    {
        //envio mails
        $mail = new YiiMailer();
        $mail->IsSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup server
        $mail->Port = 465;
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = Yii::app()->params->mailServerUsername;                            // SMTP username
        $mail->Password = Yii::app()->params->mailServerPassword;                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
        $mail->IsHTML(true);                                  // Set email format to HTML

        //$mail->clearLayout();//if layout is already set in config
        $mail->UseSendmailOptions = false;
        $mail->setFrom(Yii::app()->params->adminEmail, 'Omelettus');

        $mail->setBcc($data['to']);
        $mail->setSubject($data['subject']);
        $mail->setBody($data['body']);


        /*Setting addresses

        When using methods for setting addresses (setTo(), setCc(), setBcc(), setReplyTo()) any of the following is valid for arguments:

        $mail->setTo('john@example.com');
        $mail->setTo(array('john@example.com','jane@example.com'));
        $mail->setTo(array('john@example.com'=>'John Doe','jane@example.com'));*/



        if ($mail->send()) {
            return true;
        } else {
            return "Error enviando email: ".$mail->getError();
        }
    }

	
}



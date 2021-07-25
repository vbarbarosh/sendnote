<?php

if (isset($_GET['email'])) {
    $email = strval($_GET['email']);
}
else if (isset($_POST['email'])) {
    $email = strval($_POST['email']);
    setcookie('email', $email, time() + 60*60*24*365);
}
else if (isset($_COOKIE['email'])) {
    $email = strval($_COOKIE['email']);
}
else {
    $email = null;
}

if (isset($_GET['message'])) {
    $message = strval($_GET['message']);
}
else if (isset($_POST['message'])) {
    $message = strval($_POST['message']);
}
else {
    $message = null;
}

$attachments = [];

if (isset($_FILES['attachments'])) {
    foreach ($_FILES['attachments']['error'] as $index => $error) {
        if ($error == 0) {
            $attachments[] = [
                'file' => $_FILES['attachments']['tmp_name'][$index],
                'title' => $_FILES['attachments']['name'][$index],
                'mime' => $_FILES['attachments']['type'][$index],
            ];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($email) && (!empty($message) || !empty($attachments))) {
        send($email, $message, $attachments);
    }
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>sendnote</title>
    <link href="static/lib/bootstrap-3.1.1-dist/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
    <link href="static/css/screen.css" type="text/css" rel="stylesheet" />
</head>
<body>

<div class="container">
<div class="row">
    <div class="col-sm-8 col-sm-offset-2 col-lg-7 col-lg-offset-3">
        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <input class="form-control flat" type="email" name="email" value="<?php e($email) ?>" placeholder="hello@example.com" />
            </div>

            <div class="form-group">
                <textarea class="form-control flat" name="message" id="text" cols="30" rows="1" placeholder="What a beautiful day..." autofocus="autofocus"></textarea>
            </div>

            <div id="file-list"></div>

            <div class="text-right">
                <button class="js-file-add btn btn-success flat">Add File</button>
                <button name="submit" type="submit" class="btn btn-primary flat">Submit</button>
            </div>

        </form>
    </div>
</div>
</div>

<script src="static/lib/jquery-1.11.0.min.js"></script>
<script src="static/lib/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script>
<script src="static/lib/autosize/jquery.autosize.min.js"></script>
<script src="static/app.js"></script>

</body>
</html>
<?php

function e()
{
    echo implode('', array_map('htmlspecialchars', func_get_args()));
}

function send($email, $message, $attachments = [])
{
    global $email;

    $from = 'sendnote <noreply@example.com>';
    $mailto = $email;
    $replyTo = 'noreply@example.com';

    $subject = 'Send Note';
    // $subject = 'Your e-mail subject here';
    // $message = 'Your e-mail message here';

    // $content = file_get_contents(__FILE__);
    // $content = chunk_split(base64_encode($content));

    $boundary = md5(uniqid(null, true)); 

    $header = '';
    $header .= "From: $from\r\n";
    $header .= "Reply-To: $replyTo\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
    
    $body = '';
    $body .= "This is a multi-part message in MIME format.\r\n";

    $body .= "--$boundary\r\n";
    $body .= "Content-type: text/plain; charset=iso-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $message."\r\n\r\n";

    foreach ($attachments as $attachment) {
        $title = addslashes($attachment['title']);
        $mime = $attachment['mime'];
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $mime; name=\"$title\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$title\"\r\n\r\n";
        $body .= chunk_split(base64_encode(file_get_contents($attachment['file']))) . "\r\n\r\n";
    }

    $body .= "--$boundary--";

    return mail($mailto, $subject, $body, $header);
}

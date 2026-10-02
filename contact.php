<?php
/**
 * TDK Designs: quote form handler.
 * Receives the form on contact.html and emails it to the address below.
 * To change where enquiries go, edit $recipient.
 */

$recipient = "info@tdkdesigns.co.za";

$isAjax = isset($_SERVER["HTTP_X_REQUESTED_WITH"]);

function finish($ok, $code, $text, $isAjax) {
    if ($isAjax) {
        http_response_code($code);
        header("Content-Type: text/plain; charset=UTF-8");
        echo $text;
    } else {
        // No JavaScript: send the visitor back to the contact page with a result flag.
        header("Location: contact.html?sent=" . ($ok ? "1" : "0") . "#quote", true, 303);
    }
    exit;
}

function clean_line($value, $max) {
    // Single-line fields: strip tags and line breaks so they cannot inject email headers.
    $value = strip_tags(trim((string) $value));
    $value = preg_replace('/[\r\n\t]+/', ' ', $value);
    return mb_substr($value, 0, $max);
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    finish(false, 405, "Please use the form on the contact page.", $isAjax);
}

// Honeypot: real visitors never see or fill this field.
if (!empty($_POST["website"])) {
    finish(true, 200, "Message sent.", $isAjax);
}

$name    = clean_line($_POST["name"] ?? "", 100);
$phone   = clean_line($_POST["phone"] ?? "", 30);
$service = clean_line($_POST["service"] ?? "", 60);
$email   = filter_var(trim((string) ($_POST["email"] ?? "")), FILTER_VALIDATE_EMAIL);
$message = mb_substr(trim(strip_tags((string) ($_POST["message"] ?? ""))), 0, 4000);

if ($name === "" || !$email || $message === "") {
    finish(false, 400, "Please fill in your name, a valid email address and a message.", $isAjax);
}

$subject = "Website enquiry from " . $name . ($service !== "" ? " (" . $service . ")" : "");

$body  = "Name: " . $name . "\n";
$body .= "Email: " . $email . "\n";
$body .= "Phone: " . ($phone !== "" ? $phone : "not given") . "\n";
$body .= "Service: " . ($service !== "" ? $service : "not chosen") . "\n\n";
$body .= "Message:\n" . $message . "\n";

// Send from the site's own domain (better deliverability) and reply to the visitor.
$host = preg_replace('/^www\./', '', preg_replace('/[^a-z0-9.\-]/i', '', $_SERVER["SERVER_NAME"] ?? "localhost"));
$headers  = "From: TDK Designs website <no-reply@" . $host . ">\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($recipient, $subject, $body, $headers)) {
    finish(true, 200, "Message sent.", $isAjax);
}

finish(false, 500, "Your message was not sent. Please call or WhatsApp us on 071 373 6835.", $isAjax);

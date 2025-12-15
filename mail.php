<?php

// ============================
// CONFIG
// ============================
$to = "pavan@ymg-legal.com";      // WHO receives the email
$subject = "Intelligent Automation Services for Modern Enterprises"; // email subject as per LP title

$successPage = "thankyou.html"; // redirection after form submission
$errorPage   = "error.html";

// ============================
// ALLOW ONLY POST
// ============================
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: $errorPage");
    exit;
}

// ===============================
// TrustedForm Certificate Capture
// ===============================
$trustedForm = "";
if (isset($_POST['xxTrustedFormCertUrl']) && $_POST['xxTrustedFormCertUrl'] !== "") {
    $trustedForm = $_POST['xxTrustedFormCertUrl'];
}

// ============================
// EMAIL BODY (YOUR CODE — UNCHANGED)`
// ============================
$table = "
<h2 style='font-family: Arial; margin-bottom:10px;'>New Lead Received</h2>
";

if ($trustedForm) {
    $table .= "
    <p style='font-family: Arial;'>
        <strong>TrustedForm Certificate:</strong><br>
        <a href='$trustedForm' target='_blank'>$trustedForm</a>
    </p>
    ";
}

$table .= "
<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; font-family: Arial; margin-top:10px;'>
<tr style='background:#f2f2f2; font-weight:bold;'>
    <td>Field</td>
    <td>Value</td>
</tr>
";

// Loop through POST fields
foreach ($_POST as $key => $value) {

    if ($key == "formId") continue;
    if ($key == "xxTrustedFormCertUrl") continue;

    // Special handling for consent checkbox
    if ($key == 'consent') {
        $value = isset($_POST['consent']) ? "Yes" : "No";
    } else {
        if (is_array($value)) {
            $value = implode(", ", $value);
        }
    }

    $key = htmlspecialchars($key);
    $value = htmlspecialchars($value);

    $table .= "
    <tr>
        <td style='background:#fafafa; font-weight:bold;'>$key</td>
        <td>$value</td>
    </tr>";
}

$table .= "</table>";

// ============================
// HEADERS
// ============================
$fromEmail = $_POST['email'] ?? 'no-reply@ymg-legal.com';   // WHO is sending the email

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "Reply-To: $fromEmail\r\n";

// ============================
// SEND EMAIL & REDIRECT
// ============================
if (mail($to, $subject, $table, $headers)) {
    header("Location: $successPage");
    exit;
} else {
    header("Location: $errorPage");
    exit;
}

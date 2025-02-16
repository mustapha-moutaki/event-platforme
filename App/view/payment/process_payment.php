<?php
// استلام البيانات من PayPal
$data = json_decode(file_get_contents("php://input"), true);

if ($data && isset($data['id'])) {
    $paymentID = $data['id'];
    $payerName = $data['payer']['name']['given_name'];
    $payerEmail = $data['payer']['email_address'];
    $amount = $data['purchase_units'][0]['amount']['value'];

    // الاتصال بقاعدة البيانات
    $conn = new mysqli("localhost", "root", "", "events_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // تخزين بيانات الدفع
    $stmt = $conn->prepare("INSERT INTO payments (payment_id, name, email, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $paymentID, $payerName, $payerEmail, $amount);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo json_encode(["status" => "success", "message" => "Payment recorded successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid payment data"]);
}
?>

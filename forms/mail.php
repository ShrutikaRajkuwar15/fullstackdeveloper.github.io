<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

function sendWhatsAppMessage($name, $visitor_email, $mobile) {
    $token = "9kzxf8wk62jy8y4b"; 
    $instance_id = "instance90444"; 
    $to = "+918605110589"; 
    $body = "New registration Full stack devloper course in pune :\nName: $name\nEmail: $visitor_email\nPhone: $mobile";

    echo "\nBody : " . $body;

    $params = array(
        'token' => $token,
        'to' => $to,
        'body' => $body
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.ultramsg.com/$instance_id/messages/chat",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query($params),
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    echo "\nResponse : " . $response;

    curl_close($curl);

    if ($err) {
        throw new Exception('cURL Error: ' . $err);
    } else {
        return $response;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $name = $_POST['cname'];
        $visitor_email = $_POST['cemail'];
        $mobile = $_POST['cnumber'];

        $conn = new PDO("mysql:host=localhost;dbname=maestroi_datacouncilenq", "maestroi_datacouncil_enq", "datacouncil@2024");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO datacouncil (name, email, phone, status) VALUES (:name, :email, :phone, '1')");
       
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $visitor_email);
        $stmt->bindParam(':phone', $mobile);

        $stmt->execute();

        echo "Database insertion successful";

        $whatsapp_response = sendWhatsAppMessage($name, $visitor_email, $mobile);
        
        echo "\nWhatsApp message sent successfully";
    } catch(PDOException $e) {
        echo "\nDatabase connection failed: " . $e->getMessage();
    } catch(Exception $e) {
        echo "\nError:" . $e->getMessage();
    } finally {
        if (isset($conn)) {
            $conn = null;
        }
    }
}
?>
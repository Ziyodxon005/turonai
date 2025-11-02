<?php
// --------------------- PHP qismi ---------------------
// Hugging Face API tokenini environment variable orqali oling
$api_token = getenv("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");

// Bazaviy model nomi
$model = "mistralai/Mistral-7B-Instruct-v0.3";

// Foydalanuvchi xabari (formadan keladi)
$user_message = $_POST['message'] ?? '';

$response_text = '';
if($user_message) {
    $url = "https://api-inference.huggingface.co/models/$model";
    $data = json_encode(["inputs" => $user_message]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $api_token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $result = curl_exec($ch);
    curl_close($ch);

    $decoded = json_decode($result, true);
    // Model javobi
    if(isset($decoded['error'])){
        $response_text = "⚠️ Model javob bera olmadi: " . $decoded['error'];
    } elseif(is_array($decoded) && isset($decoded[0]['generated_text'])){
        $response_text = $decoded[0]['generated_text'];
    } else {
        $response_text = "⚠️ Modeldan javob topilmadi.";
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Turon AI ChatBot</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; justify-content: center; padding-top: 50px;}
        .chat-container { width: 500px; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);}
        h2 { text-align: center; color: #333; }
        .response { margin-top: 20px; padding: 10px; background: #e1f5fe; border-radius: 5px; min-height: 50px;}
        input[type="text"] { width: calc(100% - 22px); padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
        button { margin-top: 10px; padding: 10px 20px; background: #0288d1; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0277bd; }
        .footer { margin-top: 20px; font-size: 12px; text-align: center; color: #777; }
    </style>
</head>
<body>
    <div class="chat-container">
        <h2>Turon AI ChatBot</h2>
        <form method="POST">
            <input type="text" name="message" placeholder="Xabaringizni yozing..." required>
            <button type="submit">Yuborish</button>
        </form>
        <div class="response">
            <?php echo htmlspecialchars($response_text); ?>
        </div>
        <div class="footer">
            Turon o'quv markazi – Sizning bilim maskaningiz
        </div>
    </div>
</body>
</html>



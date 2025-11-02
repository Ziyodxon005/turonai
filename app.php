<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");

    $input = json_decode(file_get_contents("php://input"), true);
    $message = $input["message"] ?? "";

    if (!$message) {
        echo json_encode(["reply" => "⚠️ Iltimos, matn kiriting."]);
        exit;
    }

    $api_token = "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";
    $model = "Ziyodxon/TuronAi";
    $url = "https://api-inference.huggingface.co/models/$model";

    $data = json_encode(["inputs" => $message]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $api_token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    curl_close($ch);

    $decoded = json_decode($response, true);
    $reply = $decoded[0]["generated_text"] ?? "⚠️ Modeldan javob olinmadi.";

    echo json_encode(["reply" => $reply]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Turon AI ChatBot</title>
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #020024, #090979, #00d4ff);
  color: white;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  margin: 0;
}
.chat-box {
  width: 400px;
  height: 500px;
  background: rgba(255,255,255,0.1);
  border-radius: 15px;
  overflow-y: auto;
  padding: 20px;
  backdrop-filter: blur(10px);
  box-shadow: 0 0 20px rgba(255,255,255,0.2);
}
.message {
  margin: 10px 0;
  padding: 10px;
  border-radius: 10px;
}
.user { background: rgba(0,255,200,0.2); text-align: right; }
.bot { background: rgba(255,255,255,0.2); text-align: left; }
input {
  width: 300px;
  padding: 10px;
  border-radius: 10px;
  border: none;
}
button {
  padding: 10px 20px;
  border: none;
  border-radius: 10px;
  background: #00d4ff;
  color: #000;
  font-weight: bold;
  cursor: pointer;
}
footer {
  margin-top: 20px;
  font-size: 14px;
  color: #ccc;
}
</style>
</head>
<body>
<h2>🤖 Turon AI Chatbot</h2>
<div class="chat-box" id="chat-box"></div>
<div>
  <input type="text" id="user-input" placeholder="Xabar yozing...">
  <button onclick="sendMessage()">Yuborish</button>
</div>
<footer>© 2025 Turon O‘quv Markazi | Sun’iy Intellekt yordamida ishlab chiqilgan</footer>

<script>
async function sendMessage() {
  const input = document.getElementById("user-input");
  const chatBox = document.getElementById("chat-box");
  const userMsg = input.value.trim();
  if (!userMsg) return;

  chatBox.innerHTML += `<div class='message user'>${userMsg}</div>`;
  input.value = "";

  const response = await fetch("", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ message: userMsg })
  });

  const data = await response.json();
  chatBox.innerHTML += `<div class='message bot'>${data.reply}</div>`;
  chatBox.scrollTop = chatBox.scrollHeight;
}
</script>
</body>
</html>

<?php
function analyzeSentiment($text) {
    $apiKey = $_ENV['HUGGINGFACE_API_TOKEN'];  // 請填入你的 Hugging Face API Key
    $url = "https://api-inference.huggingface.co/models/cardiffnlp/twitter-roberta-base-sentiment";

    $headers = [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ];
    $data = json_encode(["inputs" => $text]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    curl_close($ch);

	$json = json_decode($result, true);

    // 對應情緒標籤
    $labelMap = [
        'LABEL_0' => '負面',
        'LABEL_1' => '中立',
        'LABEL_2' => '正面'
    ];

    if (isset($json[0])) {
        // 找出機率最高的情緒結果
        $topLabel = $json[0][0]['label'];
        return $labelMap[$topLabel] ?? $topLabel;
    }

    return "分析失敗";
}

$result = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputText = $_POST['text'] ?? '';
    $result = analyzeSentiment($inputText);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>情緒分析工具</title>
</head>
<body>
    <h1>文字情緒分析</h1>
    <form method="POST">
        <textarea name="text" rows="4" cols="50" placeholder="請輸入文字..."><?php echo htmlspecialchars($_POST['text'] ?? ''); ?></textarea><br>
        <button type="submit">分析情緒</button>
    </form>
    <p>分析結果：<strong><?php echo htmlspecialchars($result); ?></strong></p>
</body>
</html>

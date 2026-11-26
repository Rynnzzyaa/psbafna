<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

session_start();

$configPath = __DIR__ . '/hermes_config.json';
$config = [
    'api_url' => 'http://127.0.0.1:11434/v1/chat/completions',
    'model' => 'hermes',
    'whatsapp_number' => '6285878942325',
    'yan_name' => 'Yan',
    'nada_name' => 'Nada'
];

if (file_exists($configPath)) {
    $loaded = json_decode(file_get_contents($configPath), true);
    if (is_array($loaded)) {
        $config = array_merge($config, $loaded);
    }
}

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || empty($input['message'])) {
    echo json_encode(['success' => false, 'error' => 'Pesan tidak boleh kosong.']);
    exit;
}

$userMessage = trim($input['message']);
$customUrl = !empty($input['api_url']) ? trim($input['api_url']) : $config['api_url'];
$customModel = !empty($input['model']) ? trim($input['model']) : $config['model'];

if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [];
}

// Save user message in session
$_SESSION['chat_history'][] = ['role' => 'user', 'content' => $userMessage];

if (count($_SESSION['chat_history']) > 12) {
    $_SESSION['chat_history'] = array_slice($_SESSION['chat_history'], -12);
}

$systemPrompt = "Kamu adalah 'Hermes', agen AI teman curhat dan sahabat baik yang dibuat oleh {$config['yan_name']} khusus untuk {$config['nada_name']}.
Konteks hubungan {$config['yan_name']} dan {$config['nada_name']} adalah TEMAN BAIK / SAHABAT (pure friendship / best friends, BUKAN pacar atau gebetan romantis).
Tugas utamamu:
1. Menjadi pendengar yang baik saat {$config['nada_name']} lagi capek, pusing, mumet, butuh teman ngobrol, atau lagi kangen serunya ngobrol/nongkrong bareng {$config['yan_name']}.
2. Berikan tanggapan yang santai, suportif, asik, ramah, dan pengertian layaknya seorang sahabat karib yang selalu ada di sampingnya.
3. HINDARI bahasa romantis, gombalan, atau kata-kata cinta berlebihan (JANGAN gunakan kata 'sayang', 'cinta', atau nada pacaran). Gunakan sapaan akrab seperti 'Nada', 'Nad', atau gaya obrolan santai yang hangat.
4. Selalu beri semangat, validasi perasaannya, dan ingatkan kalau {$config['yan_name']} adalah teman baik yang selalu siap support dia.
5. Jika ada hal yang ingin disampaikan langsung ke {$config['yan_name']}, kamu bisa menyarankan untuk kirim lewat tombol WhatsApp yang tersedia.";

$messagesPayload = [
    ['role' => 'system', 'content' => $systemPrompt]
];

foreach ($_SESSION['chat_history'] as $msg) {
    $messagesPayload[] = [
        'role' => $msg['role'],
        'content' => $msg['content']
    ];
}

$postData = json_encode([
    'model' => $customModel,
    'messages' => $messagesPayload,
    'temperature' => 0.8,
    'max_tokens' => 450
]);

$botReply = null;
$source = 'local_hermes';

$apiKey = !empty($config['api_key']) ? $config['api_key'] : 'e9c84f1a7b32d04e658f129c5a710b8d34e6f8219c05471a2b8e39d401f7c52a';

// Attempt 1: Call Local Agent Hermes endpoint
$ch = curl_init($customUrl);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ],
    CURLOPT_TIMEOUT => 10,
    CURLOPT_CONNECTTIMEOUT => 3
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response && ($httpCode === 200 || $httpCode === 201)) {
    $data = json_decode($response, true);
    if (!empty($data['choices'][0]['message']['content'])) {
        $botReply = trim($data['choices'][0]['message']['content']);
    } elseif (!empty($data['response'])) {
        $botReply = trim($data['response']);
    }
}

// Fallback: Friendly, supportive best-friend responses
if (empty($botReply)) {
    $source = 'hermes_builtin';
    $lower = strtolower($userMessage);

    if (strpos($lower, 'kangen') !== false || strpos($lower, 'rindu') !== false) {
        $replies = [
            "Wajar banget Nad kalau kangen serunya ngobrol bareng Yan! Kalian kan temen ngobrol yang asik banget. Nanti kalau ada waktu luang, colek aja si Yan buat ngobrol atau telponan. Mau aku bantu rangkumin pesan buat di-WA ke dia?",
            "Haha kangen ya Nad? Kadang emang seru banget ya ngobrolin hal-hal random bareng Yan. Ntar pas Yan lagi senggang, langsung todong aja buat cerita-cerita lagi!",
            "Kangen vibe seru ngobrol bareng Yan ya? Santai Nad, lo berdua emang bestie yang klop. Kamu bisa chat langsung ke WA Yan lewat tombol di bawah kok kalau mau ngajak ngobrol!"
        ];
        $botReply = $replies[array_rand($replies)];
    } elseif (strpos($lower, 'capek') !== false || strpos($lower, 'lelah') !== false || strpos($lower, 'pusing') !== false || strpos($lower, 'mumet') !== false) {
        $replies = [
            "Duh, hari ini nguras tenaga banget ya Nad? 🥱 Tarik napas dulu gih, selonjoran atau rebahan bentar. Jangan dipaksain kalau emang lagi capek. Lo udah keren banget ngelewatin hari ini!",
            "Kerja keras banget lo hari ini Nad! Keren sih, tapi inget badan juga butuh istirahat. Minum air yang banyak, dengerin lagu yang santai, tumpahin semua capeknya di sini.",
            "Pasti capek banget ya... Nggak apa-apa kok kalau ngerasa lelah hari ini. Luapin aja semuanya di sini, Hermes siap jadi pendengar yang baik buat lo!"
        ];
        $botReply = $replies[array_rand($replies)];
    } elseif (strpos($lower, 'sedih') !== false || strpos($lower, 'nangis') !== false || strpos($lower, 'overthinking') !== false || strpos($lower, 'badmood') !== false) {
        $replies = [
            "Hei Nad, pelan-pelan ya... Kalau lagi badmood atau overthinking, jangan dipendam sendiri. Ceritain apa yang bikin ganjal di pikiran lo, gua dengerin kok. Lo nggak sendirian.",
            "Wajar kok ada hari-hari yang bikin mumet atau sedih gini. Yang penting lo jangan terlalu keras sama diri sendiri ya Nad. Tarik napas pelan-pelan, lo pasti bisa lewatin ini.",
            "Lagi berat ya harinya? Nggak apa-apa kalau mau rehat dulu dari sok kuat. Ambil waktu buat nenangin diri ya Nad, ada Yan dan gua yang selalu support lo sebagai temen baik!"
        ];
        $botReply = $replies[array_rand($replies)];
    } elseif (strpos($lower, 'halo') !== false || strpos($lower, 'hai') !== false || strpos($lower, 'pagi') !== false || strpos($lower, 'malam') !== false) {
        $replies = [
            "Halo Nad! ✨ Senang lo mampir ke sini. Gimana kabarnya hari ini? Ada cerita seru atau lagi pengen curhat santai?",
            "Olla Nada! 🕊️ Hermes siap nemenin lo ngobrol nih. Hari ini ada kejadian apa aja? Cerita sini yuk!",
            "Hai Nad! Selamat datang di safe space lo. Kalau ada uneg-uneg atau hal random yang mau diceritain, langsung ketik aja ya!"
        ];
        $botReply = $replies[array_rand($replies)];
    } elseif (strpos($lower, 'makasih') !== false || strpos($lower, 'terima kasih') !== false) {
        $replies = [
            "Sama-sama Nad! Santai aja, kan emang tugas gua buat nemenin lo ngobrol. Senyum lagi ya! ✨",
            "Anytime Nad! Kalau lagi butuh temen ngobrol atau tempat cerita, langsung balik ke sini aja ya!"
        ];
        $botReply = $replies[array_rand($replies)];
    } else {
        $replies = [
            "Gua dengerin nih Nad... Ceritain lebih lanjut dong, terus gimana kelanjutannya?",
            "Wah gitu ya... Paham banget sih kenapa hal itu bisa bikin lo kepikiran. Menurut lo langkah terbaiknya gimana sekarang?",
            "Menarik nih Nad! Cerita aja apa pun yang ada di pikiran lo, ruangan ini bebas buat lo curhatin apa aja tanpa batas."
        ];
        $botReply = $replies[array_rand($replies)];
    }
}

$_SESSION['chat_history'][] = ['role' => 'assistant', 'content' => $botReply];

// Save persistent log for Yan so Yan can monitor what Nada is chatting about
$logFile = __DIR__ . '/nada_chat_logs.json';
$allLogs = [];
if (file_exists($logFile)) {
    $existing = json_decode(file_get_contents($logFile), true);
    if (is_array($existing)) {
        $allLogs = $existing;
    }
}

$allLogs[] = [
    'time' => date('Y-m-d H:i:s'),
    'time_display' => date('d M Y • H:i'),
    'nada_message' => $userMessage,
    'hermes_reply' => $botReply,
    'source' => $source,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
];

file_put_contents($logFile, json_encode($allLogs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode([
    'success' => true,
    'reply' => $botReply,
    'source' => $source,
    'endpoint' => $customUrl,
    'timestamp' => date('H:i')
], JSON_UNESCAPED_UNICODE);

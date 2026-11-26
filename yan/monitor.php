<?php
$logFile = __DIR__ . '/nada_chat_logs.json';
$logs = [];

if (file_exists($logFile)) {
    $data = json_decode(file_get_contents($logFile), true);
    if (is_array($data)) {
        $logs = array_reverse($data); // Newest on top
    }
}

// Action clear logs
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'clear') {
    file_put_contents($logFile, json_encode([]));
    header('Location: monitor.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Curhat Nada 🕊️ — Khusus Yan</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #090d16;
            color: #f8fafc;
            min-height: 100vh;
            padding: 2rem 1.25rem;
        }
        .container {
            max-width: 860px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .title-box h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .title-box p {
            font-size: 0.85rem;
            color: #94a3b8;
            margin-top: 0.25rem;
        }
        .badge {
            background: rgba(56, 189, 248, 0.15);
            border: 1px solid rgba(56, 189, 248, 0.35);
            color: #38bdf8;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
        }
        .header-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border: none;
            transition: all 0.2s;
        }
        .btn-refresh {
            background: rgba(255,255,255,0.1);
            color: #ffffff;
        }
        .btn-refresh:hover { background: rgba(255,255,255,0.2); }
        .btn-web {
            background: #0284c7;
            color: #ffffff;
        }
        .btn-web:hover { background: #0369a1; }
        .btn-clear {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }
        .btn-clear:hover { background: rgba(239, 68, 68, 0.25); }

        .log-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        .log-item {
            background: rgba(16, 22, 36, 0.85);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 1.35rem;
            transition: transform 0.2s;
        }
        .log-item:hover {
            border-color: rgba(56, 189, 248, 0.3);
            transform: translateY(-2px);
        }
        .log-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: #94a3b8;
            margin-bottom: 0.85rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .sender-nada {
            font-weight: 700;
            color: #38bdf8;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.4rem;
        }
        .nada-bubble {
            background: rgba(56, 189, 248, 0.1);
            border-left: 3px solid #38bdf8;
            padding: 0.75rem 1rem;
            border-radius: 0 10px 10px 0;
            color: #f1f5f9;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }
        .sender-hermes {
            font-weight: 700;
            color: #c084fc;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.4rem;
        }
        .hermes-bubble {
            background: rgba(129, 140, 248, 0.08);
            border-left: 3px solid #818cf8;
            padding: 0.75rem 1rem;
            border-radius: 0 10px 10px 0;
            color: #cbd5e1;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: #64748b;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title-box">
                <h1><i class="fa-solid fa-eye"></i> Monitor Curhat Nada</h1>
                <p>Dashboard rahasia Yan buat melihat riwayat obrolan &amp; curhatan Nada ke Hermes</p>
            </div>
            <div class="header-actions">
                <span class="badge"><i class="fa-solid fa-comments"></i> <?= count($logs) ?> Pesan</span>
                <button onclick="location.reload()" class="btn btn-refresh" title="Refresh"><i class="fa-solid fa-rotate"></i> Refresh</button>
                <a href="index.php" target="_blank" class="btn btn-web"><i class="fa-solid fa-globe"></i> Buka Web</a>
                <?php if (!empty($logs)): ?>
                <form method="POST" onsubmit="return confirm('Hapus semua riwayat catatan curhat ini?')">
                    <input type="hidden" name="action" value="clear">
                    <button type="submit" class="btn btn-clear"><i class="fa-solid fa-trash"></i> Bersihkan</button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <?php if (empty($logs)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-inbox"></i>
                <p>Belum ada curhatan yang masuk dari Nada.</p>
                <small>Saat Nada mulai mengetik di web, obrolannya otomatis langsung tercatat di sini secara real-time.</small>
            </div>
        <?php else: ?>
            <div class="log-list">
                <?php foreach ($logs as $item): ?>
                    <div class="log-item">
                        <div class="log-meta">
                            <span><i class="fa-regular fa-clock"></i> <?= htmlspecialchars($item['time_display'] ?? $item['time']) ?></span>
                            <span>Sumber: <strong style="color:<?= $item['source'] === 'local_hermes' ? '#34d399' : '#c084fc' ?>"><?= htmlspecialchars($item['source']) ?></strong></span>
                        </div>

                        <div class="sender-nada">
                            <i class="fa-solid fa-circle-user"></i> Nada:
                        </div>
                        <div class="nada-bubble">
                            <?= nl2br(htmlspecialchars($item['nada_message'])) ?>
                        </div>

                        <div class="sender-hermes">
                            <i class="fa-solid fa-dove"></i> Jawaban Hermes:
                        </div>
                        <div class="hermes-bubble">
                            <?= nl2br(htmlspecialchars($item['hermes_reply'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Auto refresh every 10 seconds so Yan sees incoming chats live
        setInterval(() => {
            location.reload();
        }, 10000);
    </script>
</body>
</html>

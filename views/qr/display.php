<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Absensi - <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
            overflow: hidden;
        }
        .qr-display {
            text-align: center;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .app-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #00d9ff, #7b2cbf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .subtitle {
            font-size: 1.2rem;
            color: #a0a0a0;
            margin-bottom: 2rem;
        }
        .qr-container {
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 25px 50px rgba(0, 217, 255, 0.2); }
            50% { box-shadow: 0 25px 50px rgba(123, 44, 191, 0.3); }
        }
        .qr-container img {
            width: 300px;
            height: 300px;
            display: block;
        }
        .info {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem 3rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        .info p {
            margin: 0.5rem 0;
            font-size: 1.1rem;
        }
        .info strong {
            color: #00d9ff;
        }
        .time-display {
            font-size: 3rem;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
            background: linear-gradient(135deg, #00d9ff, #7b2cbf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }
        .instructions {
            font-size: 1rem;
            color: #888;
            max-width: 400px;
            margin: 0 auto;
        }
        .close-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.3s;
        }
        .close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <button class="close-btn" onclick="window.close()">✕ Tutup</button>
    
    <div class="qr-display">
        <h1 class="app-title"><?= APP_NAME ?></h1>
        <p class="subtitle">Scan untuk Absensi</p>
        
        <div id="currentTime" class="time-display"><?= date('H:i:s') ?></div>
        
        <div class="qr-container">
            <?php 
            $realQrPath = QRCODE_PATH . $qrPath;
            $qrSrc = asset('uploads/qrcodes/' . $qrPath);
            
            if (file_exists($realQrPath)) {
                $qrData = base64_encode(file_get_contents($realQrPath));
                $qrSrc = 'data:image/png;base64,' . $qrData;
            }
            ?>
            <img src="<?= $qrSrc ?>" alt="QR Code Absensi">
        </div>
        
        <div class="info">
            <p><strong>Tanggal:</strong> <?= formatDate(date('Y-m-d')) ?></p>
            <p><strong>Arahkan kamera HP ke QR Code ini</strong></p>
        </div>
        
        <p class="instructions">
            Buka aplikasi absensi di HP Anda, pilih menu "Scan QR", 
            dan arahkan kamera ke QR Code di atas untuk melakukan absensi.
        </p>
    </div>

    <script>
        // Update time every second
        function updateTime() {
            const now = new Date();
            const time = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
            document.getElementById('currentTime').textContent = time;
        }
        setInterval(updateTime, 1000);
        
        // Auto-refresh page every 5 minutes to check if session is still active
        setTimeout(() => {
            location.reload();
        }, 5 * 60 * 1000);
    </script>
</body>
</html>

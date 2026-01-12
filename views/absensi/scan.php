<div class="scan-page">
    <div class="page-header">
        <h1><i class="fas fa-qrcode"></i> Scan QR Absensi</h1>
        <p>Arahkan kamera ke QR Code untuk melakukan absensi</p>
    </div>

    <div class="scan-container">
        <!-- Tab Navigation -->
        <div class="scan-tabs">
            <button class="tab-btn active" onclick="showTab('camera')">
                <i class="fas fa-camera"></i> Kamera
            </button>
            <button class="tab-btn" onclick="showTab('manual')">
                <i class="fas fa-keyboard"></i> Manual Input
            </button>
        </div>

        <!-- Camera Scanner Tab -->
        <div id="cameraTab" class="tab-content active">
            <div class="scanner-wrapper">
                <div id="scanner" class="scanner-box">
                    <div class="scanner-placeholder" id="scannerPlaceholder">
                        <i class="fas fa-camera"></i>
                        <p>Tekan tombol untuk memulai scan</p>
                    </div>
                    <div id="reader" style="width: 100%;"></div>
                </div>

                <div class="scanner-controls">
                    <button id="startScan" class="btn btn-primary btn-lg">
                        <i class="fas fa-camera"></i>
                        Mulai Scan
                    </button>
                    <button id="stopScan" class="btn btn-secondary btn-lg" style="display: none;">
                        <i class="fas fa-stop"></i>
                        Stop
                    </button>
                </div>

                <div class="camera-error" id="cameraError" style="display: none;">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Tidak dapat mengakses kamera</strong>
                            <p>Kemungkinan penyebab:</p>
                            <ul>
                                <li>Izin kamera tidak diberikan</li>
                                <li>Browser memblokir akses kamera (butuh HTTPS)</li>
                                <li>Kamera sedang digunakan aplikasi lain</li>
                            </ul>
                            <p><strong>Solusi:</strong> Gunakan tab "Manual Input" atau akses via HTTPS</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manual Input Tab -->
        <div id="manualTab" class="tab-content" style="display: none;">
            <div class="manual-input-wrapper">
                <div class="card">
                    <div class="card-body">
                        <h3><i class="fas fa-keyboard"></i> Input Kode QR Manual</h3>
                        <p>Minta admin untuk memberikan kode sesi absensi, lalu masukkan di bawah:</p>
                        
                        <form id="manualForm" class="manual-form">
                            <div class="form-group">
                                <label for="sessionCode">Kode Sesi Absensi</label>
                                <input type="text" id="sessionCode" class="form-control" 
                                       placeholder="Contoh: A1B2C3D4E5F6G7H8_20260112"
                                       required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-check"></i> Submit Absensi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Result Messages -->
        <div class="scan-result" id="scanResult" style="display: none;">
            <div class="result-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>Absensi Berhasil!</h3>
            <p id="resultMessage"></p>
            <button onclick="location.reload()" class="btn btn-primary">
                <i class="fas fa-redo"></i> Scan Lagi
            </button>
        </div>

        <div class="scan-error" id="scanError" style="display: none;">
            <div class="error-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <h3>Gagal!</h3>
            <p id="errorMessage"></p>
            <button onclick="location.reload()" class="btn btn-primary">
                <i class="fas fa-redo"></i> Coba Lagi
            </button>
        </div>
    </div>

    <div class="scan-info">
        <div class="info-card">
            <i class="fas fa-info-circle"></i>
            <div>
                <h4>Cara Melakukan Absensi</h4>
                <ol>
                    <li><strong>Via Kamera:</strong> Tekan "Mulai Scan" dan arahkan ke QR Code</li>
                    <li><strong>Via Manual:</strong> Minta kode dari admin dan input manual</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<style>
.scan-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    justify-content: center;
}
.tab-btn {
    padding: 12px 24px;
    border: 2px solid var(--gray-300);
    background: var(--white);
    border-radius: var(--radius);
    cursor: pointer;
    font-weight: 500;
    transition: var(--transition);
}
.tab-btn.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
.tab-btn:hover:not(.active) {
    border-color: var(--primary);
    color: var(--primary);
}
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
.manual-input-wrapper {
    max-width: 500px;
    margin: 0 auto;
}
.manual-form {
    margin-top: 20px;
}
.camera-error {
    margin-top: 20px;
}
.camera-error ul {
    margin-left: 20px;
    font-size: 0.9rem;
}
#reader {
    border-radius: var(--radius);
    overflow: hidden;
}
</style>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
// Tab switching
function showTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    if (tab === 'camera') {
        document.querySelector('.tab-btn:first-child').classList.add('active');
        document.getElementById('cameraTab').classList.add('active');
        document.getElementById('cameraTab').style.display = 'block';
        document.getElementById('manualTab').style.display = 'none';
    } else {
        document.querySelector('.tab-btn:last-child').classList.add('active');
        document.getElementById('manualTab').classList.add('active');
        document.getElementById('manualTab').style.display = 'block';
        document.getElementById('cameraTab').style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const startBtn = document.getElementById('startScan');
    const stopBtn = document.getElementById('stopScan');
    const scanResult = document.getElementById('scanResult');
    const scanError = document.getElementById('scanError');
    const cameraError = document.getElementById('cameraError');
    const scannerPlaceholder = document.getElementById('scannerPlaceholder');
    
    let html5QrCode = null;
    let isScanning = false;

    startBtn.addEventListener('click', function() {
        if (isScanning) return;
        
        scannerPlaceholder.style.display = 'none';
        cameraError.style.display = 'none';
        
        html5QrCode = new Html5Qrcode("reader");
        
        html5QrCode.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess,
            onScanFailure
        ).then(() => {
            isScanning = true;
            startBtn.style.display = 'none';
            stopBtn.style.display = 'inline-flex';
        }).catch(err => {
            console.error('Camera error:', err);
            scannerPlaceholder.style.display = 'flex';
            cameraError.style.display = 'block';
        });
    });

    stopBtn.addEventListener('click', function() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                startBtn.style.display = 'inline-flex';
                stopBtn.style.display = 'none';
                scannerPlaceholder.style.display = 'flex';
            });
        }
    });

    function onScanSuccess(decodedText, decodedResult) {
        if (html5QrCode) {
            html5QrCode.stop();
        }
        processAbsensi(decodedText);
    }

    function onScanFailure(error) {
        // Silent fail - continuous scanning
    }

    // Manual form submission
    document.getElementById('manualForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const sessionCode = document.getElementById('sessionCode').value.trim();
        
        if (!sessionCode) {
            alert('Masukkan kode sesi absensi');
            return;
        }
        
        // Create QR data format
        const qrData = JSON.stringify({
            session_code: sessionCode,
            tanggal: new Date().toISOString().split('T')[0],
            type: 'attendance'
        });
        
        processAbsensi(qrData);
    });

    function processAbsensi(qrData) {
        // Hide all tabs and show loading
        document.getElementById('cameraTab').style.display = 'none';
        document.getElementById('manualTab').style.display = 'none';
        document.querySelector('.scan-tabs').style.display = 'none';
        
        fetch('<?= url('/absensi/process-scan') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'qr_data=' + encodeURIComponent(qrData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            showError('Terjadi kesalahan saat memproses absensi');
        });
    }

    function showSuccess(message) {
        scanResult.style.display = 'block';
        document.getElementById('resultMessage').textContent = message;
    }

    function showError(message) {
        scanError.style.display = 'block';
        document.getElementById('errorMessage').textContent = message;
    }
});
</script>

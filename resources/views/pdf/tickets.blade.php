<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Tiket TIXEVENT</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #333333;
        }
        .page-break {
            page-break-after: always;
        }
        .ticket-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 20px;
        }
        .header {
            background-color: #e11d48; /* Rose 600 */
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .qr-section {
            text-align: center;
            padding: 30px 20px;
            background-color: #ffffff;
        }
        .qr-code {
            margin: 0 auto;
            border: 1px solid #f1f5f9;
            padding: 10px;
            border-radius: 8px;
        }
        .ticket-code {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 4px;
            margin-top: 15px;
            color: #0f172a;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            margin-top: 10px;
        }
        .status-valid {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-used {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .details-section {
            background-color: #f8fafc;
            padding: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .detail-row {
            margin-bottom: 12px;
            clear: both;
            overflow: hidden;
        }
        .detail-label {
            float: left;
            width: 30%;
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }
        .detail-value {
            float: left;
            width: 70%;
            font-size: 14px;
            color: #0f172a;
            font-weight: bold;
            text-align: right;
        }
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    @foreach($tikets as $index => $tiket)
    <div class="ticket-container">
        <div class="header">
            <h1>{{ $tiket->status_checkin === 'sudah' ? 'TIKET TELAH DIPAKAI' : 'E-TIKET SIAP SCAN' }}</h1>
            <p>Tunjukkan tiket ini kepada petugas di pintu masuk</p>
        </div>

        <div class="qr-section">
            <img src="{{ $tiket->qr_code_base64 }}" width="200" height="200" class="qr-code">
            <div class="ticket-code">{{ explode('.', $tiket->kode_tiket)[0] }}</div>
            
            @if($tiket->status_checkin === 'sudah')
                <div class="status-badge status-used">Sudah Check-in</div>
            @else
                <div class="status-badge status-valid">Belum Check-in</div>
            @endif
        </div>

        <div class="details-section">
            <div class="detail-row">
                <div class="detail-label">Nama Event</div>
                <div class="detail-value">{{ $tiket->nama_event }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Kategori Tiket</div>
                <div class="detail-value">{{ $tiket->jenis_tiket }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Tanggal</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($tiket->tanggal)->format('d M Y') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Waktu</div>
                <div class="detail-value">{{ $tiket->waktu ? \Carbon\Carbon::parse($tiket->waktu)->format('H:i') : '-' }} WIB</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Lokasi</div>
                <div class="detail-value">{{ $tiket->nama_venue }}</div>
            </div>
        </div>

        <div class="footer">
            Dicetak pada {{ date('d M Y H:i') }} | Sistem E-Ticketing TIXEVENT
        </div>
    </div>

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
    @endforeach

</body>
</html>

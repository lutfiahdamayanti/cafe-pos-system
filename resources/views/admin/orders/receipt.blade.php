<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <style>
        body{font-family:DejaVu Sans,sans-serif;font-size:12px;color:#333;margin:20px}
        .header{text-align:center}
        .header h2{margin:0;font-size:24px}
        .header p{margin:2px 0;font-size:11px;color:#666}
        .line{border-top:1px dashed #555;margin:10px 0}
        table{width:100%;border-collapse:collapse}
        td{padding:4px 0;vertical-align:top}
        .text-right{text-align:right}
        .small{font-size:11px;color:#666}
        .total{font-size:15px;font-weight:bold}
        .footer{text-align:center;margin-top:20px;font-size:11px;color:#666}
    </style>
</head>
<body>
    <div class="header">
        <h2>☕ CAFE</h2>
        <p>Coffee • Food • Dessert</p>
        <p>Jl. Malioboro, Yogyakarta</p>
        <p>Telp. 081234567890</p>
    </div>

    <div class="line"></div>
    <table>
        <tr><td>No. Order</td><td class="text-right">{{ $order->order_number }}</td></tr>
        <tr><td>Tanggal</td><td class="text-right">{{ $order->created_at->format('d M Y H:i') }}</td></tr>
        <tr><td>Pelanggan</td><td class="text-right">{{ $order->customer_name }}</td></tr>
        <tr><td>No HP</td><td class="text-right">{{ $order->phone }}</td></tr>
        <tr><td>Nomor Meja</td><td class="text-right">{{ $order->table_number }}</td></tr>
        <tr><td>Pembayaran</td><td class="text-right">{{ $order->payment }}</td></tr>
        <tr><td>Status</td><td class="text-right">{{ statusIndonesia($order->status) }}</td></tr>
    </table>

    <div class="line"></div>
    <table>
        @foreach($order->details as $detail)
        <tr>
            <td>
                <strong>{{ $detail->menu->name }}</strong>
                @if($detail->options)
                    @foreach($detail->options as $key => $value)
                        <br><span class="small">• {{ $key }} : {{ $value }}</span>
                    @endforeach
                @endif
                <br><span class="small">{{ $detail->qty }} x Rp {{ number_format($detail->price,0,',','.') }}</span>
            </td>
            <td class="text-right">Rp {{ number_format($detail->total,0,',','.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>
    <table>
        <tr><td>Subtotal</td><td class="text-right">Rp {{ number_format($order->subtotal,0,',','.') }}</td></tr>
        <tr><td>PPN (11%)</td><td class="text-right">Rp {{ number_format($order->tax,0,',','.') }}</td></tr>
        <tr><td>Biaya Layanan</td><td class="text-right">Rp {{ number_format($order->service,0,',','.') }}</td></tr>
        <tr>
            <td class="total">TOTAL</td>
            <td class="text-right total">Rp {{ number_format($order->total,0,',','.') }}</td>
        </tr>
    </table>

    <div class="line"></div>
    <div class="footer">
        <strong>Terima Kasih ☕</strong><br><br>
        Semoga harimu menyenangkan.<br>
        Sampai jumpa kembali di Cafe ❤️
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body {font-family: DejaVu Sans, sans-serif;font-size: 11px;color: #222;margin: 0;padding: 10px;}
        .header { text-align: center; }
        .header h2 {margin: 0 0 5px;font-size: 20px;font-weight: bold;}
        .header p { margin: 2px 0; font-size: 10px; }
        .line { border-top: 1px dashed #333; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 3px 0; vertical-align: top; font-size: 10px; }
        td:first-child { padding-right: 8px; }
        .text-right { text-align: right; }
        .small { font-size: 9px; color: #444; }
        .total { font-size: 13px; font-weight: bold; }
        .footer { text-align: center; margin-top: 12px; font-size: 9px; }
        .footer strong { font-size: 11px; }
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
        <tr><td>Diskon</td><td class="text-right">Rp {{ number_format($order->discount ?? 0,0,',','.') }}</td></tr>
        <tr><td>PPN (11%)</td><td class="text-right">Rp {{ number_format($order->tax,0,',','.') }}</td></tr>
        <tr><td>Biaya Layanan</td><td class="text-right">Rp {{ number_format($order->service,0,',','.') }}</td></tr>
        <tr><td class="total">TOTAL</td><td class="text-right total">Rp {{ number_format($order->total,0,',','.') }}</td></tr>
    </table>

    <div class="line"></div>
    <div class="footer">
        <strong>Terima Kasih ☕</strong><br><br>
        Semoga harimu menyenangkan.<br>
        Sampai jumpa kembali di Cafe ❤️
    </div>
</body>
</html>
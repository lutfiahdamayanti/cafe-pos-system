<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kitchen Ticket</title>
    <style>
        @page{size:80mm 150mm;margin:0}
        html,body{width:80mm;margin:0;padding:0}
        body{box-sizing:border-box;padding:4mm 4mm 5mm;font-family:DejaVu Sans,sans-serif;font-size:11px;line-height:1.35}
        h2{text-align:center;font-size:15px;margin:0 0 5px}
        .center{text-align:center}
        .line{border-top:1px dashed #000;margin:7px 0}
        .item{margin-bottom:10px}
        .qty{font-size:14px;font-weight:bold}
        .option{margin-left:10px;font-size:10px}
        .note{margin-left:10px;margin-top:3px;font-size:10px}
    </style>
</head>
<body>
    <h2>KITCHEN TICKET</h2>
    <div class="center">
        <strong>{{ $order->order_number }}</strong><br>
        {{ $order->created_at->format('d/m/Y H:i') }}
    </div>
    <div class="line"></div>
    <strong>Pelanggan :</strong> {{ $order->customer_name }}<br>
    <strong>Meja :</strong> {{ $order->table_number }}
    <div class="line"></div>
    @foreach($order->details as $detail)
        <div class="item">
            <div class="qty">{{ $detail->qty }}x {{ $detail->menu->name }}</div>
            @if($detail->size)
                <div class="option">• Ukuran : {{ $detail->size }}</div>
            @endif
            @if($detail->options)
                @php
                    $options=is_array($detail->options)?$detail->options:json_decode($detail->options,true);
                @endphp
                @if($options)
                    @foreach($options as $key=>$value)
                        <div class="option">• {{ $key }} : {{ $value }}</div>
                    @endforeach
                @endif
            @endif
            @if($detail->note)
                <div class="note"><strong>Catatan :</strong><br>{{ $detail->note }}</div>
            @endif
        </div>
    @endforeach
    <div class="line"></div>
    <div class="center"><strong>SEGERA DIPROSES</strong></div>
</body>
</html>
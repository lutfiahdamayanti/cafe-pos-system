<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Struk Pesanan</title>

<style>

body {

    font-family: Arial, sans-serif;

    font-size: 12px;

}

.container {

    width: 100%;

}

.header {

    text-align: center;

}

.header h2 {

    margin-bottom: 5px;

}

.line {

    border-top: 1px dashed #000;

    margin: 10px 0;

}


table {

    width: 100%;

    border-collapse: collapse;

}


td {

    padding: 5px 0;

}


.text-right {

    text-align:right;

}


.total {

    font-size:14px;

    font-weight:bold;

}


.footer {

    text-align:center;

    margin-top:20px;

}


</style>

</head>


<body>


<div class="container">


<div class="header">

    <h2>Cafe POS</h2>

    <p>
        Struk Pembayaran
    </p>

</div>


<div class="line"></div>


<table>


<tr>

<td>
No Order
</td>

<td class="text-right">

{{ $order->order_number }}

</td>

</tr>


<tr>

<td>
Tanggal
</td>

<td class="text-right">

{{ $order->created_at->format('d-m-Y H:i') }}

</td>

</tr>


<tr>

<td>
Customer
</td>

<td class="text-right">

{{ $order->customer_name }}

</td>

</tr>


<tr>

<td>
Pembayaran
</td>

<td class="text-right">

{{ $order->payment }}

</td>

</tr>


</table>


<div class="line"></div>



<table>


@foreach($order->details as $detail)

<tr>

<td>

{{ $detail->menu->name }}

<br>

x{{ $detail->qty }}

</td>


<td class="text-right">

Rp {{ number_format($detail->total,0,',','.') }}

</td>


</tr>


@endforeach


</table>


<div class="line"></div>



<table>


<tr>

<td>
Subtotal
</td>

<td class="text-right">

Rp {{ number_format($order->subtotal,0,',','.') }}

</td>

</tr>


<tr>

<td>
Pajak
</td>

<td class="text-right">

Rp {{ number_format($order->tax,0,',','.') }}

</td>

</tr>


<tr>

<td>
Service
</td>

<td class="text-right">

Rp {{ number_format($order->service,0,',','.') }}

</td>

</tr>


<tr>

<td class="total">

TOTAL

</td>

<td class="text-right total">

Rp {{ number_format($order->total,0,',','.') }}

</td>

</tr>


</table>



<div class="line"></div>


<div class="footer">

<p>
Status : {{ $order->status }}
</p>

<p>
Terima kasih sudah berkunjung ☕
</p>

</div>


</div>


</body>

</html>
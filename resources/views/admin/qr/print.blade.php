<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Print QR Meja</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card{
    width:260px;
    margin:auto;
    margin-bottom:40px;
}
@media print{
button{
display:none;
}
}
</style>
</head>

<body>
    <div class="container mt-4">
        <button 
        onclick="window.print()"
        class="btn btn-success mb-4">
        Print QR
    </button>

    <div class="row">
        @foreach($tables as $table)
        <div class="col-md-4">
            <div class="card text-center p-4">
                <h2 class="fw-bold mb-3">
                    MEJA {{ $table }}
                </h2>

                <div class="d-flex justify-content-center">
                    {!! QrCode::size(180)->generate(url('/menu?table='.$table)) !!}
                </div>

                <p class="mt-4">
                    Scan untuk memesan
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
</body>
</html>
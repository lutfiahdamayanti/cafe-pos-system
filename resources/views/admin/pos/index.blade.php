@extends('layouts.admin')
@section('title','Pesanan Baru')
@section('content')

<div class="row">
    {{-- ================= MENU ================= --}}
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4> Daftar Menu </h4>
            </div>

            <div class="card-body">
                <div class="row">
                    @foreach($menus as $menu)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 pos-menu-card">
                                <img 
                                src="{{ asset('images/'.$menu->image) }}"
                                class="card-img-top pos-menu-image">
                                <div class="card-body d-flex flex-column">
                                    <div class="pos-menu-content">
                                        <h5>
                                        {{ $menu->name }}
                                        </h5>
                                        <!-- <p class="text-success fw-bold mb-2">
                                            Regular : Rp {{ number_format($menu->price,0,',','.') }}
                                            <br>
                                            Large :
                                            Rp {{ number_format($menu->price + $menu->large_price,0,',','.') }}
                                        </p> -->
                                        <div class="mt-3 pos-option-area">

                                            {{-- Ukuran --}}
                                            @php
                                            $sizeOption = $menu->options
                                                ->where('name','Ukuran')
                                                ->first();
                                            @endphp

                                            @if($sizeOption)

                                            <label class="fw-bold mb-1">
                                                Ukuran
                                            </label>

                                            <select class="form-select size-select mb-2">
                                                <option value="">
                                                        -- Pilih --
                                                </option>

                                                @foreach($sizeOption->values as $value)
                                                <option
                                                    value="{{ $value->value }}"
                                                    data-price="{{ $value->extra_price }}">
                                                    {{ $value->value }}
                                                    @if($value->extra_price > 0)
                                                    (+Rp {{ number_format($value->extra_price,0,',','.') }})
                                                    @endif
                                                </option>
                                                @endforeach
                                            </select>

                                            @endif

                                            {{-- Pilihan Menu --}}
                                            @foreach($menu->options as $option)
                                                <label class="fw-bold mt-2">
                                                    {{ $option->name }}
                                                </label>

                                                <select class="form-select option-select mb-2">
                                                    <option value="">
                                                        -- Pilih --
                                                    </option>

                                                    @foreach($option->values as $value)
                                                        <option
                                                            value="{{ $value->id }}"
                                                            data-name="{{ $option->name }}"
                                                            data-value="{{ $value->value }}"
                                                            data-price="{{ $value->extra_price }}">

                                                            {{ $value->value }}

                                                            @if($value->extra_price>0)
                                                                (+Rp {{ number_format($value->extra_price,0,',','.') }})
                                                            @endif
                                                        </option>
                                                    @endforeach

                                                </select>
                                            @endforeach

                                            {{-- Catatan --}}
                                            <textarea
                                                class="form-control note mt-2"
                                                placeholder="Catatan"></textarea>
                                        </div>
                                        <!-- <div class="mt-auto"></div> -->
                                        <button
                                            type="button"
                                            class="btn btn-success w-100 mt-3 add-menu"
                                            data-id="{{ $menu->id }}"
                                            data-name="{{ $menu->name }}"
                                            data-price="{{ $menu->price }}">
                                            Tambahkan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

{{-- ================= CART ================= --}}
<div class="col-lg-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4> Pesanan </h4>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.pos.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label> Nama Pelanggan </label>
                    <input
                    type="text"
                    name="customer_name"
                    id="customer_name"
                    class="form-control"
                    required>
                </div>
                
                <div class="mb-3">
                    <label> Nomor HP </label>
                    <input
                    type="text"
                    name="phone"
                    id="phone"
                    class="form-control"
                    required>
                </div>

                <div class="mb-3">
                    <label> Nomor Meja </label>
                    <select name="table_number" class="form-select" required>
                        <option value="">
                        -- Pilih Meja --
                        </option>

                        @foreach($tables as $table)
                            <option value="{{ $table }}">
                                {{ $table }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label> Pembayaran </label>
                    <select name="payment" class="form-select">
                        <option> Cash </option>
                        <option> QRIS </option>
                        <option> E-Wallet </option>
                        <option> Virtual Account </option>
                    </select>
                </div>
                <hr>
                <div id="cart-items">
                    <p class="text-muted">
                        Belum ada menu.
                    </p>
                </div>

                <div id="hidden-cart"></div>
                <hr>

                <div id="grandTotal"> Rp 0 </div>
                <button
                    type="submit"
                    class="btn btn-success w-100 mt-3">
                    Simpan Pesanan
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')

<script>
let cart=[];
function renderCart(){
    let html='';
    let hidden='';
    let subtotal=0;
    cart.forEach((item,index)=>{
        subtotal += item.price * item.qty;
        html += `
        <div class="border-bottom py-2">
        <strong>${item.name}</strong>
        <br>
        <small>Ukuran : ${item.size}</small>
        ${item.options.map(o=>`

        <br>
        <small>• ${o.name} : ${o.value}</small>
        `).join("")}
        ${item.note ? `
        <br>
        <small><b>Catatan :</b> ${item.note}</small>
        ` : ""}

        <br>
        Qty ${item.qty}
        x Rp ${item.price.toLocaleString()}
        </div> `;

        hidden += `
        <input type="hidden" name="menus[${index}][id]" value="${item.id}">
        <input type="hidden" name="menus[${index}][qty]" value="${item.qty}">
        <input type="hidden" name="menus[${index}][size]" value="${item.size}">
        <input type="hidden" name="menus[${index}][note]" value="${item.note}">`;

        item.options.forEach(function(option, i){

    hidden += `
        <input
            type="hidden"
            name="menus[${index}][options][]"
            value="${option.id}">
    `;

});
    });
    
    if(cart.length==0){
        html = ` <p class="text-muted"> Belum ada menu. </p> `;
    }
    
    let tax = subtotal * 0.11;
    let service = 3000;
    let total = subtotal + tax + service;
    document.getElementById('cart-items').innerHTML = html;
    document.getElementById('hidden-cart').innerHTML = hidden;
    document.getElementById('grandTotal').innerHTML = `
    Subtotal :
    Rp ${subtotal.toLocaleString()}
    <br>
    PPN 11% :
    Rp ${tax.toLocaleString()}
    
    <br>
    Service :
    Rp ${service.toLocaleString()}
    
    <hr>
    <h5> Total : Rp ${total.toLocaleString()} </h5> `;
}

document.querySelectorAll('.add-menu')
.forEach(btn=>{
btn.addEventListener('click',function(){

let card = this.closest(".card-body");

let size = card.querySelector(".size-select");

let sizeValue = "-";

if(size){
    sizeValue = size.value;
}
let price = parseInt(this.dataset.price);
let options = [];

card.querySelectorAll(".option-select").forEach(function(select){

    if(select.value!=""){

        let opt = select.options[select.selectedIndex];

        price += parseInt(opt.dataset.price);

        options.push({
            id: select.value,
            name: opt.dataset.name,
            value: opt.dataset.value
        });

    }

});

let note = card.querySelector(".note").value;

let id = this.dataset.id;
let name = this.dataset.name;

let found = cart.find(item => {

    return (
        item.id == id &&
        item.size == sizeValue &&
        JSON.stringify(item.options) == JSON.stringify(options) &&
        item.note == note
    );

});

if(found){

found.qty++;

}else{

cart.push({

    id:id,
    name:name,
    price:price,
    qty:1,
    size:sizeValue,
    options:options,
    note:note

});

}

renderCart();

});

});
</script>
@endpush
@endsection
<?php
if (!isset($_SESSION['id'])) {
return redirect('/resto/login');
}
$keranjang = [];
if (isset($_SESSION['keranjang'])) {
$keranjang = $_SESSION['keranjang'];
}

$msg = '';
if (null !== session()->get('msg')) {
$msg =
"<div class=\"alert alert-dismissible alert-danger\">
    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>
    " .
    session()->get('msg') .
    "
</div>";
}
?>
@extends('layout.resto')
@section('content')
    <div class="list-group">
        <div class="row">
            <div class="col-lg-8">
                <div class="bg-white p-3">

                    <div class="row">
                        <div class="col-lg-8 h1 pt-2">Daftar Menu</div>

                    </div>
                    <table class="table">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th scope="col">ID</th>
                                <th scope="col">Nama barang</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Qty</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($menu as $p)
                                <tr class="table-secondary">
                                    <td class="pt-5">{{ $p->id }}</td>
                                    <td class="pt-5">{{ $p->nama }}</td>
                                    <td class="pt-5">RP. <?php echo number_format($p->harga, 0, '', '.'); ?></td>
                                    <td>
                                        <form class="d-flex" method="POST"
                                            action="/resto/pembayaran/tambah/{{ $p->id }}">
                                            {{ csrf_field() }}
                                            <input type="number" min="1" max="50" oninput="validity.valid||(value='');" class="form-control" style="width:100px ;" id=""
                                                placeholder="Qty" name="qty" required>
                                            <button class="btn btn-primary my-2 my-sm-0" type="submit">+</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="bg-white p-3">
                    <div class="row">
                        <div class="h1 pt-2">Keranjang</div>
                        <div class="card border-light mb-3">
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-action active">
                                    <div class="row" style="width: 100%; ">
                                        <div class="col-lg-5">Nama</div>
                                        <div class="col-lg-3">Qty</div>
                                        <div class="col-lg-3">Subtotal</div>

                                    </div>
                                </li>
                                @foreach ($keranjang as $i)

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="row pt-1" style="width: 100%;">
                                            <div class="col-lg-5">
                                                {{ $i['nama'] }}
                                            </div>
                                            <div class="col-lg-5" style="display: none">
                                                {{ $i['id'] }}
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="col-lg-3">
                                                    <form d method="POST"
                                                        action="/resto/pembayaran/edit/{{ $i['id'] }}">
                                                        <input type="number" class="form-control" style="width:100px ;"
                                                            id="" placeholder="Qty" value="{{ $i['qty'] }}">
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                Rp.
                                                <?php
                                                $sub = $i['harga'] * $i['qty'];
                                                echo number_format($sub, 0, '', '.');
                                                ?>
                                            </div>
                                            <div class="col-lg-3">
                                                <form method="GET" action="/resto/pembayaran/hapus/{{ $i['id'] }}">
                                                    {{ csrf_field() }}
                                                    {{-- <input type="text" value={{ $i['id'] }} name> --}}
                                                    <input class="btn btn-primary my-2 my-sm-0" type="submit" value="Hapus">
                                                    {{-- <button id="id_mkn" onclick="console.log(this.value)" class="btn btn-primary my-2 my-sm-0" type="button" --}}
                                                    {{-- value={{ $i['id'] }}>Hapus</button> --}}
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <form action="/resto/pembayaran/bayar" method="POST" class="d-flex mb-3">

                                {{ csrf_field() }}
                                <div class="input-group">
                                    <span class="input-group-text">Rp.</span>
                                    <input name="bayar" type="number" class="form-control"
                                        aria-label="Amount (to the nearest dollar)" required>
                                </div>
                                <div>

                                </div>
                                <button class="btn btn-primary my-2 my-sm-0" type="submit">Bayar</button>
                            </form>
                            <?php echo $msg; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






@endsection

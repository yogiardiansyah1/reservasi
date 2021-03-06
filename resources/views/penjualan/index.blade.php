<?php
if (!isset($_SESSION['id'])) {
return redirect('/resto/login');
}
?>
@extends('layout.resto')
@section('content')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

<div class="list-group">
        <div class="row">
            <div class="col-lg-12">
                <div class="bg-white p-3">
                    <div class="row">
                        <div class="col-lg-8 h1 pt-2">Riwayat Penjualan</div>
                    </div>
                    <table id="riwayat" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th scope="col">ID</th>
                                <th scope="col">ID Karyawan</th>
                                <th scope="col">Total</th>
                                <th scope="col">Bayar</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr class="table-secondary">
                                    <td class="pt-5">{{ $d->id }}</td>
                                    <td class="pt-5">{{ $d->id_karyawan }}</td>
                                    <td class="pt-5">{{ $d->total }}</td>
                                    <td class="pt-5">{{ $d->bayar }}</td>
                                    <td class="pt-5">{{ $d->tanggal }}</td>
                                    <td>
                                        <form method="POST" action="/resto/detail">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{$d->id}}">
                                            <input type="submit" value="Detail"> 
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
        $('#riwayat').DataTable();
        } );
    </script>
@endsection
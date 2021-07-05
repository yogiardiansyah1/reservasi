<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function resto()
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            return redirect('/resto/login');
        }
        return view('resto/kasir');
    }

    public function resto_login()
    {
        return view('resto/login');
    }

    public function resto_pembayaran()
    {
        session_start();
        if (isset($_SESSION['id'])) {
            $object = new MenuController();
            return view('resto/pembayaran', ['menu' => $object->getAllMenu()]);
        } else {
            return redirect()->route('login');
        }
    }

    public function resto_penjualan()
    {
        return view('resto/penjualan');
    }

    public function resto_karyawan()
    {
        return view('resto/karyawan');
    }

    public function reservasi_index()
    {
        return view('reservasi/index');
    }

    public function reservasi_detail(Request $data)
    {
        $tgl = str_replace('T', ' ', $data['tanggal']);
        date_default_timezone_set('Asia/Jakarta');
        if(date('d') >= date_format(date_create($tgl), 'd')) {
            return redirect()->back()->with(['msg' => 'Minimal tanggal reservasi H-1.']);
        }
        $object = new ReservasiController();
        return view('reservasi/detail', ['tgl' => $data['tanggal'], 'data' => $object->getSisaKursi($tgl)]);
    }

    public function test()
    {
        $object = new ReservasiController();
        return $object->getReservasiAktif();
    }

    public function csv()
    {
        return view('penjualan/csv');
    }
}

<?php

namespace App\Http\Controllers;

use DOMDocument;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function insert($data)
    {
        DB::table('penjualan')->insert($data);
    }

    public function hapus($id){
        session_start();
        foreach ($_SESSION['keranjang'] as $item){
            if ($item['id'] == $id) {
                $index_arr = array_search($item, $_SESSION['keranjang']);
                unset($_SESSION['keranjang'][$index_arr]); 
            }
        }
        array_values($_SESSION['keranjang']);
        return redirect()->route('pembayaran');
        // $object = new MenuController();
        // return $object->getMenuById($id);
        // echo("<script type='text/javascript'>alert('$id');</script>");
        // session_start();
        // if (isset($_SESSION['keranjang'])){
        //     unset($_SESSION['keranjang'][$i]);
        // }

        
    }

    public function bayar(Request $data)
    {
        date_default_timezone_set('Asia/Jakarta');
        session_start();
        $keranjang = $_SESSION['keranjang'];


        $id = date('ymd');
        $count = DB::table('penjualan')->select('id')->where('id', 'like', $id . '%')->get()->count();
        $count = $count + 1;
        $id = $id . str_pad($count, 4, '0', STR_PAD_LEFT);

        $total = 0;
        $detail = array();
        foreach ($keranjang as $i) {
            $qty = $i['qty'];
            $harga = $i['harga'];
            $sub = $qty * $harga;
            $total = $total + $sub;
            $menu = array(
                'id_penjualan' => $id,
                'id_menu' => $i['id'],
                'qty' => $qty,
                'harga' => $harga,
                'subtotal' => $sub
            );
            array_push($detail, $menu);
        }

        $bayar = $data['bayar'];
        if ($bayar < $total) {
            return redirect()->route('pembayaran')->with(['msg' => 'Pembayaran Tidak Mencukupi']);
        }

        $ins = array(
            'id' => $id,
            'id_karyawan' => $_SESSION['id'],
            'total' => $total,
            'bayar' => $bayar,
            'tanggal' => date('Y-m-d H:i')
        );

        DB::table('penjualan')->insert($ins);
        DB::table('detail_penjualan')->insert($detail);
        unset($_SESSION['keranjang']);
        return redirect()->route('pembayaran');
    }

    public function getAll()
    {
        return DB::table('penjualan')->get();
    }

    public function getPenjualanByRange($from, $to)
    {
        // return DB::table('penjualan')->where('id', $from.'%')->get();
    }

    //Detail Penjualan
    public function getDetail(request $id)
    {
        return view ('penjualan/detail',['data' => DB::table('detail_penjualan')->where('id_penjualan',$id['id'])->get()]);
    }

    public function getdetailbyDatenow()
    {
        $date = date('ymd');
        return view('penjualan/xml', ['data' => DB::table('detail_penjualan')->where('id_penjualan', 'LIKE', '%' . $date . '%')->get()]);
    }

    public function createxml()
    {
        $d = date('ymd');
        $data = DB::table('detail_penjualan')->where('id_penjualan', 'LIKE', '%' . $d . '%')->get();

        $dom = new DOMDocument();

        $dom->encoding = 'utf-8';

        $dom->xmlVersion = '1.0';

        $dom->formatOutput = true;

        $date = date('dmY');
        $path = 'public/xml/';
        $xml_file_name = $date . '.xml';

        $root = $dom->createElement('detail_penjualan');

        foreach ($data as $dat) {
            $penjualan_node = $dom->createElement('penjualan');
            $child_node_id_penjualan = $dom->createElement('ID_Penjualan', $dat->id_penjualan);
            $penjualan_node->appendChild($child_node_id_penjualan);

            $child_node_id_menu = $dom->createElement('ID_Menu', $dat->id_menu);
            $penjualan_node->appendChild($child_node_id_menu);

            $child_node_quantity = $dom->createElement('Quantity', $dat->qty);
            $penjualan_node->appendChild($child_node_quantity);

            $child_node_harga = $dom->createElement('Harga', $dat->harga);
            $penjualan_node->appendChild($child_node_harga);

            $child_node_subtotal = $dom->createElement('Subtotal', $dat->subtotal);
            $penjualan_node->appendChild($child_node_subtotal);
            $root->appendChild($penjualan_node);
        }
        $dom->appendChild($root);
        $dom->save($path . $xml_file_name);
    }

    public function createcsv()
    {
        $date = date('dmY');
        $path_xml = 'public/xml/';
        $path_csv = 'public/csv/';

        $xml_file_name = $path_xml . $date . '.xml';
        $csv_file_name = $path_csv . $date . '.csv';

        if (file_exists($xml_file_name)) {
            $xml = simplexml_load_file($xml_file_name);

            $output_file = fopen($csv_file_name, 'w');

            $header = false;

            foreach ($xml as $key => $value) {
                if (!$header) {
                    fputcsv($output_file, array_keys(get_object_vars($value)));
                    $header = true;
                }
                fputcsv($output_file, get_object_vars($value));
            }

            fclose($output_file);
        }
    }
}

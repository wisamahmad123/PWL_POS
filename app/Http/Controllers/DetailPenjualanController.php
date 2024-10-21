<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\DetailPenjualanModel;
use App\Models\PenjualanModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class DetailPenjualanController extends Controller
{
    public function index($id)
    {
        $activeMenu = 'penjualan';
        $breadcrumb = (object) [
            'title' => 'Data Detail Penjualan',
            'list' => ['Home', 'Penjualan', 'Detail']
        ];
        $penjualan = PenjualanModel::find($id);
        return view('detail_penjualan.index', [
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'penjualan' => $penjualan,
            'id' => $id
        ]);
    }
    public function list($id)
    {
        $dPenjualan = DetailPenjualanModel::select(
            'detail_id',
            'penjualan_id',
            'barang_id',
            'harga',
            'jumlah'
        )
            ->where('penjualan_id', $id)
            ->with('penjualan', 'barang')
            ->get();
        return DataTables::of($dPenjualan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($item) { // menambahkan kolom aksi
                // Menggunakan rute show untuk tombol Detail
                $btn = '<a href="' . url('/penjualan/' . $item->penjualan_id . '/detail/' . $item->detail_id . '/show') . '" class="btn btn-info btn-sm">Detail</a> ';
                // Menggunakan rute edit_ajax untuk tombol Edit
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $item->penjualan_id . '/detail/' . $item->detail_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                // Menggunakan rute confirm_ajax untuk tombol Hapus
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $item->penjualan_id . '/detail/' . $item->detail_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // ada teks html
            ->make(true);
    }
    public function create_ajax($id)
    {
        $dPenjualan = DetailPenjualanModel::select(
            'detail_id',
            'penjualan_id',
            'barang_id',
            'harga',
            'jumlah'
        )
            ->where('penjualan_id', $id)
            ->get();
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_id')->get();
        return view('detail_penjualan.create_ajax', [
            'id' => $id,
            'barang' => $barang,
            'penjualan' => $penjualan,
        ]);
    }
    public function store_ajax(Request $request)
    {

        // Cek apakah permintaan adalah AJAX atau ingin JSON
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'penjualan_id' => ['required', 'integer', 'exists:t_penjualan,penjualan_id'],
                'barang_id' => ['required', 'integer', 'exists:m_barang,barang_id'],
                'harga' => ['required', 'integer'],
                'jumlah' => ['required', 'integer'],
            ];

            // Validasi permintaan
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }


            // Simpan data ke dalam model penjualan
            DetailPenjualanModel::create([
                'penjualan_id' => $request->penjualan_id,
                'barang_id' => $request->barang_id,
                'harga' => $request->harga,
                'jumlah' => $request->jumlah,
            ]);

            // Mengembalikan respons sukses
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan'
            ]);
        }

        return redirect('/');
    }
    // Menampilkan detail penjualan
    public function show(String $penjualan_id, $detail_id)
    {
        $dPenjualan = DetailPenjualanModel::select(
            'detail_id',
            'penjualan_id',
            'barang_id',
            'harga',
            'jumlah'
        )
            ->where('penjualan_id', $penjualan_id)
            ->where('detail_id', $detail_id)
            ->first();
        $breadcrumb = (object) [
            'title' => 'Detail detail Penjualan',
            'list' => ['Home', 'Penjualan', 'Detail', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail detail Penjualan'
        ];
        $activeMenu = 'penjualan'; // set menu yang sedang aktif

        return view('detail_penjualan.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'dPenjualan' => $dPenjualan,
            'detail_id' => $detail_id,
            'penjualan_id' => $penjualan_id,
            'activeMenu' => $activeMenu
        ]);
    }
    public function edit_ajax($penjualan_id, $detail_id)
    {
        // Ambil data penjualan berdasarkan ID penjualan
        $penjualan = PenjualanModel::all();
        $barang = BarangModel::all();

        // Ambil data detail penjualan berdasarkan ID detail penjualan
        $dPenjualan = DetailPenjualanModel::where('penjualan_id', $penjualan_id)
            ->where('detail_id', $detail_id)
            ->first();

        // Cek apakah penjualan dan detail penjualan ditemukan
        if (!$penjualan || !$dPenjualan) {
            // Kembalikan error atau redirect jika data tidak ditemukan
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Kirim data penjualan dan detail penjualan ke view
        return view('detail_penjualan.edit_ajax', compact('penjualan', 'barang', 'dPenjualan', 'penjualan_id', 'detail_id'));
    }

    // Menyimpan perubahan data penjualan barang ajax
    public function update_ajax(Request $request, $penjualan_id, $detail_id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'penjualan_id'  => 'required|integer',
                'barang_id' => 'required|integer',
                'harga' => 'required|integer',
                'jumlah' => 'required|integer',
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
            $check = DetailPenjualanModel::find($detail_id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax($penjualan_id, $detail_id)
    {
        $penjualan = PenjualanModel::all();
        $dPenjualan = DetailPenjualanModel::where('penjualan_id', $penjualan_id)
            ->where('detail_id', $detail_id)
            ->first();
        return view('detail_penjualan.confirm_ajax', compact('dPenjualan', 'penjualan', 'penjualan_id', 'detail_id'));
    }
    public function delete_ajax(Request $request, $penjualan_id, $detail_id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $dPenjualan = DetailPenjualanModel::where('penjualan_id', $penjualan_id)
                ->where('detail_id', $detail_id)
                ->first();
            if ($dPenjualan) { // jika sudah ditemuikan
                $dPenjualan->delete(); // penjualan di hapus
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    public function import($penjualan_id)
    {
        $dPenjualan = DetailPenjualanModel::where('penjualan_id', $penjualan_id)->first();
        return view('detail_penjualan.import', compact('dPenjualan'));
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_detail_penjualan' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_detail_penjualan'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'penjualan_id' => $value['A'],
                            'barang_id' => $value['B'],
                            'harga' => $value['C'],
                            'jumlah' => $value['D'],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    DetailPenjualanModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel($penjualanId = null)
    {
        // ambil data penjualan yang akan di export, cek apakah penjualan_id tertentu disertakan
        $dPenjualan = DetailPenjualanModel::select('penjualan_id', 'barang_id', 'harga', 'jumlah')
            ->orderBy('barang_id')
            ->with('barang');

        // jika ada penjualan_id, tambahkan filter untuk penjualan_id tertentu
        if ($penjualanId) {
            $dPenjualan->where('penjualan_id', $penjualanId);
        }

        // jalankan query
        $dPenjualan = $dPenjualan->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Penjualan');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga');
        $sheet->setCellValue('E1', 'Jumlah');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true); // bold header

        $no = 1; // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach ($dPenjualan as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->penjualan->penjualan_id);
            $sheet->setCellValue('C' . $baris, $value->barang->barang_nama);
            $sheet->setCellValue('D' . $baris, $value->harga);
            $sheet->setCellValue('E' . $baris, $value->jumlah);
            $baris++;
            $no++;
        }
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }
        $sheet->setTitle('Data Detail Penjualan'); // set title sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Detail Penjualan ' . date('Y-m-d H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified:' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }
    // end function export_excel

    public function export_pdf($penjualanId)
    {
        $dPenjualan = DetailPenjualanModel::select('penjualan_id', 'barang_id', 'harga', 'jumlah')
            ->orderBy('barang_id')
            ->with('barang');

        // jika ada penjualan_id, tambahkan filter untuk penjualan_id tertentu
        if ($penjualanId) {
            $dPenjualan->where('penjualan_id', $penjualanId);
        }

        // jalankan query
        $dPenjualan = $dPenjualan->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('detail_penjualan.export_pdf', ['dPenjualan' => $dPenjualan]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url $pdf->render();
        return $pdf->stream('Data penjualan' . date('Y-m-d H:i:s') . '.pdf');
    }
}

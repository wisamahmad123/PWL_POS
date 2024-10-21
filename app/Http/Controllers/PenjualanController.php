<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function index()
    {
        $activeMenu = 'penjualan';
        $breadcrumb = (object) [
            'title' => 'Data Penjualan',
            'list' => ['Home', 'Penjualan']
        ];
        $user = UserModel::select('user_id', 'username')->get();
        return view('penjualan.index', [
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'user' => $user
        ]);
    }
    public function list(Request $request)
    {
        $penjualan = PenjualanModel::select(
            'penjualan_id',
            'user_id',
            'pembeli',
            'penjualan_kode',
            'penjualan_tanggal'
        )->with('user');
        $user_id = $request->input('filter_user');
        if (!empty($user_id)) {
            $penjualan->where('user_id', $user_id);
        }
        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/penjualan/' . $penjualan->penjualan_id . '/detail') . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // ada teks html
            ->make(true);
    }
    public function create_ajax()
    {
        $user = UserModel::select('user_id', 'username')->get();
        return view('penjualan.create_ajax')->with('user', $user);
    }
    public function store_ajax(Request $request)
    {

        // Cek apakah permintaan adalah AJAX atau ingin JSON
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'user_id' => ['required', 'integer', 'exists:m_user,user_id'],
                'pembeli' => ['required', 'String' , 'max: 50'],
                'penjualan_kode' => ['required', 'String'],
                'penjualan_tanggal' => ['required', 'date']
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
            PenjualanModel::create([
                'user_id' => $request->user_id,
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => $request->penjualan_tanggal,
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
    public function show(string $id)
    {
        $penjualan = penjualanModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Detail Penjualan',
            'list' => ['Home', 'Penjualan', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail Penjualan'
        ];
        $activeMenu = 'penjualan'; // set menu yang sedang aktif
        return view('penjualan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'penjualan' => $penjualan, 'activeMenu' => $activeMenu]);
    }
    public function edit_ajax($id)
    {
        $penjualan = PenjualanModel::findOrFail($id);
        $user = UserModel::all();
        return view('penjualan.edit_ajax', ['penjualan' => $penjualan, 'user' => $user]);
    }

    // Menyimpan perubahan data penjualan barang ajax
    public function update_ajax(Request $request, $id){
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id'  => 'required|integer',
                'pembeli' => 'required|String|max:50',
                'penjualan_kode' => 'required|String|max:20',
                'penjualan_tanggal' => 'required|date',
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
            $check = PenjualanModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else{
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax($id)
    {
        $penjualan = PenjualanModel::find($id);
        return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
    }
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::find($id);
            if ($penjualan) { // jika sudah ditemuikan
                $penjualan->delete(); // penjualan di hapus
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
    public function import()
    {
        return view('penjualan.import');
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_penjualan'); // ambil file dari request
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
                            'user_id' => $value['A'],
                            'pembeli' => $value['B'],
                            'penjualan_kode' => $value['C'],
                            'penjualan_tanggal' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value['D'])->format('Y-m-d H:i:s'),
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    PenjualanModel::insertOrIgnore($insert);
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

    public function export_excel()
    {
        // ambil data penjualan yang akan di export
        $penjualan = PenjualanModel::select('user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->orderBy('user_id')
            ->with('user')
            ->get();
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Pembeli');
        $sheet->setCellValue('D1', 'Kode Penjualan');
        $sheet->setCellValue('E1', 'Tanggal penjualan');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true); // bold header

        $no = 1; // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach ($penjualan as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->user->username);
            $sheet->setCellValue('C' . $baris, $value->pembeli);
            $sheet->setCellValue('D' . $baris, $value->penjualan_kode);
            $sheet->setCellValue('E' . $baris, $value->penjualan_tanggal);
            $baris++;
            $no++;
        }
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }
        $sheet->setTitle('Data Penjualan'); // set title sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data penjualan ' . date('Y-m-d H:i:s') . '.xlsx';
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
    } // end function export_excel

    public function export_pdf()
    {
        $penjualan = PenjualanModel::select('user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->orderBy('user_id')
            ->with('user')
            ->get();
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url $pdf->render();
        return $pdf->stream('Data penjualan' . date('Y-m-d H:i:s') . '.pdf');
    }
}

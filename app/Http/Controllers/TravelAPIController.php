<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use App\Http\Resources\TravelResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TravelAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mencari data paket travel
        $travel = Travel::all(); // Mengambil semua data dari tabel destinasi

        // Memeriksa apakah data Destinasi ditemukan
        if ($travel -> isEmpty()) {
            return response() -> json([
                'status' => false,
                'message' => 'Get List Travel Failed.',
                'data' => []
            ], 404);
        }
        else {
            // Kembalikan data destinasi dalam format JSON dengan pesan sukses
            return TravelResource::collection($travel);
        }
    }

    public function filter(Request $request)
    {
        // Dapatkan data yang dikirim dari Laravel UI
        $filter = $request -> all();
        $filterDestinasi = $filter['lokasi'];
        $filterHarga = $filter['harga'];
        $filterBerangkat = $filter['durasi'];

        if (empty($filterDestinasi)) {
            // Memasukkan semua nama kota ke $filterDestinasi jika $filter['lokasi'] tidak berisi nilai
            $distinctKota = Travel::select('destination')->distinct()->get();
            $dataKotaAll = [];
            foreach ($distinctKota as $row) {
                $dataKotaAll[] = $row['destination'];
            }
            $filterDestinasi = $dataKotaAll;
        }
        if (empty($filterHarga)) {
            // Memasukkan semua harga ke $filterHarga jika $filter['harga'] tidak berisi nilai
            $filterHarga = [1, 2, 3, 4, 5];
        }
        if (empty($filterDurasi)) {
            // Memasukkan semua durasi wisata ke $filterDurasi jika $filter['durasi'] tidak berisi nilai
            $filterDurasi = [1, 2, 3, 4, 5, 6, 7];
        }

        $kueriFilterHarga = "";
        // Membuat kueri filter harga
        if (count($filterHarga) != 0) {
            for ($i = 0; $i < count($filterHarga); $i++) {
                if ($filterHarga[$i] == 1) {
                    if (empty($kueriFilterHarga)) {
                        $kueriFilterHarga = "WHERE (price BETWEEN 0 AND 50000)";
                    }
                    else {
                        $kueriFilterHarga .= " OR (price BETWEEN 0 AND 50000)";
                    }
                }
                elseif ($filterHarga[$i] == 2) {
                    if (empty($kueriFilterHarga)) {
                        $kueriFilterHarga = "WHERE (price BETWEEN 50001 AND 150000)";
                    }
                    else {
                        $kueriFilterHarga .= " OR (price BETWEEN 50001 AND 150000)";
                    }
                }
                elseif ($filterHarga[$i] == 3) {
                    if (empty($kueriFilterHarga)) {
                        $kueriFilterHarga = "WHERE (price BETWEEN 150001 AND 300000)";
                    }
                    else {
                        $kueriFilterHarga .= " OR (price BETWEEN 150001 AND 300000)";
                    }
                }
                elseif ($filterHarga[$i] == 4) {
                    if (empty($kueriFilterHarga)) {
                        $kueriFilterHarga = "WHERE (price BETWEEN 300001 AND 500000)";
                    }
                    else {
                        $kueriFilterHarga .= " OR (price BETWEEN 300001 AND 500000)";
                    }
                }
                elseif ($filterHarga[$i] == 5) {
                    if (empty($kueriFilterHarga)) {
                        $kueriFilterHarga = "WHERE price > 500000";
                    }
                    else {
                        $kueriFilterHarga .= " OR price > 500000";
                    }
                }
            }
        }

        $kueriFilterBerangkat = "";
        // Membuat kueri filter departure
        if (count($filterBerangkat) != 0) {
            for ($i = 0; $i < count($filterBerangkat); $i++) {
                if ($filterBerangkat[$i] == 1) {
                    if (empty($kueriFilterBerangkat)) {
                        $kueriFilterBerangkat = "WHERE (departure BETWEEN '03:00:00' AND '06:00:00')";
                    }
                    else {
                        $kueriFilterBerangkat .= " OR (departure BETWEEN '03:00:00' AND '06:00:00')";
                    }
                }
                elseif ($filterBerangkat[$i] == 2) {
                    if (empty($kueriFilterBerangkat)) {
                        $kueriFilterBerangkat = "WHERE (departure BETWEEN '06:01:00' AND '09:00:00')";
                    }
                    else {
                        $kueriFilterBerangkat .= " OR (departure BETWEEN '06:01:00' AND '09:00:00')";
                    }
                }
                elseif ($filterHarga[$i] == 3) {
                    if (empty($kueriFilterBerangkat)) {
                        $kueriFilterBerangkat = "WHERE (departure BETWEEN '09:01:00' AND '12:00:00')";
                    }
                    else {
                        $kueriFilterBerangkat .= " OR (departure BETWEEN '09:01:00' AND '12:00:00')";
                    }
                }
                elseif ($filterHarga[$i] == 4) {
                    if (empty($kueriFilterBerangkat)) {
                        $kueriFilterBerangkat = "WHERE (departure BETWEEN '12:01:00' AND '15:00:00')";
                    }
                    else {
                        $kueriFilterBerangkat .= " OR (departure BETWEEN '12:01:00' AND '15:00:00')";
                    }
                }
                elseif ($filterHarga[$i] == 5) {
                    if (empty($kueriFilterBerangkat)) {
                        $kueriFilterBerangkat = "WHERE (departure BETWEEN '15:01:00' AND '18:00:00')";
                    }
                    else {
                        $kueriFilterBerangkat .= " OR (departure BETWEEN '15:01:00' AND '18:00:00')";
                    }
                }
                elseif ($filterHarga[$i] == 6) {
                    if (empty($kueriFilterBerangkat)) {
                        $kueriFilterBerangkat = "WHERE (departure BETWEEN '18:01:00' AND '21:00:00')";
                    }
                    else {
                        $kueriFilterBerangkat .= " OR (departure BETWEEN '18:01:00' AND '21:00:00')";
                    }
                }
                elseif ($filterHarga[$i] == 7) {
                    if (empty($kueriFilterBerangkat)) {
                        $kueriFilterBerangkat = "WHERE (departure BETWEEN '21:01:00' AND '23:59:00')";
                    }
                    else {
                        $kueriFilterBerangkat .= " OR (departure BETWEEN '21:01:00' AND '23:59:00')";
                    }
                }
            }
        }

        // Convert array of params to a comma-separated string of placeholders
        $placeholdersKota = implode(',', array_fill(0, count($filterDestinasi), '?'));

        $querySelected = "SELECT * FROM travels
                                WHERE destination IN (" . $placeholdersKota . ")
                                AND departure IN (
                                    SELECT DISTINCT departure FROM travels
                                    " . $kueriFilterBerangkat . "
                                )
                                AND price IN (
                                    SELECT DISTINCT price FROM travels
                                    " . $kueriFilterHarga . "
                                )
                                ORDER BY id";

        try {
            // Execute the query with bound parameters
            $data = DB::select($querySelected, array_merge($filterDestinasi));

            // Process and return data
            $filterData = [];
            foreach ($data as $row) {
                $filterData[] = (array) $row;
            }

            return response()->json([
                'status' => true,
                'message' => 'Filter Travel Berhasil Didapat.',
                'filter' => $filter,
                'data' => $filterData
            ], 200);
        } catch (\Exception $e) {
            // Handle the exception
            return response()->json([
                'status' => false,
                'message' => 'Gagal mendapatkan filter travel. ' . $e->getMessage(),
                'filter' => $filter,
                'data' => []
            ], 500); // Internal Server Error status code
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $travel = Travel::where('id', $id)->get()->first();

        // Memeriksa apakah data Destinasi ditemukan
        if (!$travel) {
            return response() -> json([
                'status' => false,
                'message' => 'Get List Travel Failed.',
                'data' => []
            ], 404);
        }
        else {
            // Kembalikan data destinasi dalam format JSON dengan pesan sukses
            return response() -> json([
                'status' => true,
                'message' => 'Get List Travel Success.',
                'data' => $travel
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

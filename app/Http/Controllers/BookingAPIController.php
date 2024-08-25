<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Travel;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BookingAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'travel_id' => 'required|numeric',
            'ticket' => 'required|integer',
            'date' => 'required|date'
        ]);

        // Return validation errors on failure
        if ($validator->fails()) {
            $errors = $validator->errors()->messages();
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors
            ], 422);
        }

        // Mencari data travel berdasarkan id untuk mendapatkan harga travel
        $travel = Travel::where('id', $request->travel_id)->get()->first();

        // Memeriksa apakah data ditemukan
        if (!$travel) {
            return response()->json([
                'status' => false,
                'message' => 'Data travel dengan id ' . $request->travel_id . ' tidak ditemukan.',
                'data' => []
            ], 404);
        } else {
            $hargaTravel = $travel['price'];
            $jumlahTiket = $request->ticket;
        }

        // Save Order
        $order = Order::create([
            'user_id' => $request->user_id,
            'travel_id' => $request->travel_id,
            'ticket' => $request->ticket,
            'date' => $request->date,
            'price' => $hargaTravel * $jumlahTiket
        ]);

        return new OrderResource($order, true, 'Order created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Mencari data order berdasarkan user_id
        $order = Order::where('user_id', $id)->get();

        for ($x = 0; $x < count($order); $x++) {
            // Mencari data order berdasarkan user_id
            $travel = Travel::where('id', $order[$x]['travel_id'])->get()->first();
            $order[$x]['travel_name'] = $travel['name'];
        }

        // Memeriksa apakah data ditemukan
        if ($order->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Data order dengan user_id ' . $id . ' tidak ditemukan.',
                'data' => []
            ], 404);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Data order dengan user_id ' . $id . ' berhasil ditemukan.',
                'data' => $order
            ], 200); // Mengembalikan respons JSON
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
        $order = Order::findOrFail($id);

        if ($order->delete()) {
            return new OrderResource($order, true, 'Order deleted successfully');
        }
    }
}

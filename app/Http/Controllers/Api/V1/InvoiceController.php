<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceResource;
use App\Http\Traits\HttpResponses as TraitsHttpResponses;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use TraitsHttpResponses;

    public function index()
    {
        return InvoiceResource::collection(Invoice::with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|in:C,B,P',
            'value' => 'required|numeric|min:0',
            'paid' => 'required|boolean',
            'payment_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Ocorreu um erro de validação.', 422);
        }

        $created = Invoice::create($validator->validated());

        if($created) {
            return $this->success($created, 'Invoice Criado.', 200);
        }
        return $this->error(null, 'Ocorreu um erro ao criar a fatura.', 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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

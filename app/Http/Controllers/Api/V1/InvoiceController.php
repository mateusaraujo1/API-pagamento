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

    public function index(Request $request)
    {
        // return InvoiceResource::collection(Invoice::where([
        //     ['value', '>', 5000],
        //     ['paid', '=', true]
        // ])->with('user')->get());
        // return InvoiceResource::collection(Invoice::with('user')->get());
        return (new Invoice())->filter($request);
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
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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

        $invoice = Invoice::find($id);

        if (!$invoice) {
            return $this->error(null, 'Fatura não encontrada.', 404);
        }

        $updated = $invoice->update($validator->validated());

        if($updated) {
            return $this->success($invoice, 'Fatura atualizada.', 200);
        }
        return $this->error(null, 'Ocorreu um erro ao atualizar a fatura.', 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        
        if($invoice) {
            return $this->success(null, 'Fatura deletada.', 200);
        }
        return $this->error(null, 'Ocorreu um erro ao deletar a fatura.', 400);
    }
}

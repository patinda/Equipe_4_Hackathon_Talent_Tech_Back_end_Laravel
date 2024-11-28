<?php

namespace App\Http\Controllers;

use App\Models\TypeTransaction;
use Illuminate\Http\Request;

class TypeTransactionController extends Controller
{
    public function index()
    {
        return response()->json(TypeTransaction::all());
    }

    public function show($id)
    {
        $typeTransaction = TypeTransaction::find($id);

        if (!$typeTransaction) {
            return response()->json(['message' => 'failure'], 404);
        }

        return response()->json($typeTransaction);
    }

    public function store(Request $request)
    {
        $typeTransaction = TypeTransaction::create($request->all());

        return response()->json($typeTransaction, 201);
    }

    public function update(Request $request, $id)
    {
        $typeTransaction = TypeTransaction::find($id);

        if (!$typeTransaction) {
            return response()->json(['message' => 'failure'], 404);
        }

        $typeTransaction->update($request->all());

        return response()->json($typeTransaction);
    }

    public function destroy($id)
    {
        $typeTransaction = TypeTransaction::find($id);

        if (!$typeTransaction) {
            return response()->json(['message' => 'failure'], 404);
        }

        $typeTransaction->delete();

        return response()->json(['message' => 'TypeTransaction deleted']);
    }
}

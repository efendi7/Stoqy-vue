<?php

namespace App\Repositories;

use App\Models\StockTransaction;

class StockTransactionRepository
{
    public function getAllTransactionsPaginated($perPage = 10)
    {
        return StockTransaction::with('product')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findById($id)
    {
        return StockTransaction::with('product')->find($id);
    }

    public function create($data)
    {
        return StockTransaction::create($data);
    }

    public function update($id, $data)
    {
        $transaction = $this->findById($id);
        if ($transaction) {
            $transaction->update($data);
        }
        return $transaction;
    }

    public function delete($id)
    {
        $transaction = $this->findById($id);
        if ($transaction) {
            $transaction->delete();
            return true;
        }
        return false;
    }
    public function getConfirmedTransactions()
    {
    return StockTransaction::where('status', 'confirmed')->get();
    }

}

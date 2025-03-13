<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $fillable = ['user_id', 'transaction_id', 'action', 'description'];
}

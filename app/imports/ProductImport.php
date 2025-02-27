<?php
namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Product([
            'name'        => $row['name'],
            'category_id' => $row['category_id'],
            'price'       => $row['price'],
            'stock'       => $row['stock'],
        ]);
    }
}

<?php

namespace App\Interfaces;

interface ProductImportExportRepositoryInterface
{
    public function exportProducts();
    public function exportTemplate();
    public function importProducts($file);
}

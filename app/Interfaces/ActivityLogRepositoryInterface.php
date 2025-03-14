<?php
namespace App\Interfaces;

interface ActivityLogRepositoryInterface
{
    public function getAll($search = null);
    public function getByUser($userId);
}

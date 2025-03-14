<?php
namespace App\Contracts\Repositories;

interface ActivityLogRepositoryInterface
{
    public function getAll($search = null);
    public function getByUser($userId);
}

<?php

namespace App\Interfaces;

interface ActivityLogRepositoryInterface
{
    public function getActivityLogs($filters);
}

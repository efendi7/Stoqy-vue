<?php
namespace App\Enums;

enum UserRole: string {
    case Admin = 'admin';
    case WarehouseManager = 'warehouse_manager';
    case Staff = 'staff';
}

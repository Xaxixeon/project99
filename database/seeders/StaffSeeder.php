<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\StaffUser;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run()
    {
        // SUPER ADMIN (requested account)
        $super = StaffUser::firstOrCreate([
            'email' => 'superadmin@example.com'
        ], [
            'staff_code' => '0000001',
            'name'       => 'Super Admin',
            'username'   => 'superadmin',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);
        $super->assignRole('superadmin');

        // SUPER ADMIN
        $veronica = StaffUser::firstOrCreate([
            'email' => 'Aristaytta@example.com'
        ], [
            'staff_code' => '0001226',
            'name'       => 'Veronica Tasya Arista Amanda Nugrah',
            'username'   => 'ytta-superadmin',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);
        $veronica->assignRole('superadmin');

        // ADMIN
        $andi = StaffUser::firstOrCreate([
            'email' => 'Andi@example.com'
        ], [
            'staff_code' => '0001021',
            'name'       => 'Andi Setiawan',
            'username'   => 'andi-admin',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);
        $andi->assignRole('admin');

        // DESAINER
        $des1 = StaffUser::firstOrCreate([
            'email' => 'design1@example.com'
        ], [
            'staff_code' => '0002001',
            'name'       => 'Rizky Pratama',
            'username'   => 'rizky-designer',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);
        $des1->assignRole('designer');

        // PRODUCTION
        $prod1 = StaffUser::firstOrCreate([
            'email' => 'production1@example.com'
        ], [
            'staff_code' => '0003001',
            'name'       => 'Bagas Arya',
            'username'   => 'bagas-production',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);
        $prod1->assignRole('production');

        // CS
        $cs1 = StaffUser::firstOrCreate([
            'email' => 'cs1@example.com'
        ], [
            'staff_code' => '0004001',
            'name'       => 'Devi Ayu',
            'username'   => 'devi-cs',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);
        $cs1->assignRole('customer_service');

        // CASHIER
        $cash1 = StaffUser::firstOrCreate([
            'email' => 'cash1@example.com'
        ], [
            'staff_code' => '0005001',
            'name'       => 'Putra Wijaya',
            'username'   => 'putra-cashier',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);
        $cash1->assignRole('cashier');

        // WAREHOUSE
        $wh1 = StaffUser::firstOrCreate([
            'email' => 'warehouse1@example.com'
        ], [
            'staff_code' => '0006001',
            'name'       => 'Dimas Saputra',
            'username'   => 'dimas-warehouse',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);
        $wh1->assignRole('warehouse');

        // MARKETING
        $mk1 = StaffUser::firstOrCreate([
            'email' => 'marketing1@example.com'
        ], [
            'staff_code' => '0007001',
            'name'       => 'Salsa Aulia',
            'username'   => 'salsa-marketing',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);
        $mk1->assignRole('marketing');

        // MANAGER
        $mgr1 = StaffUser::firstOrCreate([
            'email' => 'manager1@example.com'
        ], [
            'staff_code' => '0008001',
            'name'       => 'Fauzan Prabu',
            'username'   => 'fauzan-manager',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);
        $mgr1->assignRole('manager');
    }
}

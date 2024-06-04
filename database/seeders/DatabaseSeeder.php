<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\OrderStatus;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //TODO Insert data into order_statuses table
        $dataStatus = [
            'Đang tìm tài xế', 
            'Tài xế đã nhận đơn',
            'Tài xế đã đến điểm lấy',
            'Tài xế đã đến điểm giao',
            'Giao hàng thành công',
            'Giao hàng thất bại',
            'Đơn hàng bị từ chối'
        ];

        foreach($dataStatus as $data) {
            OrderStatus::create([
                'status_name' => $data
            ]);
        }

        //TODO Insert data into drivers table
        Driver::create([
            'name' => 'unknow',
            'phone_number' => '0000000000',
            'password' => Hash::make('123456')
        ]);
    }
}

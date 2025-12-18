<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
//$ php artisan db:seed --class=DatabaseSeeder 
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $user = User::find(1);
        if ($user) {
            $user->syncPermissions(Permission::all());
        }
    }
}

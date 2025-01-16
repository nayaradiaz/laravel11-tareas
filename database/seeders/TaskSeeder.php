<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    
    {
        $user = User::find(1);
        for ($i=1; $i <5; $i++) { 
            DB::table('tasks')->insert([
                'title' => "Tarea $i",
                'description' => "Descripcion para tarea $i"
            ]);
            $user->sharedTasks()->attach($i, ['permission' => 'owner']);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $adminRole = Role::where('name', 'admin')->first();
        $editorRole = Role::where('name', 'editor')->first();
        $viewerRole = Role::where('name', 'viewer')->first();

        $adminUser = User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('$WhiteUsagiAdmin#1!'),
        ]);
        $adminUser->roles()->attach($adminRole);

        $editorUser = User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => Hash::make('$WhiteUsagiEditor#1!'),
        ]);
        $editorUser->roles()->attach($editorRole);

        $viewerUser = User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@example.com',
            'password' => Hash::make('$WhiteUsagiViewer#1!'),
        ]);
        $viewerUser->roles()->attach($viewerRole);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Post, User};
class PostSeeder extends Seeder
{
    public function run()
    {
        $userSuperAdmin = User::where('name','Super Admin')->first('id');
        $userNidaAdmin = User::where('name','Nida Admin')->first('id');

         $posts = [
            [
                'title' => 'post one superadmin', //superadmin Module
                'description' => 'lorem ipsum lorem ipsumlorem ipsumlorem ipsumlorem ipsumlorem ipsum', //superadmin Module
                'created_by' => $userSuperAdmin->id, //superadmin Module
            ],
            [
                'title' => 'post two superadmin', //superadmin Module
                'description' => 'lorem ipsum lorem ipsumlorem ipsumlorem ipsumlorem ipsumlorem ipsum', //superadmin Module
                'created_by' => $userSuperAdmin->id, //superadmin Module
            ],
            //
            [
                'title' => 'post one Nida', //superadmin Module
                'description' => 'lorem ipsum lorem ipsumlorem ipsumlorem ipsumlorem ipsumlorem ipsum', //superadmin Module
                'created_by' => $userNidaAdmin->id, //superadmin Module
            ],
            [
                'title' => 'post two Nida', //superadmin Module
                'description' => 'lorem ipsum lorem ipsumlorem ipsumlorem ipsumlorem ipsumlorem ipsum', //superadmin Module
                'created_by' => $userNidaAdmin->id, //superadmin Module
            ],


         ];
         Post::insert($posts);
    }
}

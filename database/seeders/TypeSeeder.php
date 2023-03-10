<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Type;

class TypeSeeder extends Seeder
{

    public function run(): void
    {
        $types=config("types");
        foreach($types as $type){
            $new_type=new Type();
            $new_type->label=$type["label"];
            $new_type->color=$type["color"];
            $new_type->save();
        }
    }
}
<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $a = factory(User::class,1)->create();
        $b = factory(\App\Models\Roles::class,1)->create();

        foreach ($a as $value) {
            $value->hid = \Vinkla\Hashids\Facades\Hashids::connection('user')->encode($value->id);
            $value->save();
            $value->role()->sync([$b[0]->id]);
        }

    }
}

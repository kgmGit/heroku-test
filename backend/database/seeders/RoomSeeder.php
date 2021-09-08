<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::whereNotNull('email_verified_at')->get();
        foreach ($users as $user) {
            $rooms = Room::factory()->count(10)->make();

            $count = 0;
            $rooms->each(function (Room $room) use (&$count) {
                if ($count % 2 === 0) {
                    $room->password = Hash::make('password');
                }
                $count++;
            });

            $user->ownRooms()->saveMany($rooms);
        }

        // ルームメンバー登録
        foreach (Room::all() as $room) {
            foreach ($users as $user) {
                $room->members()->attach($user);
            }
        }
    }
}

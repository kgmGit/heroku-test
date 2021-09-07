<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
            $rooms = Room::factory()->count(rand(1, 3))->make();
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

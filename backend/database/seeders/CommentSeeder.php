<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Room;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Room::all() as $room) {
            $comment_cnt = rand(1, 10);
            $members = $room->members()->get();
            for ($i = 1; $i <= $comment_cnt; $i ++) {
                /** @var \App\Models\User $member */
                $member = $members->random();

                $member->comments()->save(
                    Comment::factory()->make(['room_id' => $room->id])
                );
            }
        }
    }
}

<?php

namespace App\Jobs;

use App\Comment;
use App\Notification;
use App\Post;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CommentSave implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user_id_from;
    private $ref_id;
    private $noti_type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id_from, $ref_id, $noti_type)
    {
        $this->user_id_from = $user_id_from;
        $this->ref_id = $ref_id;
        $this->noti_type = $noti_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users_id = array();
        $name = User::find($this->user_id_from);
        $post = Post::find($this->ref_id);
        if(!empty($post)){
            Notification::create([
                'user_id_from' => $this->user_id_from,
                'user_id_to' => $post->user_id,
                'ref_id' => $this->ref_id,
                'noti_type' => $this->noti_type,
                'status' => '0'
            ]);

            array_push($users_id,$post->user_id);

            $comments = Comment::withoutTrashed()->where('post_id',$post->id)->whereNotIn('user_id',[$post->user_id])->get();
            if(!empty($comments)){
                foreach ($comments as $comment)
                {
                    Notification::create([
                        'user_id_from' => $this->user_id_from,
                        'user_id_to' => $comment->user_id,
                        'ref_id' => $this->ref_id,
                        'noti_type' => $this->noti_type,
                        'status' => '0'
                    ]);
                    array_push($users_id,$comment->user_id);
                }
            }
            SentNotifyComment::dispatch($name->name . ' ' . $name->surname,'แสดงความคิดเห็น',$users_id,$this->ref_id);
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/5/14
 * Time: 上午2:15
 */

namespace App\Transformers;


use App\Models\Replies;

class PostReplyTransformer extends BaseTransformer
{
    public $floor;
    public function __construct($floor)
    {
        $this->floor = $floor;
    }

    public function transform(Replies $replies)
    {
        $return = [
            'floor' => $this->floor,
            'hid' => $replies->hid,
            'source' => $replies->source,
//            'post_id' ,
//            'user_id',
            'isBlocked' => $replies->is_blocked,
//            'voteCount' => $replies->vote_count,
            'content' => $replies->is_blocked == 'yes' ? '该条因为一些原因被block,望周知' : $replies->body,
            'contentOriginal' => $replies->is_blocked == 'yes' ? '该条因为一些原因被block,望周知' : $replies->body_original,
            'createdAt' => rfc_3339($replies->created_at),
            'created' => $replies->created_at->diffForHumans()
        ];
        $this->floor++;
        if ($replies->post_hid) {
            $return['post'] = [
                'hid' =>  isset($replies->post->hid) ? $replies->post->hid : null,
                'title' => isset($replies->post->title) ? $replies->post->title : null,
            ];
        }

        if ($replies->user_hid) {
            if ($replies->is_blocked == 'yes') {
                $return['user'] = [
                    'hid' => config('g9zz.official.hid'),
                    'name' => config('g9zz.official.name'),
                    'avatar' => config('g9zz.official.avatar')
                ];
            } else {
                $return['user'] = [
                    'hid' => isset($replies->user->hid) ? $replies->user->hid : null,
                    'name' => isset($replies->user->name) ? $replies->user->name : null,
                    'avatar' => isset($replies->user->avatar) ? $replies->user->avatar : null
                ];
            }
        }
        return $return;
    }
}
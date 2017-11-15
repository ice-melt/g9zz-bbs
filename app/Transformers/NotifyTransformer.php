<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/6/8
 * Time: 下午10:16
 */

namespace App\Transformers;


use App\Models\Posts;
use App\Models\User;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Notifications\DatabaseNotification;

class NotifyTransformer extends BaseTransformer
{

    public function transform(DatabaseNotification $notifies)
    {
        $userRepository = app()->make(UserRepositoryInterface::class);
        $postRepository = app()->make(PostRepositoryInterface::class);
        $data = $notifies->data;

        $return = [
            'id' => $notifies->id,
            'type' => $data['type'],//类型
            'body' => cut_html_str($data['body'],100),//艾特里的内容
            'bodyOriginal' => $data['body_original'],//艾特里原内容
            'readAt' => isset($notifies->read_at) ? rfc_3339($notifies->read_at) : null,
            'createdAt' => rfc_3339($notifies->created_at),
            'updatedAt' => rfc_3339($notifies->updated_at),
        ];
        /** @var Posts $post */
        $post = $postRepository->hidFind($data['post_hid']);
        $return['post'] = [
            'hid' => $post->hid,
            'title' => $post->title,
            'author' => $post->author->name,
        ];

        /** @var User $userFrom */
        $userFrom = $userRepository->find($data['from_id']);
        $return['userFrom'] = [
            'hid' => $userFrom->hid,
            'name' => $userFrom->name,
            'avatar' => $userFrom->avatar
        ];

        /** @var User $userTo */
        $userTo = $userRepository->find($data['to_id']);
        $return['userTo'] = [
            'hid' => $userTo->hid,
            'name' => $userTo->name,
            'avatar' => $userTo->avatar
        ];

        return $return;
    }
}
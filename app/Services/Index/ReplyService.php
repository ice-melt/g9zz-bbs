<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/6/6
 * Time: 下午4:15
 */

namespace App\Services\Index;


use App\Exceptions\TryException;
use App\Notifications\ATNotify;
use App\Notifications\ReplyNotify;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\ReplyRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\BaseService;
use HyperDown\Parser;
use Vinkla\Hashids\Facades\Hashids;

class ReplyService extends BaseService
{
    protected $replyRepository;
    protected $postRepository;
    protected $userRepository;
    public function __construct(ReplyRepositoryInterface $replyRepository,
                                PostRepositoryInterface $postRepository,
                                UserRepositoryInterface $userRepository)
    {
        $this->replyRepository = $replyRepository;
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return mixed
     */
    public function paginate()
    {
        return $this->replyRepository->models()->paginate(per_page());
    }

    /**
     * @param $request
     * @return mixed
     */
    public function store($request)
    {
        $create = [
            'post_hid' => $request->get('postHid'),
            'user_hid' => $request->get('g9zz_user_hid'),
            'body_original' => $request->get('content'),
//            'at_ids' => $request->get('atIds'),
        ];

        $parse = new Parser();
        $body = $parse->makeHtml($create['body_original']);

        $preg='/(?<=@)[^\s]+\s?/';
        $res = preg_match_all($preg, $body, $match);
        if ($res) {
            $arr = [];
            $pregs = [];
            foreach ($match[0] as $key =>  $value) {
                $user = $this->userRepository->findUserByName($value);
                if (!empty($user)) {
                    $create['at_ids'][$key] = $user->id;
                    $aiTeUserName[$key] = $value;
                    $pregs[$key] = '/'.$value.'/';
                    $arr[$key] = "<a href='".env('APP_URL')."/user/". urlencode($value)."'>".$value."</a>";
                }
            }
            $body = preg_replace($pregs,$arr,$body);
        }

        $create['body'] = $body;
        try {
            \DB::beginTransaction();
            $result = $this->replyRepository->create($create);
            $result->hid  = Hashids::connection('reply')->encode($result->id);
            $result->save();

            $this->replyNotify($create,$request);

            //TODO::应该还有个 艾特的通知

            \DB::commit();
        } catch (\Exception $e) {
            $this->log('"service.error" to listener "' . __METHOD__ . '".', ['message' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
            \DB::rollBack();
            throw new TryException(json_encode($e->getMessage()),(int)$e->getCode());
        }
        return $result;
    }

    /**
     * 入通知
     * @param $create
     * @param $request
     */
    public function replyNotify($create,$request)
    {
        $notify = new \stdClass();
        $notify->post_hid = $create['post_hid'];//帖子ID
        $notify->from_id = $request->get('g9zz_user_id');//回复者
        $notify->type = 'reply';//类型
        $notify->body = $create['body'];//回复内容
        $notify->body_original = $create['body_original'];//回复原内容

        $author = $this->postRepository->getAuthorByPostHid($create['post_hid']);
        $authorHid = $author->user_hid;
        $authorId = Hashids::connection('user')->decode($authorHid);
        $notify->to_id = $authorId[0];

        $author->reply_count++;//post里 回复数添加1
        $author->last_reply_user_hid = $request->get('g9zz_user_hid');//更新post里  最后一个回复的人hid
        $author->last_reply_activated_at = date('Y-m-d H:i:s',time());//更新post里  最后一个回复的人hid
        $author->save();

        $user = $this->userRepository->getUserById($notify->to_id);
        if (!empty($user) && $notify->from_id != $notify->to_id) {
            $user->notify(new ReplyNotify($notify));
        }

        //艾特
        if (!empty($create['at_ids']) && is_array($create['at_ids']) ) {
            foreach ($create['at_ids'] as $value) {
                $notify->to_id = $value;
                $user = $this->userRepository->getUserById($value);
                if (!empty($user)) {
                    $user->notify(new ATNotify($notify));
                }
            }
        }

    }




    /**
     * @param $hid
     * @return mixed
     */
    public function hidFind($hid)
    {
        return $this->replyRepository->hidFind($hid);
    }

    /**
     * @param $request
     * @param $hid
     * @return mixed
     */
    public function hidUpdate($request,$hid)
    {
        $update = [
            'body_original' => $request->get('content'),
        ];
        $parse = new Parser();
        $update['body'] = $parse->makeHtml($update['body_original']);
        return $this->replyRepository->hidUpdate($update,$hid);
    }

    /**
     * @param $hid
     * @return mixed
     */
    public function hidDelete($hid)
    {
        return $this->replyRepository->hidDelete($hid);
    }

    /**
     * @param $hid
     * @return mixed
     */
    public function blockReply($hid)
    {
        $result = $this->replyRepository->hidFind($hid);
        $result->is_blocked = $result->is_blocked == 'yes' ? 'no' : 'yes';
        $result->save();
        return $result;
    }
}
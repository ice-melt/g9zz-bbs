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
use App\Repositories\Contracts\ReplyRepositoryInterface;
use App\Services\BaseService;
use HyperDown\Parser;
use Vinkla\Hashids\Facades\Hashids;

class ReplyService extends BaseService
{
    protected $replyRepository;
    public function __construct(ReplyRepositoryInterface $replyRepository)
    {
        $this->replyRepository = $replyRepository;
    }

    /**
     * @return mixed
     */
    public function paginate()
    {
        return $this->replyRepository->noBlocked()->paginate(per_page(100));
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
        ];

        $parse = new Parser();
        $body = $parse->makeHtml($create['body_original']);
        $create['body'] = $body;
//        dd($create);
        try {
            \DB::beginTransaction();
            $result = $this->replyRepository->create($create);
            $result->hid  = Hashids::connection('reply')->encode($result->id);
            $result->save();
            \DB::commit();
        } catch (\Exception $e) {
            $this->log('"service.error" to listener "' . __METHOD__ . '".', ['message' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
            \DB::rollBack();
            throw new TryException(json_encode($e->getMessage()),(int)$e->getCode());
        }
        return $result;
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
}
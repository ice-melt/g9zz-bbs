<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/6/21
 * Time: 上午10:46
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WechatMiniPrograms
 *
 * @property int $id
 * @property string $hid 加密ID
 * @property string $name 用户名
 * @property string $email
 * @property string $password
 * @property string $avatar
 * @property string $status
 * @property int $github_id
 * @property int $wechat_id
 * @property int $weibo_id
 * @property int $qq_id
 * @property int $google_id
 * @property int $douban_id
 * @property int $topic_count
 * @property int $reply_count
 * @property int $follower_count
 * @property string $verified
 * @property string $email_notify_enabled
 * @property string $register_source
 * @property string $last_actived_at
 * @property string $deleted_at
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereDoubanId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereEmailNotifyEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereFollowerCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereGithubId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereGoogleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereHid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereLastActivedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereQqId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereRegisterSource($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereReplyCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereTopicCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereVerified($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereWechatId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\WechatMiniPrograms whereWeiboId($value)
 * @mixin \Eloquent
 */
class WechatMiniPrograms extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'open_id',
        'nick_name',
        'language',
        'province',
        'country',
        'city',
        'avatar_url',
        'gender',
        'status',
    ];
}
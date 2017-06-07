<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/6/5
 * Time: 下午4:53
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\User
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
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereDoubanId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmailNotifyEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereFollowerCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereGithubId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereGoogleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereHid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereLastActivedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereQqId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRegisterSource($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereReplyCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereTopicCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereVerified($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereWechatId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereWeiboId($value)
 * @mixin \Eloquent
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereDeletedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Roles[] $role
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Posts[] $post
 */
class User extends Model
{
    use SoftDeletes;
    protected $table = 'users';
    protected $fillable = [
        'hid',
        'name',
        'email',
        'password',
        'avatar',
        'github_id',
        'wechat_id',
        'weibo_id',
        'qq_id',
        'google_id',
        'douban_id',
        'topic_count',
        'reply_count',
        'follower_count',
        'verified',
        'email_notify_enabled',
        'register_source',
        'last_actived_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function role()
    {
        return $this->belongsToMany(Roles::class,'role_user','user_id','role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function post()
    {
        return $this->hasMany(Posts::class,'user_hid','hid');
    }

}
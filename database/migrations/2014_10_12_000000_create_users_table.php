<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hid')->default('')->comment('加密ID');
            $table->string('name')->nullable()->default('')->comment('用户名');
            $table->string('email')->nullable()->default('');
            $table->string('mobile', 11)->default('')->comment('手机号');
            $table->string('password')->nullable()->default('');
            $table->string('avatar')->nullable();
            $table->string('status')->default('activated')->nullable()->comment('状态 activated正常,closure 封禁');
            $table->integer('github_id')->default(0);
            $table->integer('wechat_id')->default(0);
            $table->integer('weibo_id')->default(0);
            $table->integer('qq_id')->default(0);
            $table->integer('google_id')->default(0);
            $table->integer('douban_id')->default(0);
            $table->integer('xcx_id')->default(0);
            $table->integer('topic_count')->default(0);
            $table->integer('reply_count')->default(0);
            $table->integer('follower_count')->default(0);
            $table->enum('verified',['true','false'])->default('false');
            $table->enum('email_notify_enabled', ['yes',  'no'])->default('yes');
            $table->string('register_source')->nullable();
            $table->timestamp('last_activated_at')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
            $table->index('hid');
            $table->index('name');
            $table->index('email');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

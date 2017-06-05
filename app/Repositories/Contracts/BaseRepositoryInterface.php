<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/6/5
 * Time: 上午11:40
 */

namespace App\Repositories\Contracts;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

interface BaseRepositoryInterface extends Repository
{
    public function find($hid, $columns = array('*'));
    public function update(array $data, $hid);
    public function delete($hid);
}
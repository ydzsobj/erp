<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
use Illuminate\Http\Request;

class CommonController extends Controller
{

    //处理密码
    public function doPassword($password)
    {
        if ($password) {
            $password = preg_replace("/^(.{" . round(strlen($password) / 4) . "})(.+?)(.{" . round(strlen($password) / 6) . "})$/s", "\\1***\\3", $password);
            return $password;
        }
    }

    //写入登录日志
    public function admin_log($username, $password, $status, $admin_ip, $todo, $message)
    {
        $password = $this->doPassword($password);
        AdminLog::create(['username' => $username, 'admin_ip' => $admin_ip, 'status' => $status, 'password' => $password, 'todo' => $todo, 'message' => $message]);
    }



}

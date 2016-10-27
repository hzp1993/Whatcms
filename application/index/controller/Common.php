<?php
namespace app\index\controller;

use think\Controller;

class Common extends Controller
{
    public function login()
    {
        return $this->fetch();
    }
}
<?php
namespace app\admin\controller;

use app\admin\controller\Common;
class index extends Common
{
    public function index()
    {
        return $this->fetch();
    }
}
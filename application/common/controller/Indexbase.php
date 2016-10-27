<?php
namespace app\common\controller;

use think\Controller;
class Indexbase extends Controller
{
    public function __construct()
    {
        parent::__construct();
        self::aaaa();
    }
    
    public static function aaaa()
    {
        return 111;
    }
}
<?php
namespace Mdg\Frontend\Controllers;
use Lib\Member as Member;
use Lib\Auth as Auth, Lib\Arrays as Arrays;
use Mdg\Models as M;
use Mdg\Models\Category as Category;
use Lib as L;

class NotfoundController extends ControllerBase
{
    
    private $key = 'riwqe89712hjzxc8970230651mlk';
    
    public function indexAction() 
    {

 
        $this->view->title = '丰收汇-寻找合作伙伴';
        $this->view->keywords = '';
        $this->view->descript = '';

    }
}

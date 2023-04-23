<?php

namespace Mdg\Member\Controllers;
use Mdg\Models\Users as Users;
use Mdg\Models\AreasFull as mAreas;
use Mdg\Models\YncUsers as YncUsers;
use Lib as L;
use Lib\Member as Member;
use Mdg\Models as M;


class SubsidyController extends ControllerMember
{
    
    public function indexAction() 
    {
        $page = $this->request->get('p', 'int', 1 );
        $page = intval($page)>0 ? intval($page) : 1;
        $state = $this->request->get('state', 'int', 0 );

        
        $uid = $this->getUserID();
        $info  = M\UserSubsidy::findFirst(" user_id = '{$uid}'");
        if(!$info) {
            
        }
        if($state) {
            $let = $state == 1 ? ' > ' :' < ';
            $cond[] = " amount {$let} 0 ";
        }

        /* 获取详细支入支出记录 */
        $cond[] = " user_id = '{$uid}' and amount!='0.00' ";
        $cond = implode( ' AND ', $cond);
        $log = M\SubsidyLog::getSubsidyLogList($cond, $page);
        $this->view->state = $state;
        $this->view->data = $log;
        $this->view->p = $page;
        $this->view->info = $info;
        $this->view->title= '个人中心-我的补贴';

    }
}

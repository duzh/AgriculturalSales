<?php
/**
 * 订单管理
 */
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\PurchaseQuotation as Quotation;
use Mdg\Models\Orders as Orders;
use Mdg\Models\OrdersLog as OrdersLog;
use Mdg\Models\Users as Users;
use Lib\Func as Func;
use Lib\Pages as Pages;
use Mdg\Models as M;
use Lib\Category as lCategory;
use Mdg\Models\OrdersDelivery as OrdersDelivery;

class OrdersynpController extends ControllerMember
{
    /**
     * 云农宝订单列表
     * @return [type] [description]
     */
    public function indexAction() {
        $p = $this->request->get('p', 'int', 1 );
        $order_sn = $this->request->get('order_sn', 'string', '' );
        $num = $this->request->get('num', 'string', '' );
        $cond[] = " 1 ";

        if($order_sn)
        {
            $cond[] = " order_no = '{$order_sn}'";
        }
        if($num)
        {
            $cond[] = " serial_num = '{$num}'";
        }

        $cond = implode( ' AND ', $cond);

        $data = M\OrderYnpay::getOrderYnpList( $cond , $p, 'id  desc ');
        
        $this->view->data = $data;
    }
}

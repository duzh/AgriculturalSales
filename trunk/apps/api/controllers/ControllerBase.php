<?php
namespace Mdg\Api\Controllers;
use Phalcon\Mvc\Controller;
use Mdg\Models as M;
/**
 *错误代码查看
 */
class ControllerBase extends Controller
{
    /** 成功，CODE:0 */
    const SUCCESS = 0;
    /** 数据为空，CODE:10000 */
    const DATA_EMPTY = 10000;
    /** 参数错误，CODE:10001 */
    const PARAMS_ERROR = 10001;
    /** 发送验证码失败，CODE:10101 */
    const SENT_CODE_ERROR = 10101;
    /** 用户名密码错误，CODE:11001 */
    const LOGIN_ERROR = 11001;
    /** 验证码错误，CODE:10102 */
    const CODE_ERROR = 10102;
    /** 注册失败 CODE:10103  */
    const REGISTER_ERROR = 10103;
    /** 信息不完整  CODE:10104 */
    const INFO_ERROR = 10104;
    /** 此用户不存在  CODE:10105 */
    const USER_ERROR = 10105;
    /** 用户未登录 CODE:10106 */
    const NOT_LOGGED_IN = 10106;
    /** 用户信息保存失败 CODE:10107  */
    const USERS_INFO_ERROR = 10107;
    /** 原密码错误  CODE:10108 */
    const OLDPWD_ERROR = 10108;
    /** 两次密码不一致 CODE:10109 */
    const TWOPWDNOTMATCH = 10109;
    /** 不能大于供应量 不能小于起购量  CODE:10110 */
    const NOT_MAX_SELL = 10110;
    /** 不能采购自己发布的商品！ CODE:10112 */
    const NOT_SELL_SELF = 10112;
    /** 发布采购失败 CODE:11111 */
    const NEWBUY_ERROR = 11111;
    /** 发布供应失败 CODE:11112 */
    const NEWSELL_ERROR = 11112;
     /** 编辑供应失败 CODE:11127 */
    const SAVESELL_ERROR = 11127;
    /** 无权修改此供应信息 CODE:11113 */
    const NOT_SVAESELL_ERROR = 11113;
    /** 发布采购信息失败 SAVEPUR_ERROR CODE:11114 */
    const SAVEPUR_ERROR = 11114;
    /** 验证码已过期 VCODETIME_ERROR CODE:11115 */
    const VCODETIME_ERROR = 11115;
    /** 修改密码失败 VCODSAVE_ERROR CODE:11116 */
    const VCODSAVE_ERROR = 11116;
    /** 采购内容保存失败 SAVEPURCONTENT_ERROR CODE:11117 */
    const SAVEPURCONTENT_ERROR=11117;
    /** 供应内容保存失败 SAVESELLCONTENT_ERROR CODE:11118 */
    const SAVESELLCONTENT_ERROR=11118;
    /** 取消采购失败 SAVESELLCONTENT_ERROR CODE:11119 */
    const PURREMOVE_ERROR =11119;
    /** 确认采购失败 QUOTATIONORDER_ERROR CODE:11120 */
    const QUOTATIONORDER_ERROR =11120;
    /** 不是自己的订单 NOT_SELF CODE:11121 */
    const NOT_SELF =11121;
    /** 取消订单失败 ORDER_CANCEL_ERROR CODE:11122 */
    const ORDER_CANCEL_ERROR =11122;
    /** 订单操作异常 ORDER_ERROR CODE:11123 */
    const ORDER_ERROR =11123;
    /** 订单设置价格失败 ORDER_SAVEPRICE_ERROR CODE:11124 */
    const ORDER_SAVEPRICE_ERROR=11124;
    /** 订单确认收货失败 ORDER_FINISH_ERROR CODE:11126 */
    const ORDER_FINISH_ERROR=11126;
    /** 订单设置发货失败 ORDER_SEND_ERROR CODE:11125 */
    const ORDER_SEND_ERROR=11125;
    /** 供应时间不正确 STIME_ERROR=11135 */
    const STIME_ERROR=11135;
    /** 最大价格最小价格不正确 MAX_PRICE_ERROR=11136*/
    const MAX_PRICE_ERROR=11136;
    /**不能对自己的采购信息报价！ NOT_SELF_QUOTAION = 11128 */
    const NOT_SELF_QUOTAION = 11128;
     /*** 报价失败  ！ QUOTAION_ERROR = 11129 */
    const QUOTAION_ERROR = 11129;
    /** 上传图片失败 UPLOADIMG_ERROR=11130 */
    const UPLOADIMG_ERROR=11130;
    /**  图片大小不得大于2M！IMGSIZE_ERROR=11131*/
    const IMGSIZE_ERROR=11131;
    /**  无权删除此图片！NOT_DELETE_ERROR=11132 */
    const NOT_DELETE_ERROR=11132;
     /**  删除图片失败！DELETE_IMG_ERROR=11133 */
    const DELETE_IMG_ERROR=11133;
     /**  反馈建议保存失败 Feedback_ERROR=11134 */
    const Feedback_ERROR=11134;
    /**  地址要求5级  PUR_ADDRESS_ERROR=11137 */
    const PUR_ADDRESS_ERROR=11137;
    /**  用户已经存在  MOBILEEXISTENE= 11138 */
    const  MOBILEEXISTENE=11138;
    /**  手机号不存在  MOBILEEXISTENE= 11139 */
    const  MOBILE_NOT_EXISTENE=11139;

    /**   不能重复报价  MOBILEEXISTENE= 11140 */
    const  PURORDER_ERROR=11140;
    /**   此订单已取消五次  code:11142 */
    const ORDER_PAY_CANCEL_MAX = 11142;
    /**   获取U刷号失败  code:11143 */
    const GET_UPOSP_SN_ERROR = 11143;
    /**   保存U刷号失败  code:11144 */
    const SAVE_UPOSP_SN_ERROR = 11144;

    /** 数据错误, code:19999 */
    const DATA_ERROR = 19999;

    /** 返回xml解析错误, code:19998 */
    const XML_ERROR = 19998;

    /** 签名错误, code:19997 */
    const SIGN_ERROR = 19997;

    /** 订单状态错误, code:19995 */
    const ORDER_STATE_ERROR = 19995;

    /** 订单金额不能超过10000元, code:19994 */
    const ORDER_TOTAL_ERROR = 19994;
   
      /**  u刷记录不存在, code:18888 */
    const ORDER_NOT_ERROR = 18888;

      /** 交易成功, code:00 */
    const ORDER_PAY = 00;

      /** 订单尚未支付, code:01 */
    const ORDER_NOT_PAY = 01;

     /** 订单支付失败, code:02 */
    const ORDER_PAY_ERROR = 02;

    /** 订单状态异常, code:03 */
    const ORDER_STATUS_ERROR = 03;

    /** 系统异常, code:96 */
    const SYSTEM_ERROR = 96;
      /** 系统异常, code:96 */
    const PAY_STATE = 11145;
    
    /**  支付成功 code 0 */
    const  WXSUCCESS =0;
     /**  转入退款 code 20000 */
    const  WXREFUND =20000;
     /**  未支付 code 20001 */
    const  WXNOTPAY =20001;
     /**  已关闭 code 20002 */
    const  WXCLOSED=20002;
     /**  已撤销 code 20003 */
    const  WXREVOKED=20003;
     /**  用户支付中 code 20004 */
    const  WXUSERPAYING=20004;
     /**  支付失败 code 20005 */
    const  WXPAYERROR=20005;
    static $order_state = array(
        00 => '交易成功',
        01 => '订单尚未支付',
        02 => '订单支付失败',
        03 => '订单状态异常',
        96 => '系统异常',
        18888=> '没有记录'

    );
    public function getMsg($errorCode = 0, $data = array()) 
    {
        $rs = array(
            'errorCode' => $errorCode
        );
        
        if (!empty($data)) $rs['data'] = $data;
        die(json_encode($rs));
    }
    public function getJson($errorCode = 0, $data = array()) 
    {
        $rs = array(
            'status' => $errorCode
        );
        
        if (!empty($data)) $rs['data'] = $data;
        die(json_encode($rs));
    }
    /**
     * 获取登录id
     * @return [type] [description]
     */
    
    public function getUid() 
    {
        $user = $this->session->user;
        return $user ? $user['uid'] : 0;
    }
    
    /**
     * 获取手机号
     * @return [type] [description]
     */
    
    public function getMobile() 
    {
        $user = $this->session->user;
        return $user ? $user['mobile'] : 0;
    }
    public function getRealname() 
    {
        $user = $this->session->user;
        return $user ? $user['realname'] : '';
    }
    public function checkuser($user_id){
        $userext = M\UsersExt::findFirstByuid($user_id);
        $user=M\Users::findFirstByid($user_id);        
        $address=M\AreasFull::checkarea($user->areas);
        if (!isset($userext) || $userext->name == '' || $userext->areas_name == ''||!$address) 
        {
           return false;
        }else{
           return $user;
        }
    }
    public function getstatus($errorCode = 0, $data = array()) 
    {
        $rs = array(
            'status' => $errorCode
        );
        switch($errorCode) {
            case '00':
              $rs['msg']="交易成功";
                break;
            case '01':
              $rs['msg']="订单尚未支付";
                break;
            case '02':
              $rs['msg']="订单支付失败";
                break;
            case '03':
              $rs['msg']="订单状态异常";
                break;
            case '96':
              $rs['msg']="系统异常";
                break;
            case '18888':
              $rs['msg']="该订单无U刷支付记录";
                break;
            default:
              $rs['msg']="订单异常";
                break;
        }
        if (!empty($data)) $rs['data'] = $data;
        die(json_encode($rs));
    }
   
}

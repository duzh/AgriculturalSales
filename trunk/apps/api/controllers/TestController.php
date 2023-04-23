<?php
namespace Mdg\Api\Controllers;
use Phalcon\Mvc\Controller;
use Mdg\Models as M;
use Lib as L;
use Lib\Snoopy as Snoopy;
/**
 *错误代码查看
 */
class TestController extends Controller
{
   public function testAction() 
    {   

        
        $Curl = new  L\Curl();
        $data=array();
        
        $data = array(
          // 'uname'=>'15810842917',
          //  'pwd'=>'123456',
           // 'vcode'=>'328897',
           'token'=>'4j0nalppud1dcorjopn2h53ah2',
            'oid'=>'1686',
           // 'state'=>'2',
           //  // 'type'=>'3',
            // 'fuck1'=>'神风',
            // 'fuck2'=>'13521962900',
             //'keyword'=>"菠菜",
        );
          
     // // //    // $data = array(
     // // //    //    'mobile'=>'13521962900',
     // // //    //    'type'=>'reg',
     // // //    //    // 'password'=>'123456',
     // // //    //    // 'vcode'=>'933714',
     // // //    //    // 'token'=>'duu3e8ioh3d4256m0kj9lc41o2',
     // // //    //    //   //'oid'=>'475',
     // // //    //    // 'state'=>'2',
     // // //    //    //  // 'type'=>'3',
     // // //    //     // 'fuck1'=>'神风',
     // // //    //     // 'fuck2'=>'13521962900',
     // // //    //      //'keyword'=>"菠菜",
     // // //    // );

     // // // // //   //$url="http://mdgdev.ync365.com/api/upload/upfile";
     // // // // //   // $url="http://www.5fengshou.com/api";
     //cho "2";
     // $url="http://www.5fengshou.com/api/order/buy";

       
        // $data = array(
        //     // 'mobile'=>'13521962900',
        //     // 'type'=>'reg',
        //     // 'repassword'=>'123456',
        //     //  //'type'=>'findpwd',
        //     // 'vcode'=>'422644',
        //     // 'type'=>'reg',
        //     //'token'=>'gsse6kf46ta5pgk0h8eer18r94',
        // );
    //$url="http://www.5fengshou.com/api/codes/getcode";
     // //$url="http://www.5fengshou.com/api/register/save";
     $url="http://www.5fengshou.com/api/order/buyinfo";
     //    // // //完善资料
     //     $data = array(
     //         'oldpwd'=>'1234567',
     //         'pwd'=>'1234567',
     //         'repwd'=>'1234567',
     //        // 'usertype'=>'2',
     //        // 'name'=>'张三李四111李四',
     //        // 'goods'=>'小麦,白面,小麦,白面',
     //        // 'farm_areas'=>'222',
     //         //'vcode'=>'468280',
     //       'token'=>'c2i1in28qtr0mdjfh9l6f0ru04',
     //    );
     //   $url="http://www.5fengshou.com/api/login/lookusersinfo";
     //    // // // // //$data = array(
        //     //'oldpwd'=>'1234567',
            //'pwd'=>'1234567',
            //'repwd'=>'1234567',
            //'areas'=>'3516',
            //'usertype'=>'2',
            //'name'=>'张三李四111',
            //'goods'=>'小麦,白面',
            //'farm_areas'=>'222',
            // '518052'=>'13521962900',
            // 'password'=>'123456',
            // 'repassword'=>'123456',
            //  'vcode'=>'692578',
            //'token'=>'2g7ai0hrdv35rim8pnqku02l36',
        //);
        //$url="http://www.5fengshou.com/api/purchase/getList";
        // $data = array(
        //     'p'=>'1',
        //     'cate'=>'30',
        //     'area'=>'4',
        //     'sellid'=>'12',
        //     'pagesize'=>'12',
        //     'keyword'=>'333',
        //     //'areas'=>'3516',
        //    // 'usertype'=>'2',
        //     //'name'=>'张三李四111李四',
        //     //'goods'=>'小麦,白面,小麦,白面',
        //     // 'farm_areas'=>'222',
        //     // '518052'=>'13521962900',
        //     // 'password'=>'123456',
        //     // 'repassword'=>'123456',
        //     //  'vcode'=>'692578',
        //     'token'=>'7sr076otd7kqqcem31dt72cik3',
        // );
        // // $url="http://www.5fengshou.com/api/sell/selllist";
        // //$url="http://www.5fengshou.com/api/login/lookusersinfo";
        // //$url="http://www.5fengshou.com/api/codes/getcode";
        // //$url="http://www.5fengshou.com/api/codes/checkcode";
       //$url="http://www.5fengshou.com/api/login/changepwd";
        // $data = array(
        //     'uname' => '13521962900',
        //     'pwd'=>'1234567',
        // );
        // //登录
       //$url="http://www.5fengshou.com/api/login/checklogin";
       //  $data = array(
       //  'category' => 2138,
       //  'maxcategory' => 2,
       //  'goods_unit' => 3,
       //  'token' => 'nhchqhdq99mpkpjn5uj2ij6946',
       //  'version' => 1.0,
       //  'spec' =>'',
       //   'stime'=>'11',
       //   'etime'=>'12',
       //   'content' =>'',
       //   'min_number' => 5,
       //   'mincategory' => 58,
       //   'breed' => '破坏',
       //   'title' => '新加坡数学题',
       //   'areas' => 162548,
       //   'type' => 0,
       //   'address' =>1580,
       //   'max_price' => 158.0,
       //   'min_price' => 155.0,
       //   'quantity' => 5.0,
       //   );
       //  // /print_R($arr);exit;
         
       //   $data = array(
       //      //'sellid'=>'138931',
       //      'title'=>'新加坡数学题',
       //      'maxcategory' => 7,
       //      'mincategory'=>109,
       //      'min_price'=>5,
       //      'max_price'=>7,
       //      'goods_unit'=>1,
       //      'quantity'=>100,
       //      'min_number'=>100,
       //      'areas'=>53770,
       //      'address'=>'万达广场9号楼',
       //      'stime'=>21,
       //      'etime'=>91,
       //      'spec'=>'爱',
       //      'breed'=>"小麦派",
       //      'content'=>'腓肠肌',
       //      'token'=>"vkcs7iv221eteg61b1g0nm11c0",
       //  );
       // $url="http://www.5fengshou.com/api/sell/create";
        
       // //  // // // //发布采购 
        //  $data =array(
        //     "title"=>"花生22",
        //     "quantity"=> '5000',
        //     "goods_unit"=> "1",
        //     "endtime"=> "2015-04-30",
        //     "content"=>"12456",
        //     'pid'=>'1908',
        //     // 'pagesize'=>'',
        //     // 'p'=>'',
        //     // 'state'=>'1',
        //     'token'=>"stegop7e9ahr3o0lcnp0mdoll1"
        // );
        // //确认采购
       
        // //  $data = array(
        // //     'sellid'=>'947',
        // //     'areas'=>'163414',
        // //     'purname'=>'张三',
        // //     'quantity'=>'5',
        // //     'purphone'=>'18600413520',
        // //     'address'=>'',
        // //     'token'=>"stegop7e9ahr3o0lcnp0mdoll1"
        // // );
        // //报价第一步
        // // $data =array(
        // //     "pid"=>"1869",
        // //     'token'=>'stegop7e9ahr3o0lcnp0mdoll1',
        // // );
        // //  $data =array(
        // //     // "spec"=>"dsadadada",
        // //     "sellname"=> "大豆",
        // //     "sareas"=> "47381",
        // //     "sphone"=> "15101515776",
        // //     "price"=>"12456",
        // //     "purid"=> 1926,
        // //     "saddress"=> "北京市,市辖区,朝阳区,建外街道办事处,光华里社区居委会"
        // // );

        // // //确认报价
        // // $data =array(
        // //     //"quoid"=>"89",
        // //     'sellid'=>'138931',
        // //     'token'=>'stegop7e9ahr3o0lcnp0mdoll1',
        // // );
        // //报价第二步
        // //供应详情
        // //
        // //$url="http://www.5fengshou.com/api/purchase/savepur";
        // //我买的订单
        // $data =array(
        //     'type'=>'2',

        //    'token'=>'stegop7e9ahr3o0lcnp0mdoll1',
        // );
        // $url="http://www.5fengshou.com/api/index/main";
        // // $data =array(
        // //     "spec"=>"是大大大大大",
        // //     "sellname"=> "大豆",
        // //     "sareas"=> "47381",
        // //     "sphone"=> "15101515776",
        // //     "price"=>"12456",
        // //     "purid"=> 1926,
        // //     "saddress"=> "北京市,市辖区,朝阳区,建外街道办事处,光华里社区居委会"
        // // );
        // //  $data =array(
        // //     "title"=>"花生11",
        // //     "quantity"=> '500',
        // //     "goods_unit"=> "1",
        // //     "endtime"=> "2015-04-30",
        // //     "content"=>"12456",
        // //     'pid'=>'1908',
        // //     'token'=>"8rtaf1es2ijgtsornohl9onnf4"
        // // );
        
        // // $data = array(
        // //     //'quoid'=>'90',
        // //     // 'areas'=>'47381',
        // //     // 'purname'=>'刘达1',
        // //     // 'quantity'=>'2',
        // //     // 'purphone'=>'15101515776',
        // //     //'pagesize'=>'15',
        // //     'quantity'=>'20222222',
        // //     'sellid'=>'8',   
        // //     'token'=>"hunvbtmdonrv9591oj2a25kl23"
        // // );
        // // $data =array(
        // //     "pid"=>"1869",
        // //     'token'=>'879pq1do5589vuueio4jonf134',
        // );
        //print_r($data);die;
        //$url="http://www.5fengshou.com/api/quotation/lookquo";
       
        $data = $Curl->post($url,$data);
        print_R($data);
        exit;
        $data = $this->createOfferAction();
    }
   /**
     * 测试接口
     * @return [type] [description]
     */
    public function test1Action() 
    {   
       
        $Curl = new  L\Curl();
        $data=array();


        $data = array(
            'oid'=>'432',
            // 'pagesize'=>'15',
            // 'state'=>'6',
            //'price'=>'22222222',
            //'fahuo'=>'2',
            // 'driver_name'=>'张三',
            // 'driver_phone'=>'13521962900',
            'token'=>"hvi2baiq73916kafemvrm39un4"
        );
     
        //print_r($data);die;
        $url="http://www.5fengshou.com/api/order/finish/";

        $data = $Curl->post($url,$data);
        print_R($data);
        exit;
        $data = $this->createOfferAction();
    }
    /**
     * 测试接口
     * @return [type] [description]
     */
    public function test2Action() 
    {   
       
        $Curl = new  L\Curl();
        $data=array();
       
        $data = array(
             'mobile'=>'13241754050',
             // 'password'=>'123456',
             // 'usertype'=>1,
             'vcode'=>'463453',
            //'type'=>'reg',
        );
        //$url="http://www.5fengshou.com/api/codes/getcode";
        $url="http://www.5fengshou.com/api/login/resetpwd";

        $data = $Curl->post($url,$data);
        print_R($data);
        exit;
        $data = $this->createOfferAction();
    }


      /**
     * 测试接口
     * @return [type] [description]
     */
    public function selltestAction() 
    {   
       
        $Curl = new  L\Curl();
        $data=array();
        $data = array(
            //'uname' => '18610308404',
            //'pwd'=>'1234567',
            'quoid'=>'65',
            'token'=>'nsdphfuj6js43emoucj27sa200',
        );
        // $data = array(
        //     'uname' => '13161445972',
        //     'pwd'=>'123123',
        // );
        // $data = array(
        //     'uname' => '13555260444',
        //     'pwd'=>'123456',
        // );
        // create&
        //areas=191268&breed=&content=&goods_unit=2&title=776&maxcategory=21&spec=&mincategory=182&quantity=5.0&min_price=2.0&min_number=5&max_price=5.0
        $url="http://mdgdev.ync365.com/api/quotation/purchaseorder";
       // &etime=51&goods_unit=4&title=356怪怪的&maxcategory=18&stime=41&spec=&mincategory=159&quantity=2.0&min_price=2.0&min_number=2&max_price=5.0
        // $data = array(
        //     'p' => 2,
        //     'area'=>'1',
        //     'token'=>"1nmfs3jbt3k8qid7ih6ru4kpl6",
        //     'cate'=>'',
        // );
        // $url="http://www.5fengshou.com/api/sell/selllist";
        // $data = array(
        //     //'sellid' => 8280,
        //     // 'area'=>'1',
        //     //'token'=>"1nmfs3jbt3k8qid7ih6ru4kpl6222222222",
        //     // 'cate'=>'',
        // );
        //create
        // print_r($this->session->user);die;
         //$data = array(
           // 'sellid'=>'138904'
            //'title'=>'356怪怪的',
            // 'maxcategory' => 18,
            // 'mincategory'=>"159",
            // 'min_price'=>2,
            // 'max_price'=>5.0,
            // 'goods_unit'=>2,
            // 'qutailty'=>2.0,
            // 'min_number'=>2,
            // 'areas'=>'191268',
            // 'stime'=>41,
            // 'etime'=>123,
            // 'spec'=>'sdad',
            // 'content'=>'hdsajha',
            // 'token'=>"20ae61ao1lqd7duakr3sanboj4"
        //);
     
        //print_r($data);die;
        //$url="http://www.5fengshou.com/api/sell/sellinfo";

        $data = $Curl->post($url,$data);
        print_R($data);
        exit;
        $data = $this->createOfferAction();
    }
    public function zhuaquAction(){
                
                    $arr=file_get_contents("http://wuliu.ymt.com");
                    print_r($arr);die;
                    $snoopy = new Snoopy; 
                    $snoopy->fetch("http://wuliu.ymt.com/");

                    if ($snoopy->results) 
                    { 
                    //获取连接地址 
                    //$snoopy->fetchlinks("http://wuliu.ymt.com/"); 
                    $url=array(); 
                    $url=$snoopy->results; 

                    print_r($url);die;
                    foreach ($url as $key=>$value) 
                    { 
                    //匹配http://www.phpchina.com/bbs/forumdisplay.php?fid=156&sid=VfcqTR地址即论坛板块地址 
                    if (!preg_match("/^(http:\/\/www\.phpchina\.com\/bbs\/forumdisplay\.php\?fid=)[0-9]*&sid=[a-zA-Z]{6}/i",$value)) 
                    { 
                    unset($url[$key]); 
                    } 
                    } 
                    //print_r($url); 
                    //获取到板块数组$url，循环访问，此处获取第一个模块第一页的数据 
                    $i=0; 
                    foreach ($url as $key=>$value) 
                    { 
                    if ($i>=1) 
                    { 
                    //测试限制 
                    break; 
                    } 
                    else 
                    { 
                    //访问该模块，提取帖子的连接地址，正式访问里需要提取帖子分页的数据，然后根据分页数据提取帖子数据 
                    $snoopy=new Snoopy(); 
                    $snoopy->fetchlinks($value); 
                    $tie=array(); 
                    $tie[$i]=$snoopy->results; 
                    //print_r($tie); 
                    //转换数组 
                    foreach ($tie[$i] as $key=>$value) 
                    { 
                    //匹配http://www.phpchina.com/bbs/viewthread.php?tid=68127&amp; extra=page%3D1&amp;page=1&sid=iBLZfK 
                    if (!preg_match("/^(http:\/\/www\.phpchina\.com\/bbs\/viewthread\.php\?tid=)[0-9]*&amp;extra=page\%3D1&amp;page=[0-9]*&sid=[a-zA-Z]{6}/i",$value)) 
                    { 
                    unset($tie[$i][$key]); 
                    } 
                    } 
                    //print_r($tie[$i]); 
                    //归类数组，将同一个帖子不同页面的内容放一个数组里 
                    $left='';//连接左边公用地址 
                    $j=0; 
                    $page=array(); 
                    foreach ($tie[$i] as $key=>$value) 
                    { 
                    $left=substr($value,0,52); 
                    $m=0; 
                    foreach ($tie[$i] as $pkey=>$pvalue) 
                    { 
                    //重组数组 
                    if (substr($pvalue,0,52)==$left) 
                    { 
                    $page[$j][$m]=$pvalue; 
                    $m++; 
                    } 
                    } 
                    $j++; 
                    } 
                    //去除重复项开始 
                    //$page=array_unique($page);只能用于一维数组 
                    $paget[0]=$page[0]; 
                    $nums=count($page); 
                    for ($n=1;$n <$nums;$n++) 
                    { 
                    $paget[$n]=array_diff($page[$n],$page[$n-1]); 
                    } 
                    //去除多维数组重复值结束 
                    //去除数组空值 
                    unset($page); 
                    $page=array();//重新定义page数组 
                    $page=array_filter($paget); 
                    //print_r($page); 
                    $u=0; 
                    $title=array(); 
                    $content=array(); 
                    $temp=''; 
                    $tt=array(); 
                    foreach ($page as $key=>$value) 
                    { 
                    //外围循环，针对一个帖子 
                    if (is_array($value)) 
                    { 
                    foreach ($value as $k1=>$v1) 
                    { 
                    //页内循环，针对一个帖子的N页 
                    $snoopy=new Snoopy(); 
                    $snoopy->fetch($v1); 
                    $temp=$snoopy->results; 
                    //读取标题 
                    if (!preg_match_all("/ <h2>(.*) <\/h2>/i",$temp,$tt)) 
                    { 
                    echo "no title"; 
                    exit; 
                    } 
                    else 
                    { 
                    $title[$u]=$tt[1][1]; 
                    } 
                    unset($tt); 
                    //读取内容 
                    if (!preg_match_all("/ <div id=\"postmessage_[0-9]{1,8}\" class=\"t_msgfont\">(.*) <\/div>/i",$temp,$tt)) 
                    { 
                    print_r($tt); 
                    echo "no content1"; 
                    exit; 
                    } 
                    else 
                    { 
                    foreach ($tt[1] as $c=>$c2) 
                    { 
                    $content[$u].=$c2; 
                    } 
                    } 
                    } 
                    } 
                    else 
                    { 
                    //直接取页内容 
                    $snoopy=new Snoopy(); 
                    $snoopy->fetch($value); 
                    $temp=$snoopy->results; 
                    //读取标题 
                    if (!preg_match_all("/ <h2>(.*) <\/h2>/i",$temp,$tt)) 
                    { 
                    echo "no title"; 
                    exit; 
                    } 
                    else 
                    { 
                    $title[$u]=$tt[1][1]; 
                    } 
                    unset($tt); 
                    //读取内容 
                    if (!preg_match_all("/ <div id=\"postmessage_[0-9]*\" class=\"t_msgfont\">(.*) <\/div>/i",$temp,$tt)) 
                    { 
                    echo "no content2"; 
                    exit; 
                    } 
                    else 
                    { 
                    foreach ($tt[1] as $c=>$c2) 
                    { 
                    $content[$u].=$c2; 
                    } 
                    } 
                    } 
                    $u++; 
                    } 
                    print_r($content); 
                    } 
                    $i++; 
                    } 
                    } 
                    else 
                    { 
                    echo "login failed"; 
                    exit; 
                    } 
    }

}

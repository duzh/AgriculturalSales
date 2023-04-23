<?php
var_dump( selladd::excal());
class selladd {

    public $uid='0';                  
    public $title='';           //产品标题        
    public $max_category='';    //大分类           
    public $min_category='';    //小分类           
    public $min_price='';       //最低价格        
    public $max_price='';      // 最高价格        
    public $quantity='' ;      // 数量              
    public $goods_unit='' ;    // 数量单位        
    public $province='';       // 省                 
    public $city='';           // 市                 
    public $town='';           //区                 
    public $stime='';          //开始时间        
    public $etime='';           //结束时间        
    public $breed='';          // 种类              
    public $spec='';           // 规格              
    public $endtime='';         //报价截至时间  
    public $purmoblie='';      //最后更新时间  
    public $purname='';        // 用户姓名        
    public $sellmoblie='';      //供应编号        
    public $sellname='';     //联系方式        
    public $address='';      //详细地址        
    public $content='';      //秒诉
    public $type='';         //类型
	 public static function excal(){
        // //$excal=''
        
        $excal="D:/Classes/excel/sell2.xls";
        $excalpath=strtolower(trim(substr(strrchr($excal, '.'), 1)));
        $xlsPath=$excal;
        if($excalpath=='xlsx'){
            $type = 'Excel2007';
        }else{
             $type = 'Excel5';
        }
        include_once 'D:/Classes/PHPExcel.php';         
        include_once 'D:/Classes/PHPExcel/IOFactory.php';
        $xlsReader = \PHPExcel_IOFactory::createReader($type);  
        
        $xlsReader->setReadDataOnly(true);
        $xlsReader->setLoadSheetsOnly(true);
        $Sheets = $xlsReader->load($xlsPath);
        
        //开始读取
        $eet = $Sheets->getSheet(0)->toArray(); 
        
        if($eet[1][0]!="供应信息收集表"){
            unlink ($excel);
        }
        $sql="select * from reg where type=0  ";
        $arr = self::query($sql);

        if($arr){
            $sql2="delete from reg where type=0";
            self::exec($sql2);
        }

        foreach ($eet as $key => $value) {
           if($key>4&&$value[0]!=""){  

               sleep(2);
               echo $key;
                self::checksell($value);
            }else{
                $error[]=$value;
            }
        
        }
        $zong=count($eet)-count($error);
        unlink($excal);
        echo $zong;
    }
     static public function checksell($arr){
             
            if(!empty($arr)){
              $title      = self::title($arr[0]);
              
              
              $scate      =self::parseCategory($arr[1],$arr[2]);
              var_dump($scate);die;
              $max_price  = self::quantity($arr[4]);
              $min_price  = self::quantity($arr[3]);
              $quantity   = self::quantity($arr[5]);   //供应量
              $goods_unit = self::goods_unit($arr[6]); //供应单位
              $stime      = self::stime($arr[7]);
              $etime      = self::stime($arr[8]);
              $bread      = self::title($arr[9]);     //品种 
              $spec       = self::title($arr[10]);
              $content    = self::title($arr[11]);
              $sellname   = self::title($arr[12]);
              $sellmoblie = self::moblie($arr[13]);
              $areas      = Areas::parseAreas($arr[14],$arr[15],$arr[16]);
              $address    = self::title($arr[17]);
               if($title&&$scate&&$max_price&&$min_price&&$quantity&&$goods_unit&&$stime&&$etime&&$bread&&$spec&&$content&&$sellname&&$sellmoblie&&$areas&&$address){
                
                $area_name=Func::getCols(Areas::getFamily($areas), 'area_name', ',');
                $userid=self::reguser($sellmoblie,'123456','1',$areas,$area_name,$address,$sellname,$title); 
                if($userid){


                  
                    //插入采购表
                    $cur_time = time();
                    $sell = new Sell();
                    $title      = $title;
                    $category   = $scate;
                    $max_price  = $max_price;
                    $min_price  = $min_price;
                    $stime      = $stime;
                    $etime      = $etime;
                    $quantity   = $quantity;
                    $goods_unit = $goods_unit;
                    $areas      = $areas;
                    $areas_name = Func::getCols(Areas::getFamily($areas), 'area_name', ',');
                    $address    = $address;
                    $bread      = $bread;
                    $spec       = $spec;
                    $uid        = $userid;
                    $state      = 0;
                    $uname      = $sellname;
                    $mobile     = $sellmoblie;
                    $createtime = $updatetime = $cur_time;
                   

                    $sql4="insert into sell (title,category,max_price,min_price,stime,etime,quantity,goods_unit,areas,areas_name,address,bread,spec,uid,
                        state,uname,mobile,createtime,sell_sn)values($title,$category,$max_price,$min_price,$stime,$etime,$quantity,$goods_unit,$areas,$areas_name,
                        $address,$bread,$spec,$uid,$state,$uname,$mobile,$createtime,$sell_sn)";

                    $sellid=self::exec($sql4);
                    print_r($sellid);die;
                    
                   
                    $sell_sn = sprintf('SELL%010u', $sell->id);

                    $sql5="update sell set sell_sn where id=";
                    $scontent = new SellContent();
                    $scontent->sid     = $sell->id;
                    $scontent->content = $content;
                    $scontent->save();
            
                }    
                
              }else{
                    
                    $reg= new Reg(); 
                    $reg->title        = $arr[0];
                    $reg->max_category = $arr[1];
                    $reg->min_category = $arr[2];
                    $reg->min_price    = $arr[3];
                    $reg->max_price    = $arr[4];
                    $reg->quantity     = $arr[5];
                    $reg->goods_unit   = $arr[6];
                    $reg->stime        = $arr[7];  
                    $reg->etime        = $arr[8];
                    $reg->breed        = "{$arr[9]}";
                    $reg->spec         = $arr[10];
                    $reg->content      = $arr[11];
                    $reg->sellname     = $arr[12];
                    $reg->sellmoblie   = $arr[13];
                    $reg->province     = $arr[14];
                    $reg->city         = $arr[15];
                    $reg->town         = $arr[16];
                    $reg->address      = $arr[17];
                    $reg->type         = '0';
                    if(!$reg->save()){
                        return false;
                    }
                   
            }  
        }
    }
    static function parseCategory($first, $second) {
        $first = trim($first);
        $second = trim($second);
       
        $firstInfo=self::query("select * from Category where title='$first'");
        print_r($firstInfo);die;
       // $firstInfo = self::findFirstBytitle($first);
        if(!$firstInfo) {
            $firstInfo1=self::exec("insert into Category (title)values('$first')");
            if(!$firstInfo) {
                return false;
            }
        }
        $where = "title = '{$second}' and parent_id = '".$firstInfo[0]['id']."'";
        
        $secInfo = self::query("select * from Category where $where ");
        
        if(!$secInfo) {
            $secInfo=self::exec("insert into Category (parent_id,title)values($id,'$second')");
            if(!$secInfo) {
                return false;
            }
        }

        return $secInfo[0]['id'];
    }

    //检测时间并转换
    static function checktime($time){
        $t=$time;
        $n = intval(($t - 25569) * 3600 * 24); //转换成1970年以来的秒数
        return gmdate('Y-m-d H:i:s', $n);
    }
    //去除字符串空格
    static function strTrim($str)
    {
        return trim($str);
    }
    //验证商品名称
    static function title($str)
    {
        $str=self::strTrim($str);
        if(!empty($str)){
            return $str;
        }else{
            return false;
        }
    }
    static function stime($str){
        $str=self::strTrim($str);
        $arr=Sell::$type;
        if(!empty($str)&&in_array($str,$arr))
        {  
           $arr1=array_flip($arr);
           return $arr1[$str];
        }else{
            return false;
        }
    }
    //验证采购数量
    static function quantity($str)
    {   
        $str=self::strTrim($str);
        if(!empty($str)&&is_numeric($str))
        {  
            return $str;
        }else{
            return false;
        }
    }
    //验证采购单位
    static function goods_unit($str){
        $str=self::strTrim($str);
        $arr = array(
            1 => '公斤',
            2 => '吨',
            3 => '头',
        );
        if(!empty($str)&&in_array($str,$arr))
        {   
           return Purchase::$arr[$str];
        }else{
            return false;
        }
    }
    //验证手机号
    static function moblie($moblie){
        $regex = '/1[3458]{1}\d{9}$/';
        if(!empty($moblie)&&preg_match($regex,$moblie)){
            return $moblie;
        }else{
            return false;
        }
    }
    //插入会员
    static function reguser($username,$password,$type,$areas,$areas_name,$address,$name='-',$goods){
           include_once 'D:/Classes/PHPExcel.php';
           $member = new Member();
           $synuser = $member->register(trim($username),$password);
           var_dump($synuser);
            //如果平台注册成功
           if($synuser){
                //查看卖的贵的信息是否有  如果有 就直接取id  如果没有  注册
                $user=Users::findFirst("username='{$username}'");
                $user=self::query("select * from users where usrename='{$username}'");
       
                if($user){
                    $userid=$user->id;
                }else{
             

                    $salt  = Auth::random(6,1);
                    $users = new Users();
                    $users->id            = $synuser['id'];
                    $users->username      = trim($username);
                    $users->usertype      = $type;
                    $users->regtime       = time();
                    $users->regip         = Utils::getIP();
                    $users->lastlogintime = time();
                    $users->areas = $areas;
                    $users->encodePwd($password,$salt);
                    $users->save();
                    
                    $ext = new UsersExt();
                    $ext->uid = $synuser['id'];
                    $ext->name       = $name;
                    $ext->areas_name = $areas_name;
                    $ext->address    = $address;
                    $ext->goods      = $goods;
                    $ext->save();
                    if($ext->save()){
                        return $users->id;
                    }
                }
           }else{
                $synuser = $member->getMember(trim($username));
                $salt  = Auth::random(6,1);
                $users = new Users();
                $users->id            = $synuser['user_id'];
                $users->username      = trim($username);
                $users->usertype      = $type;
                $users->regtime       = time();
                $users->regip         = Utils::getIP();
                $users->lastlogintime = time();
                $users->areas = $areas;
                $users->encodePwd($password,$salt);
                $users->save();
                
                $ext = new UsersExt();
                $ext->uid = $synuser['user_id'];
                $ext->name       = $name;
                $ext->areas_name = $areas_name;
                $ext->address    = $address;
                $ext->goods      = $goods;
                $ext->save();
                if($ext->save()){
                    return $users->id;
                }

           }
    }
    public static function query($sql){
        $db = self::getDb();
        $rs =  $db->query($sql);
        if(is_object($rs)){
            return $rs->FetchAll(); 
        }
        return array();
    }
    public static function exec($sql){

        $db = self::getDb();

        return $db->exec($sql);
        
    }   
    public static function getDb(){

    $pdo  = new PDO(sprintf('%s:host=%s;dbname=%s', 'mysql', '127.0.0.1', 'maidegui') , 'root', 'root', array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_CASE => PDO::CASE_LOWER,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT=>false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
        ));
    return $pdo;

    }

}
include_once "Hprose/HproseHttpClient.php";
class Member{
    private $_url = 'http://uwebservice.ync365.com/member.php';
    private $client = null;

    public function __construct(){
        $this->client = new \HproseHttpClient($this->_url);
    }

    /**
     * 用户注册
     * @param  string $mobile 手机号
     * @param  string $pwd    密码
     * @return array
     */
    public function register($mobile='',$pwd=''){
        $user = $this->client->Member_Register($mobile,$pwd);

        return $user;
    }

    /**
     * 验证登录
     * @param  string $mobile 手机号
     * @param  string $pwd    密码
     * @return Array
     */
    public function validateLogin($mobile='',$pwd=''){
        $user = $this->client->Member_ValidateLogin($mobile,$pwd);
        return $user;
    }

    /**
     * 修改密码
     * @param  string $mobile 手机号
     * @param  string $pwd    密码
     * @return boolean
     */
    public function changePWD($mobile='',$newpwd='',$oldpwd='',$need=true){
        return $this->client->Member_changePWD($mobile,$newpwd,$oldpwd,$need);
    }

    public function checkMember($mobile=''){
        return $this->client->Member_checkMobile($mobile);
    }

    public function getMember($mobile){
        return $this->client->Member_getMember($mobile);
    }
}
?>
<?php
namespace Mdg\Models;
class UsersExt extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $uid=0;

    /**
     *
     * @var string
     */
    public $name=' ';

    /**
     *
     * @var string
     */
    public $address=' ';

    /**
     *
     * @var string
     */
    public $goods=' ';

    /**
     *
     * @var string
     */
    public $areas_name=' ';

     /**
     *
     * @var string
     */
    public $user_id;


    /**
     *
     * @var double
     */
    public $farm_areas='';
	
	/**
     *
     * @var integer
     */
    public $publish_set=0;
	
    static function getaddress($uid=0, $isareas= false) {
      $useraddress= self::findFirstByuid($uid);
      if($isareas) {
        return $useraddress?$useraddress->areas_name:"";  
      }
      return $useraddress?$useraddress->address:"-";
    }
    static function getuserext($uid) {
      $user= self::findFirstByuid($uid);
      if(!$user){
        $ext=new self();
        $ext->uid=$uid;
        $ext->save();
      }
      return $user;
    }
    static function getusername($uid) {
          $user= self::findFirstByuid($uid);
          if(!$user){
             return "";
          }else{
              return $user->name;
          }
    }
    static function getPath($uid){
          $user= self::findFirstByuid($uid);
         
          if($user&&$user->picture_path&&$user->picture_path!=' '){
             return IMG_URL.$user->picture_path;
          }else{
             return 0; 
          }
    }

}

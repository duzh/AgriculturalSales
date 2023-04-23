<!DOCTYPE HTML>
<html>
 <head>
  <title> 丰收汇后台管理系统</title>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <link href="/bui/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="/bui/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   <link href="/bui/assets/css/main.css" rel="stylesheet" type="text/css" />
 </head>
 <body>
   <div class="header">
    <div class="dl-title"><span class="">丰收汇后台管理系统</span></div>
    <div class="dl-log">欢迎您，{{ session.adminuser['name'] }}<a href="/manage/login/logout/" title="退出系统" class="dl-log-quit">[退出]</a>
    </div>
   </div>
   <div class="content">
    <div class="dl-main-nav">
      <ul id="J_Nav"  class="nav-list ks-clear">
        <li class="nav-item dl-selected"><div class="nav-item-inner nav-home">主菜单</div></li>
       <!--  <li class="nav-item"><div class="nav-item-inner nav-order">系统管理</div></li>
        <li class="nav-item"><div class="nav-item-inner nav-inventory">会员管理</div></li>
        <li class="nav-item"><div class="nav-item-inner nav-supplier">订单管理</div></li>
        <li class="nav-item"><div class="nav-item-inner nav-supplier">订单管理</div></li>
        <li class="nav-item"><div class="nav-item-inner nav-supplier">订单管理</div></li>
        <li class="nav-item"><div class="nav-item-inner nav-supplier">订单管理</div></li>
        <li class="nav-item"><div class="nav-item-inner nav-supplier">订单管理</div></li>
        <li class="nav-item"><div class="nav-item-inner nav-supplier">订单管理</div></li>
        <li class="nav-item"><div class="nav-item-inner nav-supplier">订单管理</div></li>
        <li class="nav-item"><div class="nav-item-inner nav-supplier">订单管理</div></li>
        <li class="nav-item"><div class="nav-item-inner nav-supplier">订单管理</div></li> -->

      </ul>
    </div>
    <ul id="J_NavContent" class="dl-tab-conten">
 
    </ul>
      
   </div>
  <script type="text/javascript" src="/bui/assets/js/jquery-1.8.1.min.js"></script>
  <script type="text/javascript" src="/bui/assets/js/bui-min.js"></script>
  <script type="text/javascript" src="/bui/assets/js/config-min.js"></script>
  <script>
 
       BUI.use('common/main',function(){

          var config = [{{row}}];
          new PageUtil.MainPage({
            modulesConfig : config,
            
          });
        });
  </script>
 </body>
</html>
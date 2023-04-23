<?php
if($this->view->getControllerName() == 'index1'):
?>
<!DOCTYPE HTML>
<html>
<head>

    <script>
        var _hmt = _hmt || [];
        (function() {
          var hm = document.createElement("script");
          hm.src = "//hm.baidu.com/hm.js?e8c48a2b64e9521ae07d0f67cdd9f867";
          var s = document.getElementsByTagName("script")[0]; 
          s.parentNode.insertBefore(hm, s);
        })();
        </script>
        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>卖的贵后台管理中心</title>
    <link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/default.css" />
    <link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}/easyui/themes/metro-green/easyui.css" />
    <link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}/easyui/themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
    <script type="text/javascript" src="{{ constant('JS_URL') }}easyui/jquery.min.js"></script>
    <script type="text/javascript" src="{{ constant('JS_URL') }}easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="{{ constant('JS_URL') }}easyui/outlook2.js"></script>
    
    <script type="text/javascript">
    var _menus = {{menu}};
    //设置登录窗口
    function openPwd() {
        $('#w').window({
            title: '修改密码',
            width: 300,
            modal: true,
            shadow: true,
            closed: true,
            height: 160,
            resizable: false
        });
    }
    //关闭登录窗口
    function close() {
        $('#w').window('close');
    }

    //修改密码
    function serverLogin() {
        var $newpass = $('#txtNewPass');
        var $rePass = $('#txtRePass');

        if ($newpass.val() == '') {
            msgShow('系统提示', '请输入密码！', 'warning');
            return false;
        }
        if ($rePass.val() == '') {
            msgShow('系统提示', '请在一次输入密码！', 'warning');
            return false;
        }

        if ($newpass.val() != $rePass.val()) {
            msgShow('系统提示', '两次密码不一至！请重新输入', 'warning');
            return false;
        }

        $.post("/manage/login/changepwd/"+$newpass.val(), function(msg) {
      
           msgShow('系统提示', '恭喜，密码修改成功！<br>您的新密码为：' + msg, 'info');
            $newpass.val('');
            $rePass.val('');
            close();
        });
    }

    $(function() {
        openPwd();
        $('#editpass').click(function() {
            $('#w').window('open');
        });

        $('#btnEp').click(function() {
            serverLogin();
        });

        $('#loginOut').click(function() {
            $.messager.confirm('系统提示', '您确定要退出本次登录吗?', function(r) {

                if (r) {
                    location.href = '/manage/login/logout';
                }
            });
        });
    });
    </script>
    <style>
    .easyui-accordion ul li div{border:1px solid #fff}
    </style>
</head>
<body class="easyui-layout" style="overflow-y: hidden"  scroll="no">
    <noscript>
        <div style=" position:absolute; z-index:100000; height:2046px;top:0px;left:0px; width:100%; background:white; text-align:center;">
            <img src="images/noscript.gif" alt='抱歉，请开启脚本支持！' />
        </div>
    </noscript>
    <div region="north" border="false" style="overflow: hidden; height: 164px;
        background: url(http://static.ync365.com/mdg/images/top_bg.png) no-repeat center 0;">
        <span style="float:right; padding-right:20px;padding-top:20px;" class="head">
             {{ session.adminuser['name'] }}
            <!-- <a href="#" id="editpass">修改密码</a> -->
            <a href="/manage/login/logout" id="loginOut">安全退出</a>
        </span>
        <span style="padding-left:10px; font-size: 16px; ">
            <img src="http://static.ync365.com/mdg/images/a_logo.png" style="margin-top: 50px;margin-left: 30px;"  align="absmiddle" />
            
        </span>
    </div>
    <div region="south" split="true" style="height: 30px; background: #e5f0c9; ">
        <div class="footer"></div>
    </div>
    <div region="west" split="true" title="导航菜单" style="width:180px;" id="west">
        <div id="easyui-accordion" fit="true" border="false"></div>
    </div>
    <div id="mainPanle" region="center" style="background: #eee; overflow-y:hidden">
        <div id="tabs" class="easyui-tabs"  fit="true" border="false" >
            <iframe id="mainFrame" name="mainFrame" src="users/info" width="100%" height="100%"  frameborder="0"></iframe>
        </div>
    </div>
</body>
<script>
    function changeUrl(url) {
        $('#mainFrame').attr('src', url);
    }
</script>

</html>

<?php
else:
?>



<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>卖的贵后台管理中心</title>
<?php $dev=DEV_MODE?>
{% if dev =='master'%}
    <link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/default.css" />
    <link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}/easyui/themes/metro-green/easyui.css" />
    <link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}/easyui/themes/icon.css" />
    <script type="text/javascript" src="{{ constant('JS_URL') }}easyui/jquery.min.js"></script>
    <script type="text/javascript" src="{{ constant('JS_URL') }}easyui/jquery.easyui.min.js"></script>
    <!--时间插件-->
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}DatePicker/DatePicker/WdatePicker.js"></script>
    <!--下拉列表-->
     <script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
     <!--文章分类-->

    <link rel="stylesheet" href="{{ constant('JS_URL') }}zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
    <script type="text/javascript" src="{{ constant('JS_URL') }}zTree/js/jquery.ztree.core-3.5.min.js"></script>
    <script type="text/javascript" src="{{ constant('JS_URL') }}zTree/js/jquery.ztree.excheck-3.5.min.js"></script>

    <!--valdate验证-->
    <link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
    <script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
    <script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>

    <script type="text/javascript" src="/uploadify/jquery.uploadify.min.js" ></script>
    <link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">

    <script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>

            <!-- 添加商品
    // <script type="text/javascript" src="http://static.ync365.com/sc/js/jquery.idTabs.min.js"></script>
    // <script type="text/javascript" src="http://static.ync365.com/sc/js/select-ui.min.js"></script> -->
    <!-- 消息管理 -->
    <!-- <link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/service.css" /> -->
    <link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
    <!--物流批量删除 -->
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/form.js"></script>
{% else %}
    <link rel="stylesheet" type="text/css" href="/mdgtest/manage/css/default.css" />
    <link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}/easyui/themes/metro-green/easyui.css" />
    <link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}/easyui/themes/icon.css" />
    <script type="text/javascript" src="{{ constant('JS_URL') }}easyui/jquery.min.js"></script>
    <script type="text/javascript" src="{{ constant('JS_URL') }}easyui/jquery.easyui.min.js"></script>
    <!--时间插件-->
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}DatePicker/DatePicker/WdatePicker.js"></script>
    <!--下拉列表-->
     <script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
     <!--文章分类-->

    <link rel="stylesheet" href="{{ constant('JS_URL') }}zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
    <script type="text/javascript" src="{{ constant('JS_URL') }}zTree/js/jquery.ztree.core-3.5.min.js"></script>
    <script type="text/javascript" src="{{ constant('JS_URL') }}zTree/js/jquery.ztree.excheck-3.5.min.js"></script>

    <!--valdate验证-->
    <link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
    <script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
    <script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>

    <script type="text/javascript" src="/uploadify/jquery.uploadify.min.js" ></script>
    <link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">

    <script type="text/javascript" src="/mdgtest/js/inputFocus.js"></script>

            <!-- 添加商品
    // <script type="text/javascript" src="http://static.ync365.com/sc/js/jquery.idTabs.min.js"></script>
    // <script type="text/javascript" src="http://static.ync365.com/sc/js/select-ui.min.js"></script> -->
    <!-- 消息管理 -->
    <!-- <link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/service.css" /> -->
    <link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
    <!--物流批量删除 -->
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/form.js"></script>
{% endif %}
        

</head>
<body>
    {{ content() }}
</body>
</html>

<?php
endif;
?>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>
<script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/jquery/jquery.form.js"></script>
<body >
<style>ul li {line-height: 37px;}</style>
<style>
*{ margin:0; padding:0;}
a{ text-decoration:none;}

.alert_tips{  background:#fff; overflow:hidden; margin:0 auto; font:12px '微软雅黑';}
.alert_tips div{ text-align:center; color:#333; margin-top:30px;}
.alert_tips a{ display:block; width:90px; height:30px; background:#69AE6C; line-height:30px; text-align:center; color:#fff; margin:40px auto;}
.alert_tips a:hover{ background:#548C57;}
</style>
<!-- 报价弹框 start -->
<div class="dialog" style="width:300px;height:200px" id="dialog">

    <ul>

        <li>
            <span class="label"></span> <em>申请成功 <i id="c_spec"></i></em> 
        </li>

    </ul> <em><font><div class="btn">
          
                <input class="submit_btn" id='butto' type="button" value="发布采购" onclick="closeall()" >
                <input class="submit_btn" type="button" value="继续看货" onclick="closelog()"></div></font> </em> 

</div>
<div id="__if72ru4sdfsdfrkjahiuyi_once" style="display:none;"></div>
<div id="__hggasdgjhsagd_once" style="display:none;"></div>
</body>
<script>
window.parent.parent.dialog.size(300,200);
function  closelog(){
	window.parent.dialog.close();

}
function closeall(){
	window.parent.dialog.hide();
    newWindows('newpur', '确认采购信息', '/member/dialog/newpur/');
  

}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<title>弹框</title>
<style>
*{ margin:0; padding:0;}
a{ text-decoration:none;}

.alert_tips{  background:#fff; overflow:hidden; margin:0 auto; font:12px '微软雅黑';}
.alert_tips div{ text-align:center; color:#333; margin-top:30px;}
.alert_tips a{ display:block; width:90px; height:30px; background:#69AE6C; line-height:30px; text-align:center; color:#fff; margin:40px auto;}
.alert_tips a:hover{ background:#548C57;}
</style>
</head>

<body>

<!-- 提示弹框中间内容 start -->
<div class="alert_tips">
		<p style="text-align:center; margin:80px 0;">{{ content() }}{% if content !='' %}{{content}}{% endif %}</p>
    <a  {% if url != '' %} href="javascript:closeDialog1();" {% else %} href="javascript:closeDialog();" {% endif %}  style="background-color:#fab714;">确定</a>
</div>
<!-- 提示弹框中间内容 end -->

</body>
<script>
function closeDialog() {
	window.parent.closeDialog();
	{% if url is defined %}
	window.parent.parent.location.href = '{{ url }}';
	{% endif %}
}
function closeDialog1(){
   window.parent.parent.location.href = '{{ url }}';
}
window.parent.dialog.size(390,200);
</script>
</html>

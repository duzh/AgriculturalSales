<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
  <div class="main_right">
    <div class="bt2">会员云农宝账户</div>
    {{ form("/ynbbinding/index", "method":"get", "autocomplete" : "off") }}
    <div class="chaxun">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
              <td  height="35" align="right">用户ID：</td>
              <td  height="35" align="left">
                  <input type="text" name="uid" {% if uid!=0 %}value="{{uid}}" {% endif %}>
              </td>
              <td  height="35" align="right">用户名称：</td>
              <td  height="35" align="left">
                  <input type="text" name="name" {% if name %}value="{{name}}" {% endif %}>
              </td>
          </tr>
        <tr>
          <td  width="15%" height="35" align="right">手机号：</td>
          <td  width="35%" height="35" align="left">
              <input type="text" name="moblie" {% if moblie %}value="{{moblie}}" {% endif %}>
          </td>
          <td  height="35" align="right">注册时间：</td>
          <td height="35" align="left">
            <input readonly="readonly"  type="text" class="Wdate" name="stime" id="d4331" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4332\',{M:0,d:0})}'})" value="{{stime}}">
            -
            <input readonly="readonly" type="text" class="Wdate"  name="etime"  id="d4332" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4331\',{M:0,d:1});}',maxDate:'2020-4-3'})"
                       value="{{etime}}">
          </td>
        </tr>

        </table>
        <div class="btn">{{ submit_button("确定","class":'sub') }}</div>
      </div>
    </form>
    <div class="neirong" id="tb">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr align="center">
          <td width='10%' class="bj">用户ID</td>
          <td width='5%' class="bj">手机号</td>
          <!--<td width='10%' class="bj">姓名</td>-->
          <td width='14%' class="bj">云农宝账号</td>
          <!--<td width='5%' class="bj">云农宝姓名</td>-->
          <td width='14%' class="bj">是否实名认证</td>
          <td width='14%' class="bj">绑定时间</td>
          <td width='14%' class="bj">操作</td>
          <!-- <td width='8%' class="bj">可用资金</td>
        <td width='8%' class="bj">冻结资金</td>

        <td width='8%' class="bj">注册日期</td>-->
      </tr>
      <!--<?php $i=($current-1)*10+1 ?>1-->
      {% if data is defined %}
            {% for ynb in data %}
      <tr align="center">
        <td>{{ynb.user_id}}</td>
          <td>{{ynb.ynp_user_phone}}</td>
          <!--<td>{% if ynb.user_name %}{{ynb.user_name}}{% endif %}</td>-->
          <td>{{ynb.ynp_user_phone}}</td>
          <!--<td></td>-->
          <td>{% if ynb.status %}已认证{% endif %}</td>
      <td>{{ date("Y-m-d H:i:s",ynb.add_time)}}</td>
      <td>
          <!--<a href="javascript:ShowDiv('MyDiv1','fade1','{{ynb.user_id}}');">查看日志</a>-->
        </td>
  </tr>
  {% endfor %}
        {% endif %}
</table>
</div>
{{ form("users/index", "method":"get") }}
<div class="fenye">
<div class="fenye1">
  <span>{{ pages }}</span> <em>跳转到第
    <input type="text" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?>/>页</em>
  <?php unset($_GET['p']);
              foreach ($_GET as $key =>
  $val) {

          echo "
  <input type='hidden' name='{$key}' value='{$val}'>
  ";
        }?>
  <input class="sure_btn"  type='submit' value='确定'></div>
</div>
</form>
</div>
<div id="fade1" class="black_overlay"></div>
<div id="MyDiv1" class="white_content2">
    <form action="/manage/tag/tagunauditun"   method="post"  >
        <div class="gb">
            绑定日志
            <a href="#" onclick="CloseDiv('MyDiv1','fade1')"></a>
        </div>
        <div class="shenh">
            <ul>
                <li>
                    <lable>&nbsp;</lable>
                    <div>
                        <table>
                            <th></th>
                        </table>
                    </div>
                </li>
                <li>
                    <lable>&nbsp;</lable>
                    <div>
                        <input type="hidden" name='id' value='{{ data.tag_id}}'> <!-- # 隐藏ID -->
                        <input name="" type="button" value="确定" class="btn3" onclick="CloseDiv('MyDiv1','fade1')"/>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>
    <script type="application/javascript">
        //弹出隐藏层
        function ShowDiv(show_div,bg_div,user_id){
            $.ajax({ url: "/manage/ynbbinding/getlog/"+user_id, dataType:'json',success: function(data){
                alert(data);
            }});
            document.getElementById(show_div).style.display='block';
            document.getElementById(bg_div).style.display='block' ;
            var bgdiv = document.getElementById(bg_div);
            bgdiv.style.width = document.body.scrollWidth;
            $("#"+bg_div).height($(document).height());
        };
        //关闭弹出层
        function CloseDiv(show_div,bg_div)
        {
            document.getElementById(show_div).style.display='none';
            document.getElementById(bg_div).style.display='none';
            $('#zjuser_id').val('');
            $('#zjcredit_id').val('');
        };
        $(function(){

        });
        function remove_agreement( id)   {       if(confirm("您确定要删除吗?"))
            location.href='/manage/users/delete/'+id;

        }
        $(document).ready(function () {
            //按钮样式切换
            $(".neirong tr").mouseover(function(){
                //如果鼠标移到class为stripe的表格的tr上时，执行函数
                $(this).addClass("over");}).mouseout(function(){
                //给这行添加class值为over，并且当鼠标一出该行时执行函数
                $(this).removeClass("over");}) //移除该行的class
            $(".neirong tr:even").addClass("alt");
            //给class为stripe的表格的偶数行添加class值为alt
        });

        $(document).ready(function () {
            //按钮样式切换
            $(".btn>input").hover(
                    function () {
                        $(this).removeClass("sub").addClass("sub1");
                    },
                    function () {
                        $(this).removeClass("sub1").addClass("sub");
                    }
            );
        });
    </script>
<!-- main_right 结束  -->

</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>


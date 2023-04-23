
<div class="main">
    <div class="main_right">
        <div class="bt2">专线列表</div>
        {{ form("/special/index", "method":"get", "autocomplete" : "off") }}
        <div class="chaxun">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td  height="35" align="right">出发地：</td>
                    <td  height="35" align="left">
                         <select class="startAreas" name="province">
                              <option value="0" >省</option>
                          </select>
                          <select class="startAreas"  name="city">
                              <option value="0">市</option>
                          </select>
                          <select class="startAreas"  name="district" >
                              <option value="0">区/县</option>
                          </select>
                    </td>
                    <td  height="35" align="right">目的地：</td>
                    <td height="35" align="left">
                          <select class="endAreas"  name="endprovince">
                              <option value="0" >省</option>
                          </select>
                          <select class="endAreas"  name="endcity">
                              <option value="0">市</option>
                          </select>
                          <select class="endAreas" name="enddistrict" >
                              <option value="0">区/县</option>
                          </select>
                    </td>
                </tr>
            </table>
            <div class="btn">{{ submit_button("确定","class":'sub') }}</div>
        </div>
    </form>
    <div class="neirong" id="tb">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center">
                <td width='6%'  class="bj"><input type='checkbox' id="check_all"  >序号</td>
                <td width='10%' class="bj">编号</td>
                <td width='20%' class="bj">出发地</td>
                <td width='10%' class="bj">目的地</td>
                <td width='14%' class="bj">手机号码</td>
                <td width='8%' class="bj">联系人</td>
                <td width='8%' class="bj">添加时间</td>
                <td width='8%' class="bj">操作</td>
            </tr>

  <form action="/manage/special/removeAll" method="post"name='remove'   >
  <input type="submit" value='批量删除' id='removeBtn'>
  
            <?php $i=($current-1)*10+1 ?>
       {% if data is defined %}
            {% for sc in data %}
            <tr align="center">
                <td><input type="checkbox" name="remove[]" value='{{sc.sc_id}}'><?php echo $i++ ;?></td>
                <td>{{ sc.sc_sn }}</td>
                <td>{{ sc.start_pname}}{{sc.start_cname}}{{sc.start_dname}}</td>
                <td>{{ sc.end_pname}}{{sc.end_cname}}{{sc.end_dname}}</td>
	            <td>{% if sc.phone %}{{ sc.phone  }}{% else %}<img src="{{ constant('ITEM_IMG')}}/{{sc.phone_img}}">{% endif %}</td>
	            <td>{{ sc.contact_man }}</td>
	            <td>{{ date("Y-m-d H:i:s ",sc.add_time)}}</td>
                <td>
                 <a href="/manage/special/edit/{{sc.sc_id}}" >修改</a>
                 <a href="/manage/special/look/{{sc.sc_id}}" >查看详情</a>
                 <a href="/manage/special/delete/{{sc.sc_id}}" onclick="return confirm('确定将此记录删除?')">删除</a>
                </td>
            </tr>
            {% endfor %}
        {% endif %}
        </form>

                
    </table>
</div>
{{ form("/sell/index", "method":"get") }}
<div class="fenye">
    <div class="fenye1">
        <span>{{ pages }}</span>
        <em>跳转到第<input type="text" class='input' id='page' name='p' value="<?php if(isset($_GET['p'])) { echo $_GET['p'];}?>" />页</em>
        <?php unset($_GET['p']);
              foreach ($_GET as $key => $val) {

          echo "<input type='hidden' name='{$key}' value='{$val}'>";
        }?>

         <input class="sure_btn"  type='submit' value='确定' >
    </div>
</div>
</form>
</div>
<!-- main_right 结束  -->

</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>
<script>
$('#check_all').click(function () {
  if($(this).prop('checked')) {
    $('input[name="remove[]"]').prop('checked' , true);
  }else{
    $('input[name="remove[]"]').prop('checked' , false);
  }
})

$(".startAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if (startAreas) %}
    texts : [{{ startAreas }}],
    {% endif %}
    style : {"width" : 140}
});
$(".endAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
   {% if (endAreas) %}
    texts : [{{ endAreas }}],
    {% endif %}
    style : {"width" : 140}
});
function  batch_operation(type){
  var id="";
  var values="";
  var order_id=$("input[name='subBox[]']");
  $("#piliang").show();
  for(i=0;i<order_id.length;i++){
    if(order_id[i].checked){
      values=order_id[i].value;
      id = id +values + ","; 
    }
  }
  ida=id.substring(3);
  if(ida==""){
    alert("请选择");
    return false;
  }
  if(type==2){
    var stime=$("#d4334").val();
    var etime=$("#d4335").val();
    if(stime=='' && etime==''){
      alert('请选择随机时间');
      return false;
    }
  }
  var current={{current}};
  $.getJSON("/manage/sell/review/", {'id':id,'type':type,stime:stime,etime:etime} ,function(data){
       alert(data.msg);
       location.href='/manage/sell/index?p='+current;
  });
}
</script>

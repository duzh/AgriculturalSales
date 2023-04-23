<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
  <div class="main_right">
    <div class="bt2">可信农场列表</div>

    <div class="chaxun">
      {{ form("crediblefarm/index", "method":"get", "autocomplete" : "off") }}
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  height="35" align="right">地区：</td>
          <td height="35" align="left">
            <select name="province" class="selectAreas" id="province" >
              <option value="0" selected>请选择</option>
            </select>
            <select name="city" class="selectAreas" id="city">
              <option value="0">请选择</option>
            </select>
            <select name="qu" class="selectAreas" id="town">
              <option value="0">请选择</option>
            </select>
            <select name="xian" class="selectAreas" id="town">
              <option value="0">请选择</option>
            </select>
              <select name="areas" class="selectAreas" id="town">
              <option value="0">请选择</option>
            </select>
          </td>
          <td  height="35" align="right">可信农场名称：</td>
          <td height="35" align="left">
            <input type="text" name="farm_name" {% if farm_name %}value={{farm_name}}{% endif %}>
          </td>
        </tr>
        <tr>
          <td  width="15%" height="35" align="right">状态：</td>
          <td  width="35%" height="35" align="left">
            <select name="status" >
              <option value="all">全部</option>
              <option value="11" {% if status==11 %} selected {% endif %}>可访问</option>
              <option value="1" {% if status==1 %} selected {% endif %}>禁止访问</option>
            </select>
          </td>
          <td  height="35" align="right">推荐：</td>
          <td height="35" align="left">
            <input type="checkbox" name="is_home_page" value="1" <?php if(isset($_GET['is_home_page']) && $_GET['is_home_page'] == 1){ echo "checked='checked'";}?>>首页&nbsp;&nbsp;
          </td>
        </tr>
        
      </table>
      <div class="btn">{{ submit_button("确定",'class':'sub') }}</div>
    </div>
  </form>
  <div class="neirong" id="tb">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width='5%'  class="bj">序号</td>
        <td width='10%' class="bj">可信农场名称</td>
        <td width='24%' class="bj">种植作物</td>
        <td width='10%' class="bj">农场主</td>
        <td width='13%' class="bj">地区</td>
        <td width='8%' class="bj">申请时间</td>
        <td width='6%' class="bj">状态</td>
        <td width='8%' class="bj">推荐</td>
        <td width='8%' class="bj">操作</td>
      </tr>
      <?php $i=($current-1)*10+1 ?>
      {% if data is defined %}
      {% for key,val in data %}
      <tr align="center">
        <td><?php echo $i++ ;?></td>
        <td>{{val['farmname']}}</td>
        <td>{{val['goods_name']}}</td>
        <td>{{val['farmer_user_name']}}</td>
        <td>{{val['address']}}</td>
        <td>{% if val['add_time'] %}{{date('Y-m-d H:i:s',val['add_time'])}}{% else %}-{% endif %}</td>
        <td>{% if val['status']==1 %} 可访问{% else %}禁止访问{% endif %}</td>
        <td><input type="checkbox" name="recommand_type" {% if val['is_home_page']==1 %} checked {% endif %} onchange="recommand({{val['id']}})">首页</td>
        <td>
          <a href="/manage/crediblefarm/edit/{{val['user_id']}}">编辑</a>
          <a href="http://{{val['url']}}.5fengshou.com/indexfarm"  target="_blank">查看主页</a>
      <!--  
          <a href="/indexfarm"  target="_blank">查看主页</a> -->
          <a href="/manage/crediblefarm/call/{{val['id']}}">{% if val['status']==1 %} 禁止访问{% else %}开启访问{% endif %}</a>
        </td>
      </tr>
      {% endfor %}
      {% endif %}
      
    </table>
  </div>
  {{ form("crediblefarm/index", "method":"get") }}
  <div class="fenye">
    <div class="fenye1">
      <span>{{ pages }}</span> <em>跳转到第
        <input type="text" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?>/>页</em>
          <?php unset($_GET['p']);
              foreach ($_GET as $key => $val) {

          echo "<input type='hidden' name='{$key}' value='{$val}'>";
        }?>
      <input class="sure_btn"  type='submit' value='确定'></div>
  </div>
</form>
</div>
<!-- main_right 结束  -->

</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>

<script>
$(function(){ 
$(".selectAreas").ld({ajaxOptions : {"url" :"/ajax/getareas"},defaultParentId : 0,
  {% if curAreas %}
  texts :[{{ curAreas }}],
  {% endif %}    
  style : {"width" : 140}   
  }); 
});
//推荐
function recommand(id){
  if(window.confirm('您确定要进行此操作吗?')){
  $.getJSON("/manage/crediblefarm/recommand/", {'id':id} ,function(data){
      alert(data);
       });
  }
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
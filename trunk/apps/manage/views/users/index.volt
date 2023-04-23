{{ form("users/index", "method":"get", "autocomplete" : "off") }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
  <div class="main_right">
    <div class="bt2">会员列表</div>
    {{ form("/sell/index", "method":"get", "autocomplete" : "off") }}
    <div class="chaxun">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  height="35" align="right">会员类型：</td>
          <td  height="35" align="left">
            <select name="usertype" >
              <option value="">全部</option>
              <option value="1" {% if user_type==1 %} selected {% endif %} >供应商</option>
              <option value="2" {% if user_type==2 %} selected {% endif %} >采购商</option>
            </select>
          </td>
          <td  height="35" align="right">地区：</td>
          <td height="35" align="left">
            <select name="province" class="selectAreas" id="province" >
              <option value="0" selected>省</option>
            </select>
            <select name="city" class="selectAreas" id="city">
              <option value="0">市</option>
            </select>
            <select name="qu" class="selectAreas" id="town">
              <option value="0">区/县</option>
            </select>
            <select name="xian" class="selectAreas" id="town">
              <option value="0">请选择</option>
            </select>
              <select name="areas" class="selectAreas" id="town">
              <option value="0">请选择</option>
            </select>
          </td>
        </tr>
        <tr>
          <td  width="15%" height="35" align="right">会员账号：</td>
          <td  width="35%" height="35" align="left">
            {{ text_field("username", "type" : "numeric",'class':'t1','value':username ) }}
          </td>
          <td  height="35" align="right">注册时间：</td>
          <td height="35" align="left">
            <input readonly="readonly"  type="text" class="Wdate" name="stime" id="d4331" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4332\',{M:0,d:0})}'})" value="{{stime}}">
            -
            <input readonly="readonly" type="text" class="Wdate"  name="etime"  id="d4332" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4331\',{M:0,d:1});}',maxDate:'2020-4-3'})"
                       value="{{etime}}">
          </td>
        </tr>
        
        <tr>
          <td  height="35" align="right">性别：</td>
          <td  height="35" align="left">
            <select name="sex" >
              <option value="all">全部</option>
              <option value="11" {% if sex==11 %} selected {% endif %} >男</option>
              <option value="1" {% if sex==1 %} selected {% endif %} >女</option>
            </select>
          </td>

          <td  height="35" align="right">业务主营：</td>
          <td  height="35" align="left">
            <select name="main_category" >
              <option value="">请选择</option>
              {% for key,val in cate %}
              {% if val["parent_id"] == 0 %}
              <option value="{{val['id']}}" {% if main_category==val['id'] %} selected {% endif %}>{{val["title"]}}</option>
              {% endif %}
              {% endfor %}
            </select>
          </td>       
        </tr>

        <tr>
          <td  height="35" align="right">用户ID：</td>
          <td  height="35" align="left">
            <input type="text" name="id" {% if id!=0 %}value="{{id}}" {% endif %}>
          </td>
          <td  height="35" align="right" style="width:80px;">发布平台：</td>
          <td  height="35" align="left">
            <select name="plat" style="width:80px;">
              <option value="sel" selected>请选择</option>
              <option value="all" {% if plat_param == 'all' %} selected {% endif %}>全部</option>
              <option value="3" {% if plat_param == 3 %} selected {% endif %}>自定义</option>
              
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
          <td width='10%' class="bj">用户ID</td>
          <td width='5%' class="bj">会员类型</td>
          <td width='10%' class="bj">会员账号</td>
          <td width='10%' class="bj">会员姓名</td>
          <td width='5%' class="bj">性别</td>
          <td width='14%' class="bj">所在地区</td>
          <td width='10%' class="bj">主营业务</td>
          <td width='8%' class="bj">发布平台</td>
          <td width='14%' class="bj">上次登陆时间</td>
          <!-- <td width='8%' class="bj">可用资金</td>
        <td width='8%' class="bj">冻结资金</td>
        -->
        <td width='8%' class="bj">注册日期</td>
        <td width='8%' class="bj">操作</td>
      </tr>
      <?php $i=($current-1)*10+1 ?>
      {% if data is defined %}
            {% for user in data %}
      <tr align="center">
        <?php $userext=Mdg\Models\UsersExt::getuserext($user['id']);?>
        <td>
          {{user['id']}}</td>
        <td>
          {% if user['usertype'] %}{{ usertype[user['usertype']]}}{% else %}-{% endif %}
        </td>
        <td>
          {% if user['username'] %}{{ user['username'] }} {% else %}-{% endif %}
        </td>
        <td>{% if user["name"] %} {{ user['name']}} {% else %}-{% endif %}</td>
        <td>{% if user["sex"]==0 and user['sex']!='' %}男{% endif %} {% if user["sex"]==1 %}女{% endif %} {% if user["sex"]=='' %}-{% endif %}</td>
        <td>{% if user['areas_name'] %}{{ user['areas_name'] }} {% else %}-{% endif %}</td>
        <td><?php if($user['main_category']) {echo Mdg\Models\Category::selectBytocateName($user['main_category']);}else{echo '-';}?></td>
        <td>{% if user["publish_set"]==0 %} 全部 
          {% elseif user["publish_set"]==3 %} 自定义 
          {% endif %}
        </td>
        <!-- <td>可用资金</td>
      <td>冻结资金</td>
      -->
      <td>{{ date("Y-m-d H:i:s",user['lastlogintime'])}}</td>
      <td>{{ date("Y-m-d H:i:s",user['regtime'])}}</td>
      <td>
        <a href="/manage/users/edit/{{ user['id']}}/{{current}}">编辑</a>
        <a href="/manage/users/look/{{ user['id']}}">查看</a>
        
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
<!-- main_right 结束  -->

</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>

<script> $(function(){  
$(".selectAreas").ld({ajaxOptions : {"url" :"/ajax/getareas"},defaultParentId : 0,
  {% if curAreas %}
  texts :[{{ curAreas }}],
  {% endif %}    
  style : {"width" : 140}   
  }); 
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
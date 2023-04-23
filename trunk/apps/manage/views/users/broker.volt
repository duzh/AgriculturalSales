{{ form("users/broker", "method":"get", "autocomplete" : "off") }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
  <div class="main_right">
    <div class="bt2">直销经济人列表</div>
    {{ form("/sell/index", "method":"get", "autocomplete" : "off") }}
    <div class="chaxun">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          
          <td  height="35" align="right">地区：</td>
          <td height="35" align="left" colspan="3">
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
          <td  width="15%" height="35" align="right">直销经济人账号：</td>
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
      </table><!--<a href="javascript:ShowDiv('MyDiv1','fade1','{{ item['user_id']}}','{{item['credit_id']}}');">添加</a>-->
        <div class="btn">{{ submit_button("确定","class":'sub') }}&nbsp;<input type="button" class="sub" value="添加" onclick="ShowDiv('MyDiv1','fade1');"></div>
      </div>
    </form>
    <div class="neirong" id="tb">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr align="center">
          <td width='10%' class="bj">用户ID</td>
          <!--<td width='5%' class="bj">会员类型</td>-->
           <td width='10%' class="bj">直销经济人账号</td>
          <td width='10%' class="bj">直销经济人姓名</td>
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

        <a href="/manage/users/look/{{ user['id']}}">查看</a>
          <a href="javascript:if(confirm('确认取消经纪人吗?')){window.location='/manage/users/cancelbroker/{{ user['id']}}'}">取消</a>
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
    <!--弹出层新增 开始-->
    <div id="fade1" class="black_overlay"></div>
    <div id="MyDiv1" class="white_content2">
        <form action="/manage/tag/tagunauditun"   method="post"  >
            <div class="gb">
                新增
                <a href="#" onclick="CloseDiv('MyDiv1','fade1')"></a>
            </div>
            <div class="shenh">
                <ul>
                    <li>
                        <lable>会员手机号：</lable>
                        <div>
                            <input type="text" id="mobile" name="mobile" value=""/>
                        </div>
                    </li>
                    <li style="display: none;">
                        <font>名称 ：</font>
                        <i id="user_name" style="font-style:normal;"></i>
                    </li>
                    <li style="display: none;">
                        <font>用户ID：</font>
                        <i id="user_id" style="font-style:normal;"></i>
                    </li>
                    <li>
                        <lable>&nbsp;</lable>
                        <div>
                            <input type="button" value="添加" class="btn3" id="setBroker" />
                            <input type="hidden" name='uid' value='0'><!-- # 隐藏ID -->
                            <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv('MyDiv1','fade1')"/>
                        </div>
                    </li>
                </ul>
            </div>
        </form>
    </div>
    <!--弹出层新增 结束-->

<!-- main_right 结束  -->

</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>

<script> $(function(){
    $('#mobile').blur(function(){
        if($(this).val()){
            $.getJSON('/manage/users/getusers/'+$(this).val(),function(res){
                if(res.msg){
                    $(".shenh ul li").each(function(i){
                        if(i == 1 || i == 2)$(this).hide();
                    });
                    alert(res.msg);
                }
                else if(res.data){
                    $(".shenh ul li").each(function(i){
                        if(i == 1 || i == 2)$(this).show();
                    });
                    $('#user_name').text(decodeURI(res.data.username));
                    $('#user_id').text(res.data.user_id);
                    $('input[name="uid"]').val(res.data.user_id);
                }
            });
        }
    });
    $('#setBroker').click(function(){
        var uid = $('input[name="uid"]').val();
        if(uid != '0'){
            $.ajax({
                type: "POST",
                url: "/manage/users/setbroker",
                data: "uid="+uid,
                success: function(msg){
                    if(msg) window.location="/manage/users/broker";
                    else alert('添加失败');
                }

            });
        }
        else
            alert('操作错误!');
    });

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
//弹出隐藏层
function ShowDiv(show_div,bg_div){
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
    $(".shenh ul li").each(function(i){
        if(i == 1 || i == 2)$(this).hide();
    });
    $('#mobile').val('');
};
</script>
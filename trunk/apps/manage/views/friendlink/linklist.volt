<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main" >

  <div class="main_right">
    <div class="bt2">友情连接列表 </div>

    <div class="chaxun">
      {{ form("friendlink/linklist", "method":"get", "autocomplete" : "off") }}
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
		   <td  align="right">名称:</td>
		   <td  align="left"><input type='text' name="website_name" value="{{website_name}}"/></td>
		   <td  align="right">网址:</td>
		   <td  align="left"><input type='text' name="website_link" value="{{website_link}}"/></td>         
		   <td  align="right">状态:</td>
		   <td  align="left">
				<select name="status" id="status" style="width:140px" >
				  {% for key,state in Linkstatus %}
				  <option value="{{key}}" {% if key==status %} selected {% endif %}>{{state}}</option>
				  {% endfor %}
				</select>   
			</td>
        </tr>
		<tr>		   
		   <td  align="right">显示位置:</td>
		   <td  align="left">
			   <select name="location" id="location" style="width:140px" >
					 
					  {% for key,state in Linklocation %}
					  <option value="{{key}}" {% if key==location %} selected {% endif %}>{{state}}</option>
					  {% endfor %}
				</select>   
		   </td> 
		   <td  align="right">是否有logo:</td>
		   <td  align="left">
		    <select name="islogo" id="islogo" style="width:140px" >
				 
				  {% for key,state in Linklogo %}
				  <option value="{{key}}" {% if key==islogo %} selected {% endif %}>{{state}}</option>
				  {% endfor %}
			</select>   
		   </td>		  
        </tr>
       
      </table>
	 
      <div class="btn"> 
	  {{ submit_button("搜索",'class':'sub') }}    
	  <a style="float: right; margin-right: 100px;" href='/manage/friendlink/addlink' class="sub" >添加连接</a>
	  
	  </div>
	  
  </form>  
  <div class="neirong" id="tb" ><!--style="overflow-x:scroll;width:1350px"-->
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width='3%'  class="bj">序号</td>
        <td width='7%' class="bj">网站名称</td>
        <td width='7%' class="bj">logo</td>
        <td width='7%' class="bj">显示位置</td>
        <td width='7%' class="bj">状态</td>
        <td width='7%' class="bj">排序</td>
        <td width='7%' class="bj">备注</td>       
		<td width='12%' class="bj">操作</td>
      </tr>
      <?php $i=($page-1)*10+1 ?>
      {% if data.items is defined %}
      {% for key,val in data.items %}
      <tr align="center">
        <td><?php echo $i++ ;?></td>
        <td>{{val.website_name }}</td>
        <td> <img height="31" width="88" src="{{val.logo }}"/></td>
        <td>{{ Linklocation[val.location]}}  </td>
        <td>{{ Linkstatus[val.status] }}</td>
		<td>{{ val.order_item }}</td>
		<td>{% if val.demo %} {{val.demo }} {% endif %}{% if !val.demo %} 无 {% endif %}</td>
		<td > 		
		<a href="/manage/friendlink/modylink?id={{val.id }}" > 修改 </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="#" onclick="dellink({{val.id }})"> 删除 </a>	
		</td>
      </tr>
      {% endfor %}
      {% endif %}
      
    </table>
  </div>
  {{ form("friendlink/linklist", "method":"get") }}
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
 </div>
<!-- main_right 结束  -->

</div>
<div class="footer">Copyright ? 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>

<script>

function dellink(id){
if(!id){
alert('缺少参数');
return;
}
if(confirm("是否删除？"))
{
	$.ajax({
			type:"POST",
			url:"/manage/friendlink/dellink",
			data:{id:id},
			dataType:"json",
			success:function(msg){
				if(msg['code'] == 3) {
					alert(msg['result']);
					window.location.href = "/manage/friendlink/linklist";
				} else {
					alert(msg['result']);
					return;
				}
			}
		});	
	}
 }
  
  
  
  
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
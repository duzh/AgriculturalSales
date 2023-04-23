<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>订单详细查看</title>


</head>

<body>

    <div class="place">
        <span>位置：</span>
        <ul class="placeul">
            <li>
                <a href="/professor/index">专家审核</a>
            </li>
            <li>
                {{item['title']}}
            </li>
        </ul>
    </div>

    <div class="main_right">

        <div class="bt2"></div>

        <div class="zcd1">

            <h6 class="bj1">认证身份：{{ item['type_name'] }} &nbsp; {% if item['status'] != 0 %}审核状态：{{item['status_name']}}{%endif%}</h6>
            <table  class='tablelist' width="50%" border="1" cellspacing="0" cellpadding="0">

                <tbody>
                    <tr>
                        <td width="10%" align="center">基本信息</td>
						<td></td>
                    </tr>
					<tr>
					        <td width="10%" align="center">真实姓名：</td>
							<td>{{ item['realname']}}</td>
					</tr>
                    <tr>
                        <td width="10%" align="center">联系电话：</td>
                        <td><lable> {{ item['phone']}}</lable></td>
                    </tr>
					<tr>
					<td width="10%" align="center">擅长领域：</td>
                    <td>
					{% for key , val in item['crops']%}
					<lable>{{ val }}</lable>
					{% endfor %}
					</td>
					</tr>
                </tbody>
            </table>

        </div>

        <!-- 商品信息 -->

        <div class="zcd1">

            <h6 class="bj1">完善信息</h6>
            <table class='tablelist' width="100%" border="0" cellspacing="0" cellpadding="0">

                <tbody>
                    <tr>
						<td width="10%" align="center">职称：</td>
						<td>{{item['job_title']}}</td>
                    </tr>
					<tr>
						<td width="10%" align="center">所在地区：</td>
						<td>{{item['area']}}</td>
                    </tr>
					<tr>
                        <td width="10%" align="center">详细地址：</td>
						<td>{{item['address']}}</td>
                    </tr>
					<tr>
                        <td width="10%" align="center">认证照片：</td>
						<td>{% if item['picture'] %}<img src="{{item['picture']}}">{% endif %}</td>
                    </tr>
                </tbody>
            </table>
        </div>
		{% if item['status'] == 0 %}<input type="button" value="通过" onclick="to_examine({{item['professor_id']}},1)"> <input type="button" value="拒绝" onclick="to_examine({{item['professor_id']}},2)"> {%endif%}<input type="button" value="编辑" onclick=""> <input type="button" value="删除" onclick="del({{item['professor_id']}})">
</div>
</body>
</html>
<script type="text/javascript">
<!--
	function to_examine(professor_id,type){
			$.ajax({
				type:"POST",
				url:"/professor/to_examine",
				data:{'professor_id':professor_id,'type':type},
				success:function(data){		
						alert(data);
						window.location.reload();
				}
			});
	}
	function del(professor_id){
		if(confirm('确定要删除吗？')){
			location.href="/professor/del/"+professor_id;
		}
	}
//-->
</script>
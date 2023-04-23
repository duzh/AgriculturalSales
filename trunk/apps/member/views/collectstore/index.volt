<!--头部 start-->
{{ partial('layouts/member_header') }}
<!--头部 end-->

<!--主体 start-->
<div class="center-wrapper pb30">
    <div class="bread-crumbs w1185 mtauto">
        <span>{{ partial('layouts/ur_here') }}&nbsp;收藏的可信农场</span>
    </div>
    <div class="w1185 mtauto clearfix">
        <!-- 左侧 start-->
        {{ partial('layouts/navs_left') }}
        <!-- 左侧 end-->
        
        <!-- 右侧 start-->
        <div class="center-right f-fr">
            <div class="collect-sell">

                <div class="title f-oh">收藏的可信农场</div>
                <table cellpadding="0" cellspacing="0" width="903" class="list">
                    <tr height="32">
                        <th width="25%">可信农场名称</th>
                        <th width="27%">主营产品</th>
                        <th width="24%">操作</th>
                    </tr>
                    {% if collectfarm['items'] is defined %}
                    {% for key,val in collectfarm['items'] %}
                    <tr height="128">
                        <td>{{ val['store_name'] }}</td>
                        <td>{{ val['main_products'] }}</td>
                        <td>
                    <!--
                    上线时修改：为
                    <a href="http://{{val['url']}}.5fengshou.com/indexfarm/index">查看详情</a>
                    -->
                            <a href="http://{{val['url']}}.{{host}}/indexfarm/index">查看详情</a>
                            <a href="javascript:;" onclick="farmcansel({{ val['id'] }})">取消收藏</a>
                        </td>
                    </tr>
                    {% endfor %}
                    {% endif %}
                </table>
                <!-- 分页 start-->
                {% if collectfarm['total_count']>1  %}
                    <div class="esc-page mt30 mb30 f-tac f-fr mr30">
                        {{collectfarm['pages']}}                   
                        <span>
                            <form action="/member/collectstore/index" method="get">           
                                <label>去</label>
                                <input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " value="1" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
                                <label>页</label>                   
                                <input class="btn" type="submit" value="确定" onclick="go()"/>
                            </form>
                        </span>                  
                    </div>
                {% endif %}
                <!-- 分页 end-->
            </div>
        </div>
        <!-- 右侧 end-->
    </div>
</div>
<!--主体 end-->

<!--尾部 start-->
{{ partial('layouts/footer') }}
<!--尾部 end-->
<script type="text/javascript">
function go(){
var p=$("#p").val();
 var count = {{collectfarm['total_count']}};
 if(p>count){
    $("#p").val(count);
 }
}

// 取消收藏
function farmcansel(id) {
    $.ajax({
        type:"POST",
        url:"/member/collectstore/collCansel",
        data:{id:id},
        dataType:"json",
        success:function(msg){
            if(msg['code'] == 0){
              newWindows('login', '登录', "/member/dlogin/index?ref=/member/collectstore/index/");
            } else if(msg['code'] == 2) {
                alert(msg['result']);
                window.location.reload();
            } else {
                alert(msg['result']);
                return;
            }
        }
    });
}
</script>
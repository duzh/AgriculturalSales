<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}消息列表</span>
        </div>
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
            <div class="center-right f-fr">

                <div class="news-list f-oh pb30">
                    <div class="title f-oh">
                        <span>消息列表</span>
                    </div>
                    {% if data['items'] is defined %}
                    <?php $i=($current-1)*15+1 ?>
                    {% for v in data['items']%}
                    <div class="m-box f-oh">
                        <div class="m-title"><?php echo $i++ ;?>.{{v['name']}}</div>
                        <div class="content" style="width: 80%;float: left; line-height:20px;">
                            {{v['desc']}}
                        </div>
                        <div style="width:15%; text-align:right; float: right; line-height:20px; margin-top:10px;color:#999;"><?php echo date("Y-m-d H:i:s" ,$v['add_time']);?></div>
                    </div>
                     {% endfor %}
                    {% endif %}
                    <!-- 分页 -->
                    {% if data['pages'] and data['items'] and data['total_pages']>1  %}
                    <div class="esc-page mt30 mb30 f-tac f-fr mr30">
                        {{data['pages']}}
                          <span>
                        <form action="/member/messageuser/list" method="get">
                            <label>去</label>
                            <input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " value="{% if current>1 %}1{% else %}{{current}}{% endif %}" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
                            <label>页</label>
                        </span>    
                        <input class="btn" type="submit" value="确定" onclick="go()"/>
                        </form>
                    </div>
                    {% endif  %}
                </div>

            </div>
        <!--右侧end-->
    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<script>    
function go(){
var p=$("#p").val();
 var count = {{data['total_pages']}};
 if(p>count){
    $("#p").val(count);
 }
}
</script>
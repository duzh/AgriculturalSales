<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/version2.3/css/news_tip.css" />


{{ partial('layouts/page_header') }}


<div class="ur_here w960">
     <span>{{ partial('layouts/ur_here') }}消息列表</span>
</div>

<div class="shop_decora w960 clearfix">

    {{ partial('layouts/navs_left') }}  
          <!-- 左侧导航栏 end -->
    <!-- 右侧 start -->
    <div class="center_right f-fr">
    	<!-- 服务站基本资料 start -->
        <div class="service-station-message f-oh">            
            <div class="title f-oh">历史消息记录</div>            
            <div class="about-tuijian">
 {% if data['items'] is defined %}
            <?php $i=($current-1)*15+1 ?>
            {% for v in data['items']%}
                <div class="list">
                    <dl class="clearfix">
                        <dt><?php echo $i++ ;?></dt>
                        <dd>
                            <span>{{v['name']}}</span>
                            <!-- <p>您发布的<font>xxxxx商品</font>，有人采购啦，<a href="#">点击查看详情！</a></p> -->
                            {{v['desc']}}
                        </dd>
                    </dl>
                    <div class="time"><?php echo date("Y-m-d H:i:s" ,$v['add_time']);?></div>
                </div>
                {% endfor %}
               {% endif %}
            </div>
            
            <!-- 分页 -->
            <div class="page">
                {{data['pages']}}
            </div>
            
        </div>
    	<!-- 服务站基本资料 end -->
    </div>
    <!-- 右侧 end -->
</div>
</div>
<!-- 底部 start -->
{{ partial('layouts/footer') }}
<!-- 底部 end -->

</body>
</html>

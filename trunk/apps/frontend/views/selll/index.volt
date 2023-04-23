

<!-- 头部开始 -->
{{ partial('layouts/page_header') }}
<!-- 头部结束 -->

<!-- 主体内容开始 -->
<div class="cg_ping w960" id="showtable" >
    <!-- 块 start -->
    <div class="choose_list mt20 mb10">
        <!-- list1 start -->
        <div class="list">
            <span class="f-fl f-fs14">产品</span>
            <div class="list_xiang f-fl">
                <a class="all" href="/sell/index?all=-1" > 全部</a>
                {% for sell in cat_list %}
                <a href="javascript:;"  onclick="loading({{sell.id}},'showc')">{{sell.title}}</a>
                {% endfor %}
            </div>
            <div class="xiang clear" id="xiang" >
                <?php   if($this->session->category){   ?>
                         <?php foreach (Mdg\Models\Sell::showc($this->session->category) as $key => $value) {  ?>
                        <a href="/sell/index?category=<?php echo  $value['id']; ?>" ><?php echo $value["title"];?>
                            <?php if($this->session->category==$value["id"]){ ?>
                            <img src="http://static.ync365.com/mdg/images/choose_checked_img.png"  style="display:block"/></a>
                            <?php }else{?>
                            <img src="http://static.ync365.com/mdg/images/choose_checked_img.png"  /></a>
                            <?php }?>
                         <?php }?>
                <?php }?>
            </div>
        </div>
        <!-- list1 end -->
        <!-- list1 start -->
        <div class="list border_none">
            <span class="f-fl f-fs14">地区</span>
            <div class="list_xiang f-fl">
                <a class="all" href="/sell/index?all=-2" >全部</a>
                {% for sell in areas_list %}
                <a href="javascript:;" onclick="loading({{sell.area_id}},'showa')">{{sell.area_name}}</a>
                {% endfor %}    
            </div>
            <div class="xiang clear" id="xiangs" >
                 <?php   if($this->session->areas){   ?>
                         <?php foreach (Mdg\Models\Sell::showa($this->session->areas) as $key => $value) {  ?>
                        <a href="/sell/index?address=<?php echo  $value['area_id']; ?>" ><?php echo $value["area_name"];?>
                            <?php if($this->session->areas==$value["area_id"]){ ?>
                            <img src="http://static.ync365.com/mdg/images/choose_checked_img.png" style="display:block" /></a>
                            <?php }else{?>
                            <img src="http://static.ync365.com/mdg/images/choose_checked_img.png" /></a>
                            <?php }?>
                         <?php }?>
                <?php }?>
            </div>
        </div>
        <!-- list1 end -->
    </div>
    <!-- 块 end -->
    <!-- 块 start -->
    <div>
        <table class="cg_table" >
        <tr height="30" class="title">
        <th style="text-align:center;" width="134">报价时间</th>
        <th width="106">品名</th>
        <th width="87">供应地</th>
        <th width="67">供应量</th>
        <th width="233">规格</th>
        <th width="104">单价（元）</th>
        <th width="229">供应商</th>
        </tr>
        {% if data.items is defined %}
        {% for sell in data.items %}
        <tr height="40">
        
        <td style="text-align:center;">{{ date("Y-m-d H:i:s ",sell.createtime)}}</td>
        <td><a href="/sell/edit/{{sell.id}}">{{ sell.title }}</a></td>
        <td>{{ sell.areas_name }}</td>
        <td>{{ sell.quantity }}</td>
        <td class="mouse_title">
        <a class="south-west-alt" title="{{ sell.spec }}" href="javascript:;">
        {{ sell.spec }}</a></td>
        <td><strong>{{ sell.min_price}}~{{sell.max_price }}</strong></td>
        <td><font>{{ sell.uname }}</font><a class="btn cg_btn" href="javascript:newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ sell.id }}');" >采购</a>
        <a class="btn" href="/sell/edit/{{sell.id}}">详情</a></td>

        </tr>
        {% endfor %}
        {% endif %}
        </table>
        <!-- 块 end -->
        <!-- 块 start -->
        
        <div class="page">
        {{ pages }}
        <span>共{{ data.total_pages }}页</span>
        </div>
    </div>
    <!-- 块 end -->
</div>
<!-- 主体内容结束 -->

<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->
{{ partial('sell/showuser') }}
<!-- 采购弹框 start -->

<!-- 弹框 end -->

</body>
</html>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js"></script>

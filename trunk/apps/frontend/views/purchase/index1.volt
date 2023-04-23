
<!-- 头部开始 -->
{{ partial('layouts/page_header') }}
<!-- 头部结束 -->

<!-- 主体内容开始 -->
<div class="cg_ping w960">
    <!-- 块 start -->
    <div class="choose_list mt20 mb10 clear">
        <div class="list">
            <span class="f-fl f-fs14">产品</span>
            <div class="list_xiang f-fl">
                <a class="all" href="/purchase/index?all=-1" >全部</a>
                {% for sell in cat_list %}
                <a href="javascript:;"  onclick="loading({{sell.id}},'showc')">{{sell.title}}</a>
                {% endfor %}
            </div>
            <div class="xiang clear" id="xiang" >
                <?php   if($this->session->category1){   ?>
                <?php foreach (Mdg\Models\Sell::showc($this->
                session->category1) as $key => $value) {  ?>
                <a href="/purchase/index?category=<?php echo  $value['id']; ?>
                    " >
                    <?php echo $value["title"];?>
                    <?php if($this->
                    session->category1==$value["id"]){ ?>
                    <img src="http://static.ync365.com/mdg/images/choose_checked_img.png"  style="display:block"/>
                </a>
                <?php }else{?>
                <img src="http://static.ync365.com/mdg/images/choose_checked_img.png"  />
            </a>
            <?php }?>
            <?php }?>
            <?php }?></div>
    </div>
    <!-- list1 end -->
    <!-- list1 start -->
    <div class="list border_none">
        <span class="f-fl f-fs14">地区</span>
        <div class="list_xiang f-fl">
            <a class="all" href="/purchase/index?all=-2" >全部</a>
            {% for sell in areas_list %}
            <a href="javascript:;" onclick="loading({{sell.area_id}},'showa')">{{sell.area_name}}</a>
            {% endfor %}
        </div>
        <div class="xiang clear" id="xiangs" >
            <?php   if($this->
            session->areas1){   ?>
            <?php foreach (Mdg\Models\Sell::showa($this->
            session->areas1) as $key => $value) {  ?>
            <a href="/purchase/index?address=<?php echo  $value['area_id']; ?>
                " >
                <?php echo $value["area_name"];?>
                <?php if($this->
                session->areas1==$value["area_id"]){ ?>
                <img src="http://static.ync365.com/mdg/images/choose_checked_img.png" style="display:block" />
            </a>
            <?php }else{?>
            <img src="http://static.ync365.com/mdg/images/choose_checked_img.png" />
        </a>
        <?php }?>
        <?php }?>
        <?php }?></div>
</div>
</div>
<!-- 块 end -->
<div class="choose_tab f-fl">
<!-- 块 start -->
<table class="cg_table bj_table">
    <tr height="30" class="title">
        <th style="text-align:center;" width="108">发布时间</th>
        <th width="273">采购商品</th>
        <th width="86">采购地区</th>
        <th width="243">采购商</th>
    </tr>
    {% if data.items is defined %}
            {% for sell in data.items %}
            <a href="">
    <tr height="40">
        <td style="text-align:center;">{{ date("Y-m-d H:i:s ",sell.createtime)}}</td>
        <td class="mouse_title">
            <a class="south-west-alt" title="{{sell.title}} 台农一号 单个重:" href="javascript:;">{{sell.title}}</a>
        </td>
        <td>{{sell.areas_name}}</td>
        <td> <font style="margin-right:48px;">{{sell.username}}</font>
            <a style="margin-right:34px; margin-top:2px" class="btn bj_btn" 
                    href="javascript:newWindows('newquo', '确定报价', '/member/dialog/newquo/{{sell.id}}');" >报价</a> <font style="margin-right:0;"><i><?php echo Mdg\Models\PurchaseQuotation::oredercount($sell->id)?></i>
                &nbsp;家报价</font> 
        </td>
    </tr>
    </a>
    {% endfor %}
          {% endif %}
</table>
<!-- 块 end -->
<!-- 块 start -->
<div class="page">
    {{ pages }}
    <span>共{{ data.total_pages }}页</span>
</div>
<!-- 块 end -->
</div>
<div class="choose_right f-fr" id="sroll">
<h6>成交动态展示</h6>
<div style="overflow:hidden; height:533px;">
    <ul>
        {% for order in order %}
        <li>
            <span>
                <?php echo Mdg\Models\PurchaseQuotation::humandate($order->addtime)?></span>
            <br/>
            {{order.areas_name}}{{order.purname}}成功以&nbsp; <strong>{{order.price}}元/斤</strong>
            &nbsp;采购了&nbsp; <strong>{{order.quantity}}
                <?php echo  Mdg\Models\Sell::$type3["3"] ?></strong>
            &nbsp;{{order.goods_name}}
            <br />
            <span>
                <?php echo Mdg\Models\PurchaseQuotation::spec($order->purid)?></span>
        </li>
        {% endfor %}
    </ul>
</div>
</div>
</div>
<!-- 主体内容结束 -->
<div class="layer">&nbsp;</div>
<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->

<script>
/* 文字向上滚动效果 */
function scrollNews(obj) { 
    var $self = obj.find("ul"); 
    var lineHeight = $self.find("li:first").height() + 1; 
    $self.animate({ 
        "marginTop": -lineHeight + "px" 
    }, 1000, function() { 
        $self.css({ 
            marginTop: 0 
        }).find("li:first").appendTo($self); 
    });
}

jQuery(document).ready(function(){
    var gyInput = $('.gy_step li input');
    inputFb(gyInput);
    
    /* 筛选条件 点击显示选项效果 */
    $('.list_xiang a').click(function(){
        if($(this).hasClass('all')){
            $(this).parents('.list').find('.xiang').hide();
        }else{
            $(this).parent().find('.all').css('background', '#05780a');
            $('.list_xiang a').removeClass('active');
            $(this).addClass('active');
            $(this).parents('.list').find('.xiang').show();
        };
    });
    
    /* 筛选条件 点击选中选项效果 */
    $('.xiang a').click(function(){
        $(this).parent().find('img').hide();
        
        $(this).find('img').show();
    });
    
    /* 鼠标悬停效果 */
    $('.south-west-alt').powerTip({ placement: 'sw-alt' });
    
   
    
    /* 关闭按钮 */
    $('.close_btn').click(function(){
        if(!$(this).parents('.alert_box')){
            $(this).parent().hide();
            $('.layer').hide();
        }else{
            $(this).parent().hide();
            $('.alert_box').hide();
            $('.layer').hide();
        };
    });
    
    /* 鼠标经过表格行的效果 */
    
    
    var $sroll = $("#sroll"); 
    var scrollTimer = null;
    
    $sroll.hover(function() {
        clearInterval(scrollTimer); 
    }, function() { 
        scrollTimer = setInterval(function() { 
            scrollNews($sroll); 
        }, 1000); 
    }).trigger("mouseleave");  
});
</script>
<script>
function loading(id,type){
     if(type=="showc"){
      $('#xiang').load("http://mdg.ync365.com/purchase/showcategorys",{id:id},function(){});
     }
     if(type=="showa"){
      $('#xiangs').load("http://mdg.ync365.com/purchase/showaddress",{id:id},function(){});
     }    
}

</script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js"></script>

</body>
</html>
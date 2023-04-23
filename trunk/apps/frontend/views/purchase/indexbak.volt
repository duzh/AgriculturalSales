{{ partial('layouts/page_header') }}

<!-- 主体内容开始 -->
<div class="cg_ping w960">
    {{ partial('layouts/navs_cond') }}
    <div class="choose_tab f-fl">
        <!-- 块 start -->
        <table class="cg_table bj_table">
            <tbody><tr height="30" class="title" style="border-bottom-style: none;">
                <th style="text-align:center;" width="108">发布时间</th>
                <th width="240">采购商品</th>
                <th width="156">采购地区</th>
                <th width="206">采购商</th>
            </tr>
            {% for key, pur in data %}
            <tr height="40">
                <td style="text-align:center;">{{ pur.pubtime }}</td>
                <td class="mouse_title">{{ pur.title }}</td>
                <td><a class="south-west-alt" href="javascript:;" title="{{ pur.address }}">{{ pur.areas_name }}</a></td>
                <td><font style="margin-right:10px;"><?php $shop= Mdg\Models\Purchase::checkShopExist($pur->uid);?>{% if shop %}
                    <a href="http://{{shop['shop_link']}}.5fengshou.com/store/purchaseshop/index/">{{ pur.username }}</a>{% else %}{{ pur.username }}{% endif %}</font><a style="margin-top:2px" class="btn bj_btn" href="javascript:newWindows('newquo', '确定报价', '/member/dialog/newquo/{{pur.id}}');">报价</a><font style="margin-right:0;"><i>{{ pur.countQuo }}</i>&nbsp;家报价</font></td>
            </tr>
            {% endfor %}
        </tbody></table>
        <!-- 块 end -->
        <!-- 块 start -->
        {{ form("purchase/index", "method":"get") }}
        <div class="page mb20">
             {{ pages }}
             <em>跳转到第<input type="text" class='input' name='p'  onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />页</em>
             <input type='submit' value='确定'>
        </div>
        </form>
        <!-- 块 end -->
    </div>
    <div class="choose_right f-fr" id="sroll">
        <h6>成交动态展示</h6>
        <div id="sroll_box" style="overflow:hidden;">
        <ul style="margin-top: -0.2597849332018px;">
            {% for key, order in orders %}
            <li>
                <span>{{ order['pubtime'] }}：</span><br>
                {{ order['areas_name'] }}{{ order['purname'] }}成功以&nbsp;<strong>{{ order['price'] }}元/{{ order['goods_unit'] ? goods_unit[order['goods_unit']] : ''}}</strong>&nbsp;采购了&nbsp;<strong>{{ order['quantity'] }}{{ order['goods_unit'] ? goods_unit[order['goods_unit']]  : '' }}</strong>&nbsp;{{ order['goods_name']}}<br>
                <!-- <span>&nbsp;</span> -->
            </li>
            {% endfor %}
        </ul>
        </div>
    </div>
</div>
<!-- 主体内容结束 -->
{{ partial('layouts/footer') }}

<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
<script>
/* 文字向上滚动效果 */
function scrollNews(obj) { 
    var $self = obj.find("ul"); 
    var lineHeight = $self.find("li:first").height() + 16; 
    $self.animate({ 
        "marginTop": -lineHeight + "px" 
    }, 600, function() { 
        $self.css({ 
            marginTop: 0 
        }).find("li:first").appendTo($self); 
    });
}

jQuery(document).ready(function(){
    var gyInput = $('.gy_step li input');
    inputFb(gyInput);

    /* 鼠标悬停效果 */
    $('.south-west-alt').powerTip({ placement: 'sw-alt' });
    
    /* 鼠标经过表格行的效果 */
    $("tr[class!='title']").hover(
      function () {

        $(this).prev("tr[class!='title']").css({background:"none"});
        $(this).addClass("bg_line");
      },
      function () {
        $(this).prev("tr[class!='title']").removeAttr('style');

        $(this).removeClass("bg_line");
      }
);
    
    var $sroll = $("#sroll"); 
    var scrollTimer = null;
    
    var $index = $sroll.find("li").length;
    var $height = $sroll.find("li").eq(0).height() + 16;
    var $ul = document.getElementById('sroll').getElementsByTagName('ul')[0];
    if($index < 6 || $index == 6){
        $ul.innerHTML += $ul.innerHTML;
        $("#sroll_box").css('height', $height*$index + 'px');
    }else{
        $("#sroll_box").css('height', $height*6 + 'px');
    };

    $sroll.hover(function() {
        clearInterval(scrollTimer); 
    }, function() { 
        scrollTimer = setInterval(function() { 
            scrollNews($sroll); 
        }, 1000); 
    }).trigger("mouseleave");  
});
</script>

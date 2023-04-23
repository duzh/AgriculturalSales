<meta charset="UTF-8">
{{ partial('layouts/page_header') }}

<!-- 主体内容开始 -->
<div class="cg_ping w960">
{{ partial('layouts/navs_cond') }}
    <!-- 块 start -->
    <table class="cg_table">
        <tbody><tr height="30" class="title" style="border-bottom-style: none;">
            <th style="text-align:center;" width="134">发布时间</th>
            <th width="120">品名</th>
            <th width="220">供应地</th>
            <th width="203">供应量</th>
            <th width="134">单价（元）1</th>
            <th width="350"></th>
        </tr>
        {% if data %}
            {% for key, sell in data %}
            <tr height="40">
                <td style="text-align:center;">{{ sell.pubtime }}</td>
                <td><a href="/sell/info/{{ sell.id }}">{{ sell.title }}</a></td>
                <td><a class="south-west-alt" href="javascript:;" title="{{ sell.address }}">{{ sell.areasname }}</a></td>
                <td class="mouse_title">
                    {% if sell.quantity > 0 %}
                    

                    <?php echo Lib\Func::conversion($sell->quantity); ?><?php if($sell->goods_unit){ echo $goods_unit[$sell->goods_unit]; }else{ } ?> 
                    {% else %}
                        不限
                    {% endif %}
                    <!-- {{sell.min_number > 0 ? sell.min_number : '不限' }}
                    <?php if($sell->goods_unit){ echo $goods_unit[$sell->goods_unit]; }else{ } ?> -->
                  <!--   <a class="south-west-alt" href="javascript:;" title="{{ sell.c_desc }}">{{ sell.c_desc }}</a></td> -->
                <td><strong>{{ sell.min_price }}~{{ sell.max_price}}</strong></td>
                <td>
				<a class="btn cg_btn" href="javascript:newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ sell.id }}');">采购</a>
				<a class="btn" href="/sell/info/{{ sell.id }}">详情</a>
				<a class="btn cg_btn" href="javascript:void(0);" onclick = "collectSel({{ sell.id }});">收藏</a>
				
				</td>
            </tr>
            {% endfor %}
        {% else %}
           <tr height="40" ><td style="text-align:center;" colspan="6"  ><strong>暂无相关内容！！</strong></td></tr>
        {% endif %}
    </tbody></table>
    <!-- 块 end -->
    <!-- 块 start -->
    {{ form("sell/index", "method":"get") }}
    <div class="page mb20">
        {{ pages }}
        <em>跳转到第<input type="text" name="p"  onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />页</em>
        <input  type='submit' value='确定'>
    </div>
    </form>
    <!-- 块 end -->
</div>
<!-- 主体内容结束 -->
{{ partial('layouts/footer') }}

<script type="text/javascript" src="js/inputFocus.js"></script>
<script>
// 收藏
function collectSel(sellId,flag){
	$.ajax({
		type:"POST",
		url:"/sell/collectSell",
		data:{sellId:sellId,flag:flag},
		dataType:"json",
		success:function(msg){
			alert(msg['result']);
			return;
		}
	});
}
jQuery(document).ready(function(){
    var gyInput = $('.gy_step li input');
    inputFb(gyInput);
    

    /* 鼠标悬停效果 */
    $('.south-west-alt').powerTip({ placement: 'sw-alt' });
    
    /* 采购 点击弹层效果 */
    $('.cg_btn').click(function(){
        $('.layer').show();
        $('.cg_alerter').show();
    });
    $('.cg_alerter .submit_btn').click(function(){
        $('.cg_alerter').hide();
        $('.hz_alerter').show();
    });
    
 
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
});
</script>

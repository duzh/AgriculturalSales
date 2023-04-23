<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
	        <span><a href="/">首页</a>&nbsp;&gt;&nbsp;<a href="/member">个人中心 </a>&nbsp;&gt;&nbsp;我买入的订单</span>
	    </div>
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
        <div class="center-right f-fr">
            <div class="my-buyOrder f-oh pb30">
                <div class="title f-oh">
                    <span>我买入的订单</span>
                </div>
                <form action="/member/ordersbuy/index" method="get">
                <div class="m-box clearfix">
                    <div class="message clearfix">
                        <font>订单成交时间：</font>
                        <input id="d4311" class="Wdate" type="text" value='{{ stime }}' name="stime"  onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4312\')||\'2020-10-01\'}'})" />
                        <i>至</i>
                        <input id="d4312" class="Wdate" type="text" value='{{ etime }}' name="etime" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'2020-10-01'})"/>
                    </div>
                    <div class="message clearfix">
                        <font>订单状态：</font>
                        <select name="state">
                            <option value="0">全部状态</option>
                            {% for key, val in orders_state %}
                            <option value="{{ key }}" {% if (state == key) %} selected="selected" {% endif %} >{{ val }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <input class="btn" type="submit" value="搜  索">
                </div>
                <table cellpadding="0" cellspacing="0" width="100%" class="list">
                    <tr height="31">
                        <th width="480">
                            <div class="m-left">采购商品信息</div>
                        </th>
                        <th width="160">订单状态</th>
                        <th width="284">
                            <span class="m-right">操作</span>
                        </th>
                    </tr>
                    {% if data %}
                    {% for key, order in data['items'] %}
                    <tr height="28">
                        <td colspan="3">
                            <div class="m-box clearfix">
                                <span class="num f-fl">订单号：{{ order.order_sn }}</span>
                                <span class="sj f-fr">下单时间：{{ date('Y-m-d H:i:s', order.addtime) }}</span>
                            </div>
                        </td>
                    </tr>
                    <tr height="139">
                        <td>
                            <div class="m-left">
                                <dl class="clearfix">
                                    <dt class="f-fl">
                                    <?php 
                                    if(isset($order->sellid) && $order->sellid!=0 && Mdg\Models\Sell::getSellThumb($order->sellid) ) { 
                                        ?>
                                    <?php  $sell=Mdg\Models\Sell::getSellThumb($order->sellid); 
                                    ?>
                                    <img src="{{ sell }}" height="120" width="120">
                                    <?php }else { ?>

                                    <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" height="120" width="120">
                                    <?php } ?>
                                    </dt>
                                    <dd class="f-fl">
                                        商品名称：<a style="display:inline-block;" href="/sell/info/{{order.sellid}}">{{ order.goods_name }}</a><br />
                                        单品价格：{% if order.state == 2 %}<?php $orderprice=Mdg\Models\Sell::getprice($order->sellid); if($orderprice){ echo $orderprice;}else{ echo $order->price;}?> {% else %}{{ order.price }}{% endif %}元/{{ goods_unit[order.goods_unit] }}<br />
                                        购买数量：{{ order.quantity }}{{ goods_unit[order.goods_unit] }}
                                    </dd>
                                </dl>
                            </div>
                        </td>
                        <td align="center">{{ orders_state[order.state] }}</td>
                        <td>
                            <span class="m-right">
                                <a href="/member/ordersbuy/info/{{ order.id }}">订单详情</a>
                                {% if (order.state == 2) %}
                                <a  href="javascript:cancelOrder({{ order.id }})">取消订单</a>
                                {% endif %}
                                
                                {% if (order.state == 3) %}                                   
                                <!--11<a  href="/member/ordersbuy/payorder/{{ order.id }}"   onclick="showDistplay({{ order.id}})" target="_blank" >去付款</a>-->
                                <a  href="/member/ordersbuy/payorderpro?gate_id=1&order_id={{ order.id }}"   onclick="showDistplay({{ order.id}})" target="_blank" >去付款</a>
                                {% endif %}
                                
                                {% if (order.state == 5) %}
                                <a  href="javascript:void(0);" onclick="finishOrder({{ order.id }})">确认收货</a>
                                {% endif %}

                                {% if (order.state == 6 and !order.activity_id ) %}
                                <?php $order_id=$order->id?$order->id:$order->sellid; $comments=Mdg\Models\GoodsComments::WOrderComments($order_id,$user_id);if(!$comments){
                                echo "<a class='f-db pingfen mt10' href='javascript:;' onclick='pingfen($order->purid, $order->sellid,$order->id)'>去评价</a>";}else{echo '已评论';}?>
                                {% endif %}
                                
                            </span>
                        </td>
                    </tr>
                    {% endfor %}
                    {% endif %}
                </table>
                <!-- 分页 -->
                {% if total_count >1 and total_count!=0 %}
                <div class="esc-page mt30 mb30 f-tac f-fr mr30">
                        {{ pages }}
                <span>
                    <label>去</label>
                    <input type="text" name='p' value="1" id="p"  onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))">
                    <label>页</label>
                </span>
                <input class="btn" type="submit" value="确定" onclick="go()">

                </div>
                {% endif %}  
                </form>   
                        
               </div>
               


        </div>

    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<div id='show' style='display:none'>

<style>
.catchUser_layer{ width:100%; height:100%; background:#000; opacity:0.5; filter:alpha(opacity:50); position:fixed; z-index:998; left:0; top:0;}
.catchUser{ width:429px; height:219px; position:fixed; left:50%; top:50%; margin-top:-160px; margin-left:-215px; z-index:999;}
.catchUser .close_btn{ display:block; width:20px; height:20px; background:url(images/close_btn.png) center center no-repeat; position:absolute; right:10px; top:6px; text-indent:-10000em;}
</style>

    <div class="catchUser_layer"></div>
    <div class="catchUser">
        <a class="close_btn" href="/member/ordersbuy">关闭</a>
        <div class="title"></div>
        <div class="catchTip" style='border-bottom:0px'>
            <p></p>
            <div class="btns clearfix">
                <a class="f-db f-fl memberInfo" href="/member/ordersbuy?p=<?php echo isset($_GET['p']) ? $_GET['p'] : 1;?>">已完成支付</a>
                <a class="f-db f-fr memberInfo "  id='memberInfo' href="/member/ordersbuy?p=<?php echo isset($_GET['p']) ? $_GET['p'] : 1;?>">支付遇到问题</a>
            </div>
        </div>
    </div>
</div>

    <!-- 底部 -->
    

    <!-- 评分弹框 -->
    <div class="pingfen-layer" ></div>
    <div class="dialog_pingfen"  >

        <a class="close-btn" href="javascript:;"></a>
        <div class="title">商品评价</div>
        <div class="message clearfix">
            <font>商品名称：</font>
            <div class="msg-box" id="msg_box_title"></div>
        </div>
        <div class="message clearfix">
            <font>商品图片：</font>
            <div class="msg-box">
                <img src="" height="114" width="114" id="imgs">
            </div>
        </div>
        <div class="message clearfix">
            <font>供应商 ：</font>
            <div class="msg-box" id="msg_box_uname"></div>
        </div>
        <div class="line"></div>
        <div class="add-message clearfix">
            <font class="name"><label>*</label>商品描述相符程度评分：</font>
            <div class="msg-box" id="pingfen">
                <div class="star">
                    <a href="javascript:;" class="star1" _index="1"></a>
                    <a href="javascript:;" class="star2" _index="2"></a>
                    <a href="javascript:;" class="star3" _index="3"></a>
                    <a href="javascript:;" class="star4" _index="4"></a>
                    <a href="javascript:;" class="star5" _index="5"></a>
					<input type="hidden" name="sellid" id="sellid" value="0">
					<input type="hidden" name="purid" id="purid" value="0">
                    <input type="hidden" name="orderid" id="orderid" value="0">
					<input type="hidden" name="decribe_score" id="decribe_score" value='0'>
                </div>
                <i></i>
            </div>
        </div>
        <div class="add-message clearfix">
            <font class="name"><label>*</label>评价内容：</font>
            <div class="msg-box">
                <textarea name="comment" id="comment"></textarea>
                <label>
                    <input type="checkbox" name="anonym_flag" id="anonym_flag" value="0" onclick="anonym()"/>
                    <font>匿名</font>
                </label>
            </div>
        </div>
        <input class="pingfen-btn" type="button" value="提交评价" onclick="subcomments()"/>

    </div>

</body>
</html>
<script type="text/javascript">

function go(){
var p=$("#p").val();
 var count = {{total_count}};
 if(p>count){
    $("#p").val(count);
 }
}

/* 显示 */
function showDistplay(oid) {

    $('#memberInfo').attr("href" , "/member/ordersbuy/info/"+oid);
   $('#show').show();
}
$(function () {
    /* 显示 */
        $('#pingfen .star a').click(function(){
            $(this).parent().find('a').removeClass('active');
            $(this).addClass('active');
            var num = $(this).attr('_index');
            $(this).parent().parent().find('i').text(num + '分');
            $("#decribe_score").attr('value',num);
        });
        $('.dialog_pingfen .close-btn').click(function(){
            $('.pingfen-layer').hide();
            $('.dialog_pingfen').hide();
        });
        
})
	
	function pingfen(purid, sellid,orderid){
        
		$("#purid").attr('value',purid);
		$("#sellid").attr('value',sellid);
        $("#orderid").attr('value',orderid);
		$.ajax({
			type:"POST",
			url:"/goodscomments/getGoodsInfo",
			data:{'purid':purid,'sellid':sellid},
			dataType:"json",
			success:function(data){		
					if (data.status=='1000')
					{
							$('.pingfen-layer').show();
							$('.dialog_pingfen').show();
							$("#msg_box_title").html(data.data.title);
							$("#msg_box_uname").html(data.data.uname);
							$("#imgs").attr('src',data.data.thumb);
					}else{
						alert(data.msg)
					}
			}
		});
	}
	
	function subcomments(){
		var decribe_score = $('#decribe_score').val();
        var comment = $('#comment').val();

		var purid = $('#purid').val();
		var sellid = $('#sellid').val();
        var orderid = $('#orderid').val();
		var anonym_flag = $('#anonym_flag').val();
		var msg = '';
		if (decribe_score<=0)
		{
			msg += '请先评分！'+"\n";
		}
		if (!comment)
		{
			msg += '请填写评价内容！'+"\n";
		}
        if(document.getElementById('comment').value.length >= 501 || document.getElementById('comment').value.length <= 9){
            msg += '请输入10-500字符'+"\n";
        }

		if (msg != '')
		{
			alert(msg);
		}else{
            
				$.ajax({
				type:"POST",
				url:"/goodscomments/create",
				data:{'purid':purid,'sellid':sellid,'decribe_score':decribe_score,'comment':comment,'anonym_flag':anonym_flag,'orderid':orderid},
				dataType:"json",
				success:function(data){		
						if (data.status=='1000')
						{
							alert(data.msg);
							window.location.reload();
						}else{
							alert(data.msg);
						}
				}
			});
		}
	}

	function anonym(){
		var is_anonym = $('#anonym_flag').val();
		if (is_anonym==0)
		{
			$('#anonym_flag').attr('value',1);
		}else{
			$('#anonym_flag').attr('value',0);
		}
	}
function cancelOrder(oid) {
    $.getJSON('/member/ordersbuy/cancel', { oid : oid }, function(data) {
        alert(data.msg);
        if(data.state) {
            window.location.reload();
        } 
    });
}

function finishOrder(oid){
$.getJSON('/member/ordersbuy/finish', { oid : oid }, function(data) {
    alert(data.msg);
    if(data.state) {
        window.location.reload();
    } 
});
}
</script>

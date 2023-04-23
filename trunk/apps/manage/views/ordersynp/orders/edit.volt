
<table>
    <tr>
        <td align="right">
            <label for="title">订单号</label>
        </td>
        <td align="left">
            {{order.order_sn}}
        </td>
    </tr>
     <tr>
        <td align="right">
            <label for="id">订单状态</label>
        </td>
        <td align="left">
             {{orderstate[order.state]}}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="title">购货人</label>
        </td>
        <td align="left">
               {{order.purname}}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="category">下单时间</label>
        </td>
        <td align="left">
           {{date("Y-m-d H:i:s",order.addtime)}}
        </td>
    </tr>
<!--     <tr>
        <td align="right">
            <label for="thumb">支付方式</label>
        </td>
        <td align="left">
              支付方式
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="price">付款时间</label>
        </td>
        <td align="left">
            付款时间
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="quantity">发货时间</label>
        </td>
        <td align="left">
            发货时间
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="areas">配送方式</label>
        </td>
        <td align="left">
            配送方式
        </td>
    </tr> -->
    <tr>
        <td align="right">
            <label for="address">供货商信息</label>
        </td>
        <td align="left">
                
        </td>
    </tr>
     <tr>
        <td align="right">
            <label for="address">会员帐号</label>
        </td>
        <td align="left">
                {{order.sphone}}
               
        </td>
    </tr>
     <tr>
        <td align="right">
            <label for="address">姓名</label>
        </td>
        <td align="left">
             {{order.purname}}
        </td>
    </tr>
     <tr>
        <td align="right">
            <label for="address">地址</label>
        </td>
        <td align="left">
              <?php echo Mdg\Models\UsersExt::getaddress($order->purid) ?>  
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="stime">采购商信息</label>
        </td>
        <td align="left">
            
        </td>
    </tr>
     <tr>
        <td align="right">
            <label for="address">会员帐号</label>
        </td>
        <td align="left">
                {{order.sphone}}
               
        </td>
    </tr>
     <tr>
        <td align="right">
            <label for="address">姓名</label>
        </td>
        <td align="left">
             {{order.sname}}
        </td>
    </tr>
     <tr>
        <td align="right">
            <label for="address">地址</label>
        </td>
        <td align="left">
              <?php echo Mdg\Models\UsersExt::getaddress($order->suserid) ?>  
        </td>
    </tr>

    <tr>
        <td align="right">
            <label for="etime">收货人姓名</label>
        </td>
        <td align="left">
            {{order.sname}}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="breed">收货人电话</label>
        </td>
        <td align="left">
              {{order.sname}}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="spec">收货人地址</label>
        </td>
        <td align="left">
               {{order.areas_name}}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="state">商品信息</label>
        </td>
        <td align="left">
            
        </td>
    </tr>
     <table>
            <tbody><tr height="28" class="title">
                <th width="422">商品名称</th>
                <th width="160">供货价格</th>
                <th width="85">采购数量</th>
                <th width="85">单位</th>
                <th width="90">小计</th>
            </tr>
          
            <tr>
                <td>{{order.goods_name}}</td>
                <td>{{order.price}}</td>
                <td>{{order.quantity}}</td>
                <td><?php echo Mdg\Models\Sell::$type3[$order->goods_unit]; ?></td>
                <td>{{order.total}}</td>
            </tr>
         
        </tbody>
            <tr>费用总计:{{order.total}}</tr>
    </table>
    {{ form("orders/updatestate/", "method":"post","id":"myform") }}
    <tr>操作信息</tr>
    <tr>操作备注:{{  text_area("content", "type" : "numeric") }}</tr>
    <td>当前可操作</td>
    <input type="hidden" name="order_id" value="{{order.id}}">
    <input type="hidden" name="type" id="type" >
    <td><input type="buttton" value="卖方确定" onclick="confirm('confirm')"></td>
    <td><input type="buttton" value="取消" onclick="confirm('remove')"></td>
    </form>



</table>
<table>
    <tbody>
        <tr height="28" class="title">
            <th width ="422">操作者  </th>
            <th width ="160">操作时间</th>
            <th width ="85" >订单状态</th>
            <th width ="85" >付款状态</th>
            <th width ="90" >发货状态</th>  
            <th width ="90" >备注    </th>
        </tr>
        {% if orderlog is defined %}
            {% for order in orderlog %}   
        <tr>
            <td>{{order.operationname}}</td>
            <td>{{date("Y-m-d H:i:s",order.addtime)}}</td>
            <td>{{orderstate[order.state]}}</td>
            <td>付款状态</td>
            <td>发货状态</td>
            <td>{{order.demo}}</td>
        </tr>
              {% endfor %}
        {% endif %}

    </tbody>
            
</table>
<script>
function confirm(state){
    if($("#content").val()!=""){
        $("#type").val(state);
        $("#myform").submit();
    }else{
        alert("操作备注不能为空");
    }    
}
</script>
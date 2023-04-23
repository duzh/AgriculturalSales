<style>
ul li {line-height: 37px;}
</style>
<div class="dialog" style="width:425px; padding-bottom:3px;">
    <form action="/manage/orders/changestimepro" method="post" id="editprice">
    
          

    <div class="message">
      <ul>
       
            <li><span class="label">{{str}}：</span>
               
               
                   <input id="d411" class="Wdate"  type="text" name="except_shipping_time" onfocus="WdatePicker({skin:'whyGreen'})"  value="{% if orders.except_shipping_time > 0 %}{{date("Y-m-d",orders.except_shipping_time)}}{% endif %}" />
                <i id="c_price"></i>
            
                
            </li>
    
            <li><span class="label">&nbsp;</span><em>
                <input type="hidden" name="order_id" value="{{ orders.id }}" />
                <input class="sub" type="submit" value="确认修改" /></em>
            </li>
        </ul>
        
    </div>
    </form>
</div>




<style>
ul li {line-height: 37px;}
.dialog li em{ width:auto;}
</style>
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<div class="dialog" style="width:480px; height:307px; overflow:hidden; padding-bottom:3px;">
    <form action="/member/dialog/savepricepro" method="post" id="editprice">
    <div class="message">
      <ul>
          <li><span class="label">供应编号：</span><em>{{ sell.sell_sn }}</em></li>
          <li><span class="label">发布时间：</span><em>{{ date('Y-m-d H:i:s', sell.updatetime) }}</em></li>
          <li><span class="label">供应时间：</span><em>{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}</em></li>
          <li><span class="label">供应商品：</span><em>{{ sell.title }}</em></li>
          <li>
                <span class="label">供应量：</span>
                <em class="clearfix" style="width:364px;">
                  <input class="mr10 f-fl" type="text" name="quantity"  id="quantity" value="{{ quantity }}" data-target="#c_quantity"/>
                  <font style="display:inline-block;line-height:37px;float:left;">
                    <?php 
                      echo isset($goods_unit[$sell->goods_unit]) ? $goods_unit[$sell->goods_unit] : '';
                    ?>
                  </font>                
                  <i id="c_quantity"></i>
                </em>  
         </li>
          <li><span class="label">&nbsp;</span><em>
                <input type="hidden" name='min_number' id='min_number' value='{{ min_number }}'>
                <input type="hidden" name="sellid" value="{{ sell.id }}" />
                <input class="fu_btn submit_btn" style="margin:0px!important;margin-bottom:5px;" type="submit" value="确认修改" /></em>
            </li>
        </ul>
        
    </div>
    </form>
</div>

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery.form.js"></script>

<script>

$('#editprice').validator({
        groups: [{
            fields: 'quantity',
            callback: function($elements){
                var min_number = $('#min_number').val();
                var quantity = $('#quantity').val();
                if(parseFloat(quantity) < parseFloat(min_number) && parseFloat(quantity) > 0 ) {
                    return '供应量不能小于起购量';
                }
                return true;
            },
            target: '#c_quantity'
        }],
 rules: {
            nimei  : [/^([0-9])+(\.([0-9]){1,2})?$/, '保留两位小数']
        },
 fields: {
          'quantity':'供应量:required;float[*];nimei;length[1~8];checkNum;'
        }
});
    


</script>
<style type="text/css">
.dialog li{margin-bottom: 5px!important;}
</style>
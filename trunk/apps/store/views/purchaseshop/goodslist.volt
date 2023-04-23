{{ partial('layouts/page_header') }}

<div class="contianer pb30">
   {{ partial('layouts/nav_purchase') }}
    <div class="shop_symm w960 clearfix">
        <div class="symm_left f-fl">
            {{ partial('layouts/category_left') }}
            {{ partial('layouts/shoper_left') }}
            {{ partial('layouts/comments_left') }}
        </div>
        <!-- 右侧 start -->
        <div class="symm_right f-fr">
            <div class="shop_goods">
                <h6>采购产品</h6>
                <div class="list clearfix">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr height="30" class="title">
                            <th width="25%">采购商品</th>
                            <th width="30%">采购地区</th>
                            <th width="20%">采购商</th>
                            <th width="25%">
                                
                            </th>
                        </tr>

                         {% for key, item in purchaselist['items'] %}
                         <tr height="40">
                            <td>{{item['title']}}</td>
                            <td><?php echo Lib\Utils::getC($item["areas_name"]); ?></td>
                            <td>{{item['username']}}</td>                           
                            <td>                            
                                <a style="margin-right:14px;" class="btn bj_btn" href="javascript:newWindows('newquo', '确定报价', '/store/qprice/newquo/{{item['id']}}');" >报价</a>
                                <font style="margin-right:0;"><?php echo Mdg\Models\PurchaseQuotation::countQuo($item["id"])?>家报价</font>
                            </td>                           
                        </tr>
                        {% endfor %}
             
                </table>
                </div>
            </div>
            {{ form("purchaseshop/goodslist", "method":"get") }}
            <div class="page mb20">
            {{ purchaselist['pages'] }} 
            <!-- {{pages }} -->
            <em>跳转到第<input type="text" name="p" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"/>页</em>
            <input class="btn" type='submit' value='确定'>
            </div>
            </form>
            
        </div>

        <!-- 右侧 end -->
    </div>

</div>

<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->
<style>
.shop_goods table .title{ background:rgb(156, 204, 94); color:#fff;}
.shop_goods table th, .shop_goods table td{ text-align: center;}
.shop_goods table td .btn{ display:inline-block; margin-left:30px; float:left; width:46px; height:21px; text-align:center; line-height:21px; background:url(http://yncstatic.b0.upaiyun.com/mdg/images/level_btn1.png) no-repeat; background-position:-1px -74px; margin-top:2px; margin-right:6px;}
.shop_goods table td font{ display:block; float:left; height:25px; line-height:25px;}
.shop_goods table td .btn:hover{ background-position:-1px -118px; text-decoration:none; color:#fff;}
</style>
</body>
</html>

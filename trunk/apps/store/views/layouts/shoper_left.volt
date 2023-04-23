<!-- 左侧 start -->
<!-- 供应商资料 start -->

            <div class="supplier">
                <h6>
                    {% if shop.business_type==1 %}供应商资料{% endif %}
                    {% if shop.business_type==2 %}采购商资料{% endif %}
                </h6>
                <ul class="information">
                    <li>
                        <font>姓名：</font>
                        <p>{{shop.contact_man}}</p>
                    </li>
                    <li>
                        <font>电话：</font>
                        <p id="telChange">
                            <i>{{shop.contact_phone}}</i>
                            <label>{{ shop.contact_phone}}</label>
                            <a href="javascript:;">查看</a>

                        </p>
                    </li>
                    <li>
                        <font>地址：</font>
                        <p>
                            {% for key, item in areas %}
                                {{item['name']}}
                            {% endfor %}
                        </p>
                    </li>
                    <li>
                        <font>主营：</font>
                        <p>
                            <!-- <span>{{shop.main_product}}</span> -->
                            <span><?php echo Mdg\Models\ShopCoods::getgoodsname($shop->shop_id); ?></span>
                        </p>
                    </li>
                </ul>
            </div>
<script>
$(function(){
    var text = $('#telChange i').text();
    var val = text.substring(0,3) + '****' + text.substring(7,11);
    $('#telChange i').text(val);
    $('#telChange a').click(function(){
        $('#telChange i').hide();
        $('#telChange label').text(text);
        $('#telChange label').show();
        $(this).hide();
    });
});


</script>
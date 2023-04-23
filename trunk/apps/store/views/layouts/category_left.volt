<!-- 左侧 start -->
<!-- 产品分类 start -->
            <div class="category" style="margin-bottom:20px">
                <h6>产品分类</h6>
                {% if shop.business_type==1 %}
                {% if cate %}
                <ul class="information" id="symm_category">
                    {% for key,val in cate %}
                    <li class="title"><span class="arrow_top">{{val['title']}}</span></li>
                    
                    <li class="list"  <?php if($category_id) {?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
                        {% for key,item in val['children'] %}
                        <ul>
                            <li <?php if($category_id == $item['id']){ echo "class='active'"; }?>><a href="/store/shoplook/goodslist/{{item['id']}}"><span>{{item['title']}}</span>
                            <em>(<?php $count =  Mdg\Models\Sell::categorygoods($user_id,$item['id']); echo $count;  ?>)</em>
                            </a></li>
                        </ul>
                        {% endfor %}
                    </li>                    
                    {% endfor %}
                </ul>
                {% endif %}
                {% endif %}

                {% if shop.business_type==2 %}
                {% if purchasecate %}
                <ul class="information" id="symm_category">
                    {% for key,val in purchasecate %}
                    <li class="title"><span class="arrow_top">{{val['title']}}</span></li>
                    
                    <li class="list"  <?php if($category_id) {?>style="display:block;"<?php }else{?>style="display:none;"<?php }?>>
                        {% for key,item in val['children'] %}
                        <ul>
                            <li <?php if($category_id == $item['id']){ echo "class='active'"; }?>><a href="/store/purchaseshop/goodslist/{{item['id']}}"><span>{{item['title']}}</span>
                            <em>(<?php $count =  Mdg\Models\Purchase::categorygoods($user_id,$item['id']); echo $count;  ?>)</em>
                            </a></li>
                        </ul>
                        {% endfor %}
                    </li>
                    
                    {% endfor %}
                </ul>
                {% endif %}
                {% endif %}
            </div>

            
<!-- 左侧 end -->

<script>
$(function(){
    $('#symm_category .title span').toggle(function(){
        $(this).removeClass('arrow_down');
        $(this).parent().next('.list').slideDown();        
    }, function(){
        $(this).addClass('arrow_down');
        $(this).parent().next('.list').slideUp();
    });
});


</script>

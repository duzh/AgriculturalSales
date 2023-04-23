<!-- 左侧 start -->
<!-- 栏目名称 start -->
            {% if is_parent %}
            <div class="category" style="margin-bottom:20px">
                <h6>{{is_parent}}</h6>
                <ul class="information" id="symm_category">
                    {% for key,val in cate %}
                   <!--  {% if val['children'] %}
                    <li class="title"><span class="arrow_top">{{val['col_name']}}</span></li>
                    {% endif %} -->
                    
                    <li class="list"
                    {% if val['id']==id %} style="display:block;" {% else %} style="display:none;" {% endif %}>
                        {% for key,item in val['children'] %}
                        <ul>
                            <li <?php if($columns_id == $item['id']){ echo "class='active'"; }?>>
                                {% if shop.business_type==1 %}<a href="/store/shoplook/columns/{{id}}?cid={{item['id']}}">{% endif %}
                                {% if shop.business_type==2 %}<a href="/store/purchaseshop/columns/{{id}}?cid={{item['id']}}">{% endif %}
                                <span>{{item['col_name']}}</span>
                            </a></li>
                        </ul>
                        {% endfor %}
                    </li>
                    
                    {% endfor %}
                </ul>
                
            </div>
{% endif %}

<!-- 左侧 end -->

<script>
// $(function(){
//     var text = $('#telChange i').text();
//     var val = text.substring(0,3) + '****' + text.substring(8,11);
//     $('#telChange i').text(val);
//     $('#telChange a').click(function(){
//         $('#telChange i').hide();
//         $('#telChange label').text(text);
//         $('#telChange label').show();
//         $(this).hide();
//     });
// });

$(function(){
    $('#symm_category .title span').toggle(function(){
        $(this).addClass('arrow_down');
        $(this).parent().next('.list').slideToggle();
    }, function(){
        $(this).removeClass('arrow_down');
        $(this).parent().next('.list').slideToggle();
    });
});
</script>

<div class="message">
	<div class="m_title">详细描述</div>
    <table class="m_table">
    	<tr height="24">
        	<td width="223">供应编号：{{ sell.sell_sn }}</td>
        	<td width="224">产品品名：{{ sell.title }}</td>
        	<td>产品品种：{{sell.breed ? sell.breed : '暂无' }}</td>
        </tr>
    	<tr height="24">
        	<td>供应时间：{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}</td>
        	{% if sell.spec %}<td colspan="2">{{ sell.spec }}</td>{% endif %}
        </tr>
    </table>
    <p class="line">&nbsp;</p>
    <p> 

       {% if sell.scontent  %}
          {{ sell.scontent.content }}
       {% else %}
         {{contents}}
       {% endif %}

    </p>
</div>
<!-- 块 end -->
 

{% if tagquality and  ( tagquality['quality_level'] > 0  or tagquality['pesticide_residue'] > 0 or tagquality['heavy_metal'] > 0 or tagquality['is_gene'] or tagquality['inspector'] or tagquality['inspect_time'] or tagquality['certifying_agency']) %}
<!-- 块 start -->
<div class="message">
    <div class="m_title">商品检测</div>
    <div style="padding:20px 0;">
    <p>
     <br>
    质量等级：{{tagquality['quality_level']}}
    <br>
    农残检测：{{tagquality['pesticide_residue']}}
    <br>
    重金属含量：{{tagquality['heavy_metal']}}
    <br>
    转基因：{{ tagquality['is_gene'] ? '是' : '否'}}
    <br>
    检验员：{{tagquality['inspector']}}
    <br>
    检验时间：{{ tagquality['inspect_time'] ? date('Y-m-d ' , tagquality['inspect_time'] ) : '' }}
    <br>
    安全检机构名称：{{tagquality['certifying_agency']}}
    <br>
    安全鉴定机构：<br>
    {% if tagquality['certifying_file'] %}
    <img src="{{tagquality['certifying_file']}}" alt="安全鉴定机构" width='201px' height='201px'>
    {% endif %} 
    </p>

    </div>
</div>
<!-- 块 end -->
{% endif %}

{% if product and ( product['product_place'] or product['manure'] or product['pollute'] or product['breed']  or product['process_type'] or product['manure_type'] or (product['manure_amount'] > 0) or product['pesticides_type'] or (product['pesticides_amount'] > 0) )   %}
<!-- 块 start -->
<div class="message">
	<div class="m_title">生产环境</div>
    <div style="padding:20px 0;">
        <p>
            <br>
            生产基地位置：{{product['product_place']}}
            <br>
            土地肥力：{{product['manure']}}
            <br>
            土地“三废”：{{product['pollute']}}<br>
            品种名：{{product['breed']}}<br>
            种子质量指标： {{product['seed_quality']}}<br>
            加工方式： {{product['process_type']}}<br>
            肥料种类：{{product['manure_type']}} 用量：{{product['manure_amount']}}<br>
            农药种类：{{product['pesticides_type']}} 用量：{{product['pesticides_amount']}}<br>
        </p>

        <?php  if( count($tagsell['imgList']) > 0) {?>

        <!-- 滚动 start -->
        <div class="schj_list f-pr mt20" id="schj_wufeng">
            <ul class="pa clearfix">
                    {% for img in tagsell['imgList'] %}
                    <li><a href="#"><img src="{{img['path']}}" width="151" height="112" /></a></li>
                    {% endfor %}
            </ul>
        </div>
         <?php } ?>
        <!-- 滚动 end -->
    </div>
</div>
<!-- 块 end -->
{% endif %}


<?php if(isset($plantList[0]) && ($plantList[0]['ptype'] || $plantList[0]['begin_date'] || $plantList[0]['end_date'] || $plantList[0]['weather'] || $plantList[0]['comment'] ||  $plantList[0]['imgList']  ) ) { ?>
<!-- 块 start -->
<div class="message">
	<div class="m_title">生产作业</div>
    <div style="padding:20px 0;">

        <table cellpadding="0" cellspacing="0" width="100%" class="zyxc_img">
        	<tr height="40">
            	<th width="15%">作业类型</th>
            	<th width="15%">开始时间</th>
            	<th width="15%">结束时间</th>
            	<th width="15%">天气状况</th>
            	<th width="30%">作业内容</th>
            	<th width="10%">相册</th>
            </tr>
            {% for row in plantList %}
            <tr height="80">
            	<td>{{ row['ptype']}}</td>
            	<td>{{ row['begin_date'] ? row['begin_date'] : ''}}</td>
            	<td>{{ row['end_date'] ? row['end_date'] : ''}}</td>
            	<td>{{ row['weather']}}</td>
            	<td>
                	<p>{{ row['comment']}}</p>
                </td>
            	<td>
                  <a href="/sell/tagplant?sid=<?php echo base64_encode($sell->id); ?>&sname=<?php echo base64_encode($sell->title); ?>&pid={{row['id']}}&t=<?php echo base64_encode($row['operate_type'])?>" >查看</a>
                </td>
            </tr>
            {% endfor %}
        </table>
    </div>
</div>
<!-- 块 end -->
<?php } ?>


<script type="text/javascript">
$(function(){
    //生产作业无缝滚动
    (function(){
        var wufeng=document.getElementById('schj_wufeng');
        var wufeng_ul=wufeng.children[0];
        var wufeng_li=wufeng_ul.children;
        var speed=-2;
        var wufeng_timer = null;
        
        wufeng_ul.innerHTML+=wufeng_ul.innerHTML;
        wufeng_ul.style.width=(wufeng_li[0].offsetWidth + 8)*wufeng_li.length+'px';
        
        function wufeng_move()
        {
            wufeng_ul.style.left=wufeng_ul.offsetLeft+speed+'px';
            
            if(wufeng_ul.offsetLeft<-wufeng_ul.offsetWidth/2)
            {
                wufeng_ul.style.left='0';
            }
            else if(wufeng_ul.offsetLeft>=0)
            {
                wufeng_ul.style.left=-wufeng_ul.offsetWidth/2+'px';
            }
        }
        wufeng_timer=setInterval(wufeng_move, 30);
        
        wufeng.onmouseover=function ()
        {
            clearInterval(wufeng_timer);
        };
        wufeng.onmouseout=function ()
        {
            wufeng_timer=setInterval(wufeng_move, 30);
        };
    })();
});

</script>
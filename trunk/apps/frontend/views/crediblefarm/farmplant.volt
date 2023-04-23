<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/trust-farm.css">
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/trust-farm.js"></script>
{{ partial('layouts/orther_herder') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		 <!--农场头部--> 
		{{ partial('layouts/farm_header') }}
		
		<input type="hidden" name="count" id="count" value="{{num}}">
		{% if data_info %}
		<?php $i=-1;?>
		{% for key,val in data_info %}
		<?php $i++;?>
		<div class="grow-process">
			<div class="title">{{goodsname[key]}}种植过程</div>

			<!-- 过程列表 -->
			<div class="timeline timeline-process timeline{{i}}">

				<ul class="dates clearfix">
				{% for k,item in val %}
					<li {% if k==0 %}class="active" {% endif %}>
						<a href="javascript:;">{{date("Y/m/d",item['picture_time'])}}</a>
					</li>
				{% endfor %}
				</ul>
				<div class="box">
					<ul class="issues">
                    {% for k,item in val %}
                    <li>
                        <div class="imgBox f-oh">
                            <div class="imgs f-oh f-fl">
                                {% if item['picture_path'] %}
                        <img src="{{ constant('IMG_URL') }}{{item['picture_path']}}" height="200" width="200">
                        {% else %}
                        <img src="http://yncstatic.b0.upaiyun.com//mdg/version2.4/images/detial_b_img.jpg" height="200" width="200">
                        {% endif %}
                            </div>
                            <div class="imgCon f-fr">
                                <h6>{{item['title']}}</h6>
                                <p>{{item['desc']}}</p>
                            </div>
                        </div>
                    </li>
                    {% endfor %}
					</ul>
				</div>
				<a href="javascript:;" class="tl-prev"></a>
				<?php end($val);$plant_count = key($val);?>
				{% if plant_count!=0 %}
				<a href="javascript:;" class="tl-next"></a>
				{% endif %}
			</div>
		</div>
		{% endfor %}
        {% endif %}	
	</div>
</div>
{{ partial('layouts/orther_footer') }}
<div class="box">
	<div class="letter" >
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fA_p1" {% if firstStr == 'A' %}class="active"{% endif %}>A</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fB_p1" {% if firstStr == 'B' %}class="active"{% endif %}>B</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fC_p1" {% if firstStr == 'C' %}class="active"{% endif %}>C</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fD_p1" {% if firstStr == 'D' %}class="active"{% endif %}>D</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fE_p1" {% if firstStr == 'E' %}class="active"{% endif %}>E</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fF_p1" {% if firstStr == 'F' %}class="active"{% endif %}>F</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fG_p1" {% if firstStr == 'G' %}class="active"{% endif %}>G</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fH_p1" {% if firstStr == 'H' %}class="active"{% endif %}>H</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fI_p1" {% if firstStr == 'I' %}class="active"{% endif %}>I</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fJ_p1" {% if firstStr == 'J' %}class="active"{% endif %}>J</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fK_p1" {% if firstStr == 'K' %}class="active"{% endif %}>K</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fL_p1" {% if firstStr == 'L' %}class="active"{% endif %}>L</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fM_p1" {% if firstStr == 'M' %}class="active"{% endif %}>M</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fN_p1" {% if firstStr == 'N' %}class="active"{% endif %}>N</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fO_p1" {% if firstStr == 'O' %}class="active"{% endif %}>O</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fP_p1" {% if firstStr == 'P' %}class="active"{% endif %}>P</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fQ_p1" {% if firstStr == 'Q' %}class="active"{% endif %}>Q</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fR_p1" {% if firstStr == 'R' %}class="active"{% endif %}>R</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fS_p1" {% if firstStr == 'S' %}class="active"{% endif %}>S</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fT_p1" {% if firstStr == 'T' %}class="active"{% endif %}>T</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fU_p1" {% if firstStr == 'U' %}class="active"{% endif %}>U</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fV_p1" {% if firstStr == 'V' %}class="active"{% endif %}>V</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fW_p1" {% if firstStr == 'W' %}class="active"{% endif %}>W</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fX_p1" {% if firstStr == 'X' %}class="active"{% endif %}>X</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fY_p1" {% if firstStr == 'Y' %}class="active"{% endif %}>Y</a>
		<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{arr['a']}}_fZ_p1" {% if firstStr == 'Z' %}class="active"{% endif %}>Z</a>
	</div>
	<div class="word f-pr" >
		<div class="more-btn">
			<a href="javascript:;">更多</a>
		</div>
		<div class="all-word" >
			{% for key, parent in parentData %}
			<a href="{{url}}/mc{{arr['mc']}}_a{{arr['a']}}_c{{parent.id}}_f{{arr['f']}}_p1" {% if parent.id == c %}class="active"{% endif %} >{{ parent.title }}</a>
			{% endfor %}
		</div>
	</div>
</div>
<script>
(function(){
	var allHeight = $('.product-filter .all-word').height();
	$('.product-filter .more-btn').click(function(){
		if($(this).hasClass('up')){
			$(this).removeClass('up');
			$(this).find('a').text('更多');
			$('.product-filter .word').css('height', '27px');
		}else{
			$(this).addClass('up');
			$(this).find('a').text('收起');
			$('.product-filter .word').css('height', allHeight + 'px');
		};
	});
})();
</script>
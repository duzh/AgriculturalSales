<!--头部-->
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">
{{ partial('layouts/member_header') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
	        <span>{{ partial('layouts/ur_here') }}&gt;&nbsp;申请标签</span>
	    </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
		<div class="center-right f-fr">

			<div class="apply-tag f-oh">
				<div class="title f-oh">
					<span>申请标签</span>
				</div>
				<form action="/member/tag/save" method="post" id="applyForm">
					<div class="m-title">基本信息</div>
					<div class="message clearfix">
						<font>
							<i>*</i>品名：
						</font>
						<div class="wz">{{ data.goods_name}}</div>
					</div>
					<div class="message clearfix">
						<font>
							<i>*</i>产地：
						</font>
						<div class="selectBox selectBox1 f-pr">
							<select class="select1 mb10" name="province" id="province">
								<option value="">省</option>
							</select>
							<select class="select1 mb10" name="city">
								<option value="">市</option>
							</select>
							<select class="select1 mb10" name="county" >
								<option value="">区／县</option>
							</select>
							<select class="select1 mb10" name="townlet">
								<option value="">街道</option>
							</select>
							<select name="village" data-target="#address-yz" class="select1">
								<option value="">办事处</option>
							</select>
							<div class="f-db f-oh mt10">
								<input name="address" value='{{ data.address }}' data-target="#address-yz" class="input1 f-fl" type="text" placeholder="街道地址／门牌号">
								<i class="tips f-fl">(已选择的地址请勿重复填写)</i>
							</div>
							<i id="address-yz"></i>
						</div>
					</div>
					<div class="message clearfix">
						<font>
							<i>*</i>经纬度：
						</font>
						<div class="wz">
							N{{ data.latitude }}&nbsp;&nbsp;&nbsp;S{{ data.longitude }}
						</div>
					</div>
					<div class="message clearfix">
						<font>
							<i>*</i>雨水等级：
						</font>
						<div class="selectBox selectBox2 f-pr">
							<select name="rainwater" class="select2">
								<option value="">请选择</option>
								<?php  
									foreach ($rainwater as $key =>$val) {
									$selected = '';
									if( $data->rainwater == "$key"){
									$selected = 'selected';
									}
									echo "<option value='{$key}' {$selected} >{$val}</option>";
								}?>
							</select>
						</div>
					</div>
					<div class="message clearfix">
						<font>
							<i>*</i>种植面积：
						</font>
						<div class="inputBox inputBox3 f-pr clearfix">
							<input class="input1 f-fl" type="text" name="plant_area"  id="plant_area" data-rule="required;xs"  value='{{ data.plant_area }}'>
							<input type="hidden" name='total' value='{{ farmArea }}' id='farmArea'>
							<i class="dw f-fl">亩</i>
							<i class="tips f-fl">(可信农场认证耕地面积为：{{ farmArea }}亩)</i>
                            <input type="hidden" value="{{farmArea}}" name="farmarea_info">
                            <span id='area_show_tip'></span>
						</div>
					</div>
					<div class="message clearfix">
						<font>
							<i>*</i>生产日期：
						</font>
						<div class="inputBox inputBox2 f-pr clearfix">
							<input readonly="readonly" type="text" data-rule="required;" class="Wdate input1" name="product_date" id="product_date" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'product_date\',{M:0,d:0})}'})"  value="{{ data.product_date}}"></em>
							
						</div>
					</div>
					<div class="message clearfix">
						<font>
							<i>*</i>保质期：
						</font>
						<div class="inputBox inputBox3 f-pr clearfix">
							<input type="text" name='expiration_date' value="{{ data.expiration_date}}" class="input1 f-fl"/>
							<i class="dw f-fl">天</i>
						</div>
					</div>
					<div class="line"></div>
					<div class="m-title">种植作业</div>
					<div class="message clearfix">
						<font>
							<i>*</i>作物：
						</font>
						<div class="wz">{{ category }}</div>
					</div>
					<div class="message clearfix">
						<font>
							<i>*</i>品种：
						</font>
						<div class="inputBox inputBox2 f-pr clearfix">
							<input class="input1 f-fl" type="text" name="breed" value="{{ tagseed ? tagseed.breed : '' }}">
						</div>
					</div>
					<div class="message clearfix">
						<font>
							<i>*</i>净度：
						</font>
						<div class="inputBox inputBox3 f-pr clearfix">
							<input class="input1 f-fl" type="text" name="neatness"  value="{{ tagseed ? tagseed.neatness : '' }}">
							<i class="dw f-fl">%</i>
						</div>
					</div>
					<div class="message clearfix">
						<font>
							<i>*</i>纯度：
						</font>
						<div class="inputBox inputBox3 f-pr clearfix">
							<input class="input1 f-fl" type="text" name="fineness" value="{{ tagseed ? tagseed.fineness : '' }}">
							<i class="dw f-fl">%</i>
						</div>
					</div>
					<div class="message clearfix">
						<font>
							<i>*</i>发芽率：
						</font>
						<div class="inputBox inputBox3 f-pr clearfix">
							<input class="input1 f-fl" type="text" name="sprout" value="{{ tagseed ? tagseed.sprout : '' }}">
							<i class="dw f-fl">%</i>
						</div>
					</div>
					<div class="message clearfix">
						<font>
							<i>*</i>水分：
						</font>
						<div class="inputBox inputBox3 f-pr clearfix">
							<input class="input1 f-fl" type="text" name="water" value="{{ tagseed ? tagseed.water : '' }}">
							<i class="dw f-fl">%</i>
						</div>
					</div>
					<div class="message clearfix">
						<font>化肥：</font>
						<div class="tableBox f-pr f-oh">
							<table cellpadding="0" cellspacing="0" width="765">
								<tr height="28">
									<th width="210">时间</th>
									<th width="210">名称</th>
									<th width="210">用量（千克／亩）</th>
									<th width="130">操作</th>
								</tr>
							    {% for key , item in TagManureList %}
								<tr height="28">
                                    <input type="hidden" name="use_period[manure][]" value="{{ item.use_period }}">
                                    <input type="hidden" name="manure_name[manure][]" value="{{ item.manure_name }}">
                                    <input type="hidden" name="manure_amount[manure][]" value="{{ item.manure_amount }}">								
                                    <td align="center">{{ item.use_period }}</td>
                                    <td align="center">{{ item.manure_name }}</td>
                                    <td align="center">{{ item.manure_amount }}</td>
                                    <td align="center">
                                        <a href="javascript:;" onclick="$(this).parent().parent().remove();">删除</a>
                                    </td>
								</tr>
								{% endfor %}
							</table>
							<div class="addBox addBox1 f-oh mt10">
								<input class="input2 one" type="text" class="Wdate" name="begin_date" id="begin_date" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'begin_date\',{M:0,d:0})}'})">
								<input class="input2 two" type="text">
								<input class="input2 three" type="text">
								<input class="celltype" style="width:136px;" type="hidden" value='manure' />
								<input class="btn f-fl" type="button" value="+增加">
							</div>
						</div>
					</div>
					<div class="message clearfix">
						<font>农药：</font>
						<div class="tableBox f-pr f-oh">
							<table cellpadding="0" cellspacing="0" width="765">
								<tr height="28">
									<th width="210">时间</th>
									<th width="210">名称</th>
									<th width="210">用量（千克／亩）</th>
									<th width="130">操作</th>
								</tr>
								{% for key , item in TagPesticide %}
								<tr height="28">
									<input type="hidden" name="use_period[pesticide][]" value="{{ item.use_period}}">
                                    <input type="hidden" name="manure_name[pesticide][]" value="{{ item.pesticide_name}}">
                                    <input type="hidden" name="manure_amount[pesticide][]" value="{{ item.pesticide_amount}}">
                                    <td align="center">{{ item.use_period}}</td>
                                    <td align="center">{{ item.pesticide_name}}</td>
                                    <td align="center">{{ item.pesticide_amount}}</td>
                                    <td align="center">
                                        <a href="javascript:;" onclick="$(this).parent().parent().remove();">删除</a>
                                    </td>
								</tr>
								{% endfor %}
							</table>
							<div class="addBox addBox2 f-oh mt10">
								<input class="input2 one" type="text" name="begin_datepes" id="begin_datepes" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'begin_datepes\',{M:0,d:0})}'})">
								<input class="input2 two" type="text">
								<input class="input2 three" type="text">
								<input class="celltype" style="width:136px;" type="hidden" value='pesticide' />
								<input class="btn f-fl" type="button" value="+增加">
							</div>
						</div>
					</div>
					<div class="line"></div>
					<div class="m-title">产品详细信息</div>
					<div class="message clearfix">
						<font>&nbsp;</font>
						<div class="tableBox f-pr f-oh">
							<table cellpadding="0" cellspacing="0" width="765">
								<tr height="28">
									<th width="315">作业类型</th>
									<th width="315">时间</th>
									<th width="130">操作</th>
								</tr>
								{% for key ,item in tagplant %}
								<tr height="28">
                                    <input type="hidden" name="plantoperate_type[]" value="{{ item['operate_type']  }}">
                                    <input type="hidden" name="plantbegin_date[]" value="{{ item['begin_date']  }}">
                                    <input type="hidden" name="plantend_date[]" value="{{ item['end_date']  }}">
                                    <td align="center">{{ item['ptype']  }}</td>
                                    <td align="center">
                                    {{ item['begin_date']  }}-
                                    {{ item['end_date']  }}
                                    </td>
                                    <td align="center">
                                        <a href="javascript:;" onclick="$(this).parent().parent().remove();">删除</a>
                                    </td>
								</tr>
								{% endfor %}
							</table>
							<div class="addBox addBox3 f-oh mt10">
								<select class="select3 one f-fl mr10"  name='operate_type'>
									<option value="">请选择</option>
									{% for key,val in _operate_type %}
                                    <option value='{{ key }}'>{{ val }}</option>
                                    {% endfor %}
								</select>
								<input class="input3 two f-fl" type="text" name="begin_dates" id="begin_dates" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'begin_dates\',{M:0,d:0})}'})">
								<i class="heng f-fl">-</i>
								<input class="input3 three f-fl" type="text" name="end_dates" id="end_dates" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'end_dates\',{M:0,d:0})}'})">
								<input class="btn f-fl" type="button" value="+增加">
							</div>
						</div>
					</div>
					<input type="hidden" name='sid' value='{{ data.tag_id }}'>
					<input class="apply-btn" type="submit" value="确认发布">
				</form>
			</div>

		</div>		

	</div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}
<script>
	$(function(){
		// 验证
		$('#applyForm').validator({
			groups: [{
                fields: "plant_area",
                callback: function(){

                   var farmArea = $('#farmArea').val();
                   var plant_area = $('#plant_area').val();
                    if(!farmArea || !plant_area) {
                        return '不能为空';
                    }
                    if(parseFloat(plant_area) > parseFloat(farmArea) && plant_area  &&  farmArea ) {
                        return '大于认证耕地面积';
                    }
                    return '';
                },
                target: "#area_show_tip"
            }],
			rules: {
                cd: [/^(?=.*?[\u4E00-\u9FA5])[\dA-Za-z\u4E00-\u9FA5]$/, '请输入正确信息'],
                xs: [/^[0-9]+(.[0-9]{1,2})?$/, '请输入正确信息,可保留两位小数']
            },
            fields: {
               'village': '产地:required;',
                'address': '产地:required;length[5~50];',
                'rainwater': '雨水等级:required;',
                'product_date': '生产日期:required;',
                'expiration_date': '保质期:required;integer[+];',
                'breed': '品种:required;length_name;length[2~30];',
                'neatness': '净度:required;xs;range[0~100];',
                'fineness': '纯度:required;xs;range[0~100];',
                'sprout': '发芽率:required;xs;range[0~100];',
                'water': '水分:required;xs;range[0~100];'
            }
		});
		$('select[name="village"]').on('valid.field', function(e, result, me){
			var village = $(this).val();
			$.ajax({
				url: '/member/tag/changeAreas',
				type: 'post',
				async:false,
				dataType:'json',
				data: {village: village},
				success:function (e) {
					var msg = 'N' + e.lng + ' S' + e.lat ;
					// alert(msg);
					$('#resultlat').val(e.lng + ',' + e.lat );
					$('#resultlng').html(msg);
				} 
			})

		});

		// 地区联动
		$(".select1").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
			defaultParentId : 0,
			<?php if(isset($address) && $address) {
				echo " texts : [{$address}],";
			}?>
			style : {"width" : 250}
		});		

	        // 增加化肥表格
        (function(){
            $('.addBox1 .btn').click(function(){
                var reg = new RegExp("^[0-9]+(.[0-9]{1,4})?$");
                var regn = new RegExp("^[a-zA-Z0-9\u4e00-\u9fa5]{2,30}$");
                var reg1= new RegExp("^[^#+-]*$");
                var one_val = $(this).parent().find('.one').val();
                var two_val = $(this).parent().find('.two').val();
                var three_val = $(this).parent().find('.three').val();
                var celltype = $(this).parent().find('.celltype').val();
                
                if(one_val && two_val && three_val && reg.test(three_val)&& reg1.test(three_val) && regn.test(two_val)&&three_val.length<9){
                    var str = '<tr height="28"><input type="hidden" name="use_period['+ celltype + '][]" value='+ one_val +' ><input type="hidden" name="manure_name[' + celltype +'][]" value=' + two_val +' ><input type="hidden" name="manure_amount[' + celltype +'][]" value='+ three_val +' ><td align="center">'+ one_val +'</td><td align="center">'+ two_val +'</td><td align="center">'+ three_val +'</td><td align="center"><a onclick="$(this).parents(\'tr\').remove();" href="javascript:;">删除</a></td></tr>';
                    $(str).appendTo($(this).parent().prev('table'));

                    // 清空信息
                    $(this).parent().find('.one').val('');
                    $(this).parent().find('.two').val('');
                    $(this).parent().find('.three').val('');
                }else{
                    if(!one_val||!two_val||!three_val){
                          alert('各项不能为空');  
                    }else{
	                    if(!reg.test(three_val) || !reg1.test(three_val)|| three_val.length>9){
	                        alert('用量不符合规则');  
	                    }
	                    if(!regn.test(two_val)){
	                        alert('名称不符合规则');  
	                    }
                    }
                   
                    return false;
                };
            });
        })();

        // 增加农药表格
        (function(){
            $('.addBox2 .btn').click(function(){
               var regn = new RegExp("^[a-zA-Z0-9\u4e00-\u9fa5]{2,30}$");
                var reg = new RegExp("^[0-9]+(.[0-9]{1,4})?$");
                var reg1= new RegExp("^[^#+-]*$");
                var one_val = $(this).parent().find('.one').val();
                var two_val = $(this).parent().find('.two').val();
                var three_val = $(this).parent().find('.three').val();
                var celltype = $(this).parent().find('.celltype').val();
                if(one_val && two_val && three_val && reg.test(three_val)&& reg1.test(three_val) && regn.test(two_val)&&three_val.length<9){
                    var str = '<tr height="28"><input type="hidden" name="use_period['+ celltype + '][]" value='+ one_val +' ><input type="hidden" name="manure_name[' + celltype +'][]" value=' + two_val +' ><input type="hidden" name="manure_amount[' + celltype +'][]" value='+ three_val +' ><td align="center">'+ one_val +'</td><td align="center">'+ two_val +'</td><td align="center">'+ three_val +'</td><td align="center"><a onclick="$(this).parents(\'tr\').remove();" href="javascript:;">删除</a></td></tr>';

                    $(str).appendTo($(this).parent().prev('table'));

                    // 清空信息
                    $(this).parent().find('.one').val('');
                    $(this).parent().find('.two').val('');
                    $(this).parent().find('.three').val('');
                }else{
                    if(!one_val||!two_val||!three_val){
                           alert('各项不能为空');  
                    }else{
	                    if(!reg.test(three_val) || !reg1.test(three_val)|| three_val.length>9){
	                        alert('用量不符合规则');  
	                    }
	                    if(!regn.test(two_val)){
	                        alert('名称不符合规则');  
	                    }
                    }
                    return false;
                };
            });
        })();

        // 增加详细信息表格
        (function(){
            $('.addBox3 .btn').click(function(){
                var one_val = $(this).parent().find('.one option:selected').text();
                var one_valid = $(this).parent().find('.one option:selected').val();
                var two_val = $(this).parent().find('.two').val();
                var three_val = $(this).parent().find('.three').val();

                if(one_val && two_val && three_val){
                    var str = '<tr height="28"><input type="hidden" name="plantoperate_type[]" value='+ one_valid +' ><input type="hidden" name="plantbegin_date[]" value=' + two_val +' ><input type="hidden" name="plantend_date[]" value='+ three_val +' ><td align="center">'+ one_val +'</td><td align="center">'+ two_val +'   ～  '+ three_val +'</td><td align="center"><a onclick="$(this).parents(\'tr\').remove();" href="javascript:;">删除</a></td></tr>';
                    $(str).appendTo($(this).parent().prev('table'));

                    // 清空信息
                    $(this).parent().find('.one').val('');
                    $(this).parent().find('.two').val('');
                    $(this).parent().find('.three').val('');
                }else{
                    alert('请填写完整信息');
                    return false;
                };
            });
        })();
	});

</script>

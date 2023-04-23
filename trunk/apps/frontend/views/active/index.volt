{{ partial('layouts/page_header') }}
	<!-- 我是分割线 -->
  <link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
  <script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator-src.js"></script>
  <script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
  <link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.4/css/cg-active/cgou.css">
  <link type="text/css" href="http://code.jquery.com/ui/1.9.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
  <script type="text/javascript" src="{{ constant('JS_URL') }}/jquer-ui/jquery-ui.min.js"></script>
  <script src="{{ constant('JS_URL') }}/jquer-ui/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
  <script src="{{ constant('JS_URL') }}/jquer-ui/jquery.ui.datepicker-zh-CN.js.js" type="text/javascript" charset="gb2312"></script>
  <script src="{{ constant('JS_URL') }}/jquer-ui/jquery-ui-timepicker-zh-CN.js" type="text/javascript"></script>
 
	<!-- 长沟活动样式、js -->
	
</head>
<body>
	<!-- 头部 -->
	<div class="cg-wrapper f-oh">

		<div class="cg-active-banner">
			<div class="box f-oh">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-banner.jpg">
			</div>
			<div class="links">
				<a class="link1" href="#page2"></a>
				<a class="link2" href="#page2"></a>
			</div>
		</div>
		<div class="cg-fixTop">
			<div class="box f-oh">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/fixTop-bc.jpg">
			</div>
			<div class="links">
				<a class="link1 has-layer-dg" href="javascript:;"></a>
				<a class="link2 has-layer-cz" href="javascript:;"></a>
			</div>
		</div>
		<div class="hide-kuai"></div>
		<div class="cg-kuai1">
			<div class="box f-oh">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai1-img1.jpg">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai1-img2.jpg">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai1-img3.jpg">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai1-img4.jpg">
			</div>
		</div>
		<div class="cg-kuai2" id="page2">
			<div class="box f-oh">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai2-img1.jpg">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai2-img2.jpg">
			</div>
			<div class="links">
				<a class="link1 has-layer-dg" href="javascript:;"></a>
			</div>
		</div>
		<div class="cg-kuai3">
			<div class="box f-oh">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai3-img1.jpg">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai3-img2.jpg">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai3-img3.jpg">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai3-img4.jpg">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai3-img5.jpg">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai3-img6.jpg">
			</div>
		</div>
		<div class="cg-kuai4">
			<div class="box f-oh">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai4-img1.jpg">
				<img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-kuai4-img2.jpg">
			</div>
		</div>

	</div>

	<!-- 弹框 -->
	<div class="cg-layer"></div>
	<div class="cg-confirm">

		<a class="close-btn" href="javascript:;"></a>
		<div class="title f-tac">
			<img src="{{ constant('STATIC_URL') }}mdg/version2.4/images/cg-active/cg-confirm-title.png">
		</div>
	    <form action="/active/create/" method="post" id="myform"  autocomplete="off"
         data-validator-option="{theme:'yellow_right_effect',stopOnError:true}">
		<div class="message clearfix">
			<strong>产品名：</strong>
			<font>{{sell.title}}</font>
		</div>
		<div class="message clearfix">
			<strong>单&nbsp;&nbsp;&nbsp;价：</strong>
			<font>¥ {{sell.min_price}}</font>
		</div>
		<div class="message clearfix">
			<strong>规&nbsp;&nbsp;&nbsp;格：</strong>
			<font>{{sell.spec}}</font>
		</div>
		<div class="message clearfix">
			<strong>数&nbsp;&nbsp;&nbsp;量：</strong>
			<font>
				<a class="jian" id="jian" href="javascript:;">-</a>
				<input id="num" name="number" type="text" value="{{min_number}}" data-target="#numbertip" />
				<a class="jia" id="jia" href="javascript:;">+</a>
			</font>
			<p id="numbertip"></p>
		</div>
		<div class="message clearfix">
			<strong>总&nbsp;&nbsp;&nbsp;价：</strong>
			<font id="zongjia">¥ {{sell.min_price}}</font>
		</div>
		<div style="height:25px;"></div>
	
		<div class="delivery-methods">
			<label class="radioBox">
				<input type="radio" checked value='1' id="radio1" name="distribution"  onclick="fun()"/>
				<font>送货上门
					<em>（本次活动仅对以下可选地区进行送货上门）</em>
				</font>
			</label>
			<div class="dg-box change-tab">
				   <div class="formBox clearfix"  >
						<font>预约送货时间：</font>
						<div class="inputBox">
							<input id="d411"  type="text" name="distributiontime" />
						</div>
					</div>
					<div class="formBox clearfix">
						<font>联系人：</font>
						<div class="inputBox">
							<input type="text" name="username"/>
						</div>
					</div>
					<div class="formBox clearfix">
						<font>联系电话：</font>
						<div class="inputBox">
							<input type="text" name="mobile"/>
						</div>
					</div>
					<div class="formBox clearfix">
						<font>配送地址：</font>
						<div class="selectBox">
							<em>山东省</em>
							<em>济宁市</em>
                            <select class="selectAreas">
                                <option>请选择</option>
                            </select>
                            <select  name="areas" class="selectAreas" >
                                <option  value="">请选择</option>
                            </select>
							<input type="text" value=""   placeholder="请输入街道地址"  name="address" />
						</div>
					</div>
		    </div>
		</div>
		<div class="delivery-methods" >
			<label class="radioBox">
				<input type="radio"  value='2' id="radio2" name="distribution" onclick="fun()" />
				<font>
					自行采摘
					<em>（目前仅支持2015年8月15日-2015年10月20日 每周日采摘）</em>
				</font>
			</label>
			<div class="cz-box change-tab">
				<div class="formBox clearfix" >
					<font>预约采摘时间：</font>
					<div class="selectBox">
						<em>2015年</em>
						<select name="stime">
			<!-- 				<option value="2015-8-02">8月2日</option>
							<option value="2015-8-09">8月9日</option> -->
							<option value="2015-8-16">8月16日</option>
							<option value="2015-8-23">8月23日</option>
							<option value="2015-8-30">8月30日</option>
							<option value="2015-9-06">9月6日</option>
							<option value="2015-9-13">9月13日</option>
							<option value="2015-9-20">9月20日</option>
							<option value="2015-9-27">9月27日</option>
							<option value="2015-10-04">10月4日</option>
							<option value="2015-10-11">10月11日</option>
							<option value="2015-10-18">10月18日</option>
							<option value="2015-10-25">10月25日</option>
	                    </select>
						<em>星期日</em>
					</div>
				</div>
				<div class="formBox clearfix">
					<font>联系人：</font>
					<div class="inputBox">
						<input type="text" name="zxusername" />
					</div>
				</div>
				<div class="formBox clearfix">
					<font>联系电话：</font>
					<div class="inputBox">
						<input type="text" name="zxmobile"  />
					</div>
				</div>
				<div class="formBox clearfix">
					<font>备注：</font>
					<div class="areaBox" id="textArea">
						<textarea name="active_desc"></textarea>
						<em><label>150</label>字内</em>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" value="{{sell.id}}" name="sellid">
		<input class="tj-btn" type="submit" value="提交订单" />	
        </form>
	</div>

	<!-- 底部 -->

	<script>
	$(function(){
	    $('#d411').datepicker({
            timeFormat: "HH:mm:ss",
            minDate: new Date(2015, {{month}}, {{day}}),  
            maxDate: new Date(2015, 9, 20)
        });
		//吸顶
		(function(){
			var fixTop = $('.cg-fixTop').position().top;
		
			window.onscroll = function(){
				var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;

				if(scrollTop >= fixTop){
					$('.cg-fixTop').css({'position': 'fixed', 'top': '0', 'z-index': '100002'});
					$('.hide-kuai').show();
				}else{
					$('.cg-fixTop').css({'position': 'relative'});
					$('.hide-kuai').hide();
				};
			};
		})();

		//弹框
		(function(){
			$('.has-layer-dg').click(function(){
				{% if issession %}
				$('.cg-layer').show();
				$('.cg-confirm').show();
				$('.dg-box').show();
				$('.cz-box').hide();
				$("#radio1").prop("checked",true);
				$("#radio2").prop("checked",false);
				
                {% else %}
               $(".cg-fixTop .links").css("z-index","1982");
                $('.cg-kuai2 .links').css("height","0px");
                $(".f-fl").css("float","clear");
                $('.cg-active-banner .links').css("height","0px");
                parent.newWindows('login', '登录', "/member/dlogin/index?ref=/active/index&islogin=1");
                {% endif %}
			});
			$('.has-layer-cz').click(function(){
				{% if issession %}
				$('.cg-layer').show();
				$('.cg-confirm').show();
				$('.dg-box').hide();
				$('.cz-box').show();
				$("#radio2").prop("checked",true);
				$("#radio1").prop("checked",false);

               
				 {% else %}
				$(".cg-fixTop .links").css("z-index","1982");
				 $(".f-fl").css("float","clear");
				 $('.cg-kuai2 .links').css("height","0px");
				 $('.cg-active-banner .links').css("height","0px");
                  parent.newWindows('login', '登录', "/member/dlogin/index?ref=/active/index&islogin=1");
                {% endif %}
			});
			$('.cg-confirm .close-btn').click(function(){
				
				$('.cg-layer').hide();
				$(this).parent().hide();
			});
		})();
       
		(function(){
			//加减
			$("#jia").click(function(){
				var n=$("#num").val();
				var num=parseInt(n)+1;
				
				$("#num").val(num);
				var nums=(num*25.00).toFixed(2);
				$("#zongjia").text("¥"+nums);
			});
			$("#jian").click(function(){
				var n=$("#num").val();
				var num=parseInt(n)-1;

				if(num==0){
					alert("起购量为1箱"); 
					return;
				}
				var nums=(num*25.00).toFixed(2);
				$("#num").val(num);
				$("#zongjia").text("¥"+nums);
			});
			$("#num").keyup(function(){
				var n=$("#num").val();
				if(n==''){
					n=1;
				}
				var num=parseInt(n);

				if(num==0){
					num=1;
				}
				var nums=(num*25.00).toFixed(2);
				$("#num").val(num);
				$("#zongjia").text("¥"+nums);
			});

			//字数统计
			$(document).keydown(function() {
		        var text=$("#textArea textarea").val();
		        var counter=text.length;
				if(counter > 150){
					$('#srNum').val('');
					return ;
				}else{
					$("#textArea label").text(150-counter);
					$('#srNum').val(150-counter);
				};
		    });

		})();
	});
    $('#myform').validator({
    	ignore: ':hidden',
    	timely: 2,
	    select: function(element, param, field) {
            return element.value > 0 || '请选择';
        },
        fields:{
          'distributiontime':'时间:required',
          'number':'购买数量:required;integer[+];',
          'username':'联系人:required;chinese',
          'mobile':'联系电话:required;mobile',
          'areas':"required;",
          "address":"详细地址:required(not,'请输入街道地址');",
          'zxusername':'联系人:required;chinese',
          'zxmobile':'联系电话:required;mobile',
          'active_desc':'length[~150];filter(`%\/@)'

        }

    });
    $(".selectAreas").ld({ajaxOptions:{"url":"/ajax/getactiveareasfull"},
	    defaultParentId : 1564,
	    style : {"width" : 70}
	});
	function fun(){
		$("input[name='distribution']").each(function(index, el) {
			// console.log(this);
			if(this.checked) {
				$(this).parents('.delivery-methods').find('.change-tab').show(500);
			}else{
				$(this).parents('.delivery-methods').find('.change-tab').hide(300);
			}
		});
	}
	  function note_click(target)
	  {
		   if(target.value=='')
		   {
		    target.style.color="#B0B0B0";
		    target.value="请输入街道地址";
		   }
		   else if(target.value=="请输入街道地址")
		   {
			    target.style.color="#000000";
			    target.value="";
		   }
	  }
	
 </script>
</body>
</html>
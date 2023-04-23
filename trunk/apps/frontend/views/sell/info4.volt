<!-- 头部 -->
{{ partial('layouts/page_header') }}
	<div class="wrapper">
		<div class="w1190 mtauto f-oh">

			<div class="bread-crumbs">
				<a href="/">首页</a>&nbsp;>&nbsp;<a href="/tag/index">可追溯产品</a>&nbsp;>&nbsp;{{areasName}}{{ sell.title }}
			</div>
			<div class="tracea-detials f-oh">
				<div class="magnifier clearfix f-fl">
					<dl class="f-fl">
	                    <dt>
	                    	<a href="{{ curImg }}" class="MagicZoom">
								{% if curImg  %}
									<img src="{{ curImg }}" width="300" height="300" alt="{{sell.title}}供应时间:{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}"/>
									{% else %}
									<img  src="<?php echo  Mdg\Models\Image::imgmaxsrc($cateid) ?>" width="300" height="300" alt="{{sell.title}}供应时间:{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}"/>
								{% endif %}

	                    	</a>
	                    </dt>
	                    <dd>
	                        <a class="prev_btn" href="javascript:;">上一个</a>
	                        <div class="ul_box">
	                            <ul>
	                                <li class="active"><a href="javascript:;"><img src="images/b-img1.jpg" /></a></li>
	                                <li><a href="javascript:;"><img src="images/b-img2.jpg" /></a></li>
	                                <li><a href="javascript:;"><img src="images/b-img1.jpg" /></a></li>
	                                <li><a href="javascript:;"><img src="images/b-img2.jpg" /></a></li>
	                                <li><a href="javascript:;"><img src="images/b-img1.jpg" /></a></li>
	                                <li><a href="javascript:;"><img src="images/b-img2.jpg" /></a></li>
	                                <li><a href="javascript:;"><img src="images/b-img1.jpg" /></a></li>
	                                <li><a href="javascript:;"><img src="images/b-img2.jpg" /></a></li>
	                                <li><a href="javascript:;"><img src="images/b-img1.jpg" /></a></li>
	                            </ul>
	                        </div>
	                        <a class="next_btn" href="javascript:;">下一个</a>
	                    </dd>
	                </dl>
					<div class="detials-box f-fl">
						<div class="name">富硒小麦仁成活率高</div>
						<div class="bh clearfix">
							<font class="f-fl">[供应编号：SELL0000139578]</font>
							<a class="collect f-fr" href="#">收藏</a>
							<a class="share f-fr" href="#">分享</a>
						</div>
						<div class="b-price">
							<span>
								商品报价：<strong>16.00-17.50</strong> 元／斤
							</span>
							<font>
								累计评价<br />
								<strong>18563</strong>
							</font>
						</div>
						<div class="message">供 应 量：1000.00 公斤</div>
						<div class="message">起 购 量：200 公斤</div>
						<div class="message">
							供应地区：黑龙江省,齐齐哈尔市,建华区,西大桥街道办事处,名人社区居民委员会
						</div>
						<div class="message">供应时间：10月下旬~12月中旬</div>
						<div class="message">产品规格：散装</div>
						<div class="btns">
							<input class="cg-btn" type="submit" value="采购" />
							<input class="xj-btn" type="submit" value="询价" />
						</div>
					</div>
				</div>
				<!-- 
				**	根据需求判断显示“公司信息”还是“商家信息”
				-->
				<!-- 公司信息 -->
				<div class="company-intro f-fr f-oh">
					<div class="imgs f-tac">
						<img src="images/company-intro-img.jpg" alt="">
					</div>
					<div class="line"></div>
					<div class="name">美胜化肥有限公司</div>
					<div class="icon">可信农场</div>
					<div class="line"></div>
					<div class="message clearfix">
						<font>农场面积：</font>
						<div class="m-con">3000亩</div>
					</div>
					<div class="message clearfix">
						<font>所在地区：</font>
						<div class="m-con">黑龙江省,齐齐哈尔市,建华区</div>
					</div>
					<div class="message clearfix">
						<font>主营产品：</font>
						<div class="m-con">西红柿、马铃薯、地瓜、小麦、苹果、圆茄子...</div>
					</div>
					<div class="line"></div>
					<div class="btns">
						<a class="collect-btn" href="#">收藏可信农场</a>
						<a class="enter-btn" href="#">进入可信农场</a>
					</div>       
				</div>
				<!-- 商家信息 -->
				<div class="bussiness-intro f-fr f-oh" style="display:none;">
					<div class="name">商家姓名：张三</div>
					<div class="icon">实名认证</div>
					<div class="line"></div>
					<div class="message clearfix">
						<font>所在地区：</font>
						<div class="m-con">黑龙江省,齐齐哈尔市,建华区</div>
					</div>
					<div class="message clearfix">
						<font>主营产品：</font>
						<div class="m-con">西红柿、马铃薯、地瓜、小麦、苹果、圆茄子...</div>
					</div>
				</div>
			</div>
			<div class="tracea-biggerBox f-oh">
				<div class="left f-fl">

					<div class="switch-btn clearfix">
						<span class="active">详细信息</span>
						<span>追溯信息</span>
						<span>用户评论（55）</span>
						<span>交易记录</span>
					</div>
					<div class="tracea-tabChange">
						<!-- 详细信息 -->
						<div class="tab-box f-oh" style="display:block;">

							<!-- 块1 -->
							<table cellpadding="0" cellspacing="0" class="tracea-message">
								<tr height="29">
									<td width="243">
										供应编号：SELL0000139749
									</td>
									<td width="243">
										产品品名：西红柿
									</td>
									<td width="332">
										产品品种：西红柿
									</td>
								</tr>
								<tr height="29">
									<td width="243">
										供应时间：7月上旬－8月中旬
									</td>
									<td width="243">
										产品规格：单个重3两以上
									</td>
									<td width="332">
										产地：河北石家庄通利福利亚区新发农产品产业园
									</td>
								</tr>
								<tr height="29">
									<td width="243">
										生产商：丰收汇农副产品有限公司
									</td>
									<td width="243">
										生产日期：
									</td>
									<td width="332">
										加工商：丰收汇农副产品有限公司
									</td>
								</tr>
								<tr height="29">
									<td width="243">
										加工地：山东
									</td>
									<td width="243">
										保质期：60天
									</td>
									<td width="332">
										质量等级：优
									</td>
								</tr>
								<tr height="29">
									<td width="243">
										农残含量：
									</td>
									<td width="243">
										重金属含量：0.00
									</td>
									<td width="332">
										是否转基因：否
									</td>
								</tr>
							</table>
							<div class="tracea-title">
								<span>产品介绍</span>
							</div>
							<div class="tracea-product-intro f-tac">
								<img src="images/tracea-tabChange-img.jpg" alt="">
							</div>
							<!-- 块2 -->
							<div class="tracea-title">
								<span>追溯信息</span>
							</div>
							<div class="tracea-erjiMsg">
								<div class="m-title">生产过程信息</div>
								<table cellpadding="0" cellspacing="0" width="100%" class="m-message">
									<tr height="29">
										<td width="50%">
											生产基地位置：丰收汇农副产品有限公司
										</td>
										<td width="50%">
											基地面积：1000亩
										</td>
									</tr>
									<tr height="29">
										<td width="50%">
											种子质量指标：
										</td>
										<td width="50%">
											产地：河北石家庄通利福利亚区新发农产品产业园
										</td>
									</tr>
									<tr height="29">
										<td width="50%">
											土地污染：
										</td>
										<td width="50%">
											品种名：
										</td>
									</tr>
									<tr height="29">
										<td colspan="2">
											物流信息：未发货，物流单位未确认
										</td>
									</tr>
								</table>
								<div class="safety">
									<div class="name">权威机构安全鉴定：</div>
									<ul class="gallery imgs">
				                        <li>
				                            <a href="images/supply-featured-product.jpg">
				                                <img src="images/supply-featured-product.jpg" height="80" width="80">
				                            </a>
				                        </li>
				                        <li>
				                            <a href="images/supply-hall-product.jpg">
				                                <img src="images/supply-hall-product.jpg" height="80" width="80">
				                            </a>
				                        </li>
				                    </ul>
								</div>
								<div class="origin-photo">
									<div class="name">产地照片：</div>
									<!-- 滚动 start -->
									<div class="add_big_box" id="add_big_box1">
				                    	<a class="prev_btn" href="javascript:;"></a>
				                        <div class="f-pr mtauto schj_list_xq">
				                            <ul class="pa clearfix gallery" style="width:2000px;">
				                                <li class="active">
				                                	<a href="images/shop-recommend-img1.jpg">
				                                		<img src="images/shop-recommend-img1.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img2.jpg">
				                                		<img src="images/shop-recommend-img2.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img3.jpg">
				                                		<img src="images/shop-recommend-img3.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img1.jpg">
				                                		<img src="images/shop-recommend-img1.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img2.jpg">
				                                		<img src="images/shop-recommend-img2.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img3.jpg">
				                                		<img src="images/shop-recommend-img3.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img1.jpg">
				                                		<img src="images/shop-recommend-img1.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img2.jpg">
				                                		<img src="images/shop-recommend-img2.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                            </ul>
				                        </div>
				                        <a class="next_btn" href="javascript:;"></a>
				                    </div>
				                    <!-- 滚动 end -->
								</div>
								<div class="use-table">
									<div class="name">化肥使用表</div>
									<table cellpadding="0" cellspacing="0" width="778" class="u-message">
										<tr height="30">
											<th width="16%">使用时期</th>
											<th width="16%">名称</th>
											<th width="16%">种类</th>
											<th width="16%">用量(千克/亩)</th>
											<th width="16%">品牌</th>
											<th width="20%">供应商</th>
										</tr>
										<tr height="40">
											<td>补肥期施肥</td>
											<td>根力多有机肥</td>
											<td>有机肥</td>
											<td>200</td>
											<td>根力多</td>
											<td>
												<span>河北根力多生物科技股份有限公司</span>
											</td>
										</tr>
									</table>
								</div>
								<div class="use-table">
									<div class="name">农药使用表</div>
									<table cellpadding="0" cellspacing="0" width="778" class="u-message">
										<tr height="30">
											<th width="15%">使用时期</th>
											<th width="20%">名称</th>
											<th width="20%">用量(千克/亩)</th>
											<th width="20%">品牌</th>
											<th width="25%">供应商</th>
										</tr>
										<tr height="40">
											<td>补肥期施肥</td>
											<td>根力多有机肥</td>
											<td>200</td>
											<td>根力多</td>
											<td>
												<span>河北根力多生物科技股份有限公司</span>
											</td>
										</tr>
									</table>
								</div>
								<div class="m-title">生产环节全程监控</div>
								<table cellpadding="0" cellspacing="0" width="778" class="p-table">
									<tr height="30">
										<th width="16%">作业类型</th>
										<th width="16%">开始时间</th>
										<th width="16%">结束时间</th>
										<th width="16%">天气状况</th>
										<th width="20%">作业内容</th>
										<th width="16%">相册</th>
									</tr>
									<tr height="40">
										<td>收获</td>
										<td>2015/01/01</td>
										<td>2015/02/02</td>
										<td>晴朗 20℃~30℃</td>
										<td>汗滴禾下土，粒粒皆辛苦</td>
										<td>
											<a class="chakan_photos" href="javascript:newWindows('view_photos', '查看相册', '500px', '500px', 'view_photos.html');">查看</a>
										</td>
									</tr>
									<tr height="40">
										<td>收获</td>
										<td>2015/01/01</td>
										<td>2015/02/02</td>
										<td>晴朗 20℃~30℃</td>
										<td>汗滴禾下土，粒粒皆辛苦</td>
										<td>
											<a class="chakan_photos" href="javascript:newWindows('view_photos', '查看相册', '500px', '500px', 'view_photos.html');">查看</a>
										</td>
									</tr>
								</table>
							</div>
							<!-- 块3 -->
							<div class="tracea-title">
								<span>用户评价</span>
							</div>
							<div class="user-rate f-oh">
								<div class="message clearfix">
									<font class="f-fl">累计评价：37691639</font>
									<font class="f-fl clearfix">
										<i>与描述相符： 3分</i>
										<span class="star active"></span>
										<span class="star active"></span>
										<span class="star active"></span>
										<span class="star"></span>
										<span class="star"></span>
									</font>
								</div>
								<table cellpadding="0" cellspacing="0" width="778" class="r-table">
									<tr height="32">
										<th align="left" width="40%">
											<em>评价内容</em>
										</th>
										<th width="20%">评分</th>
										<th width="20%">发布时间</th>
										<th width="20%">用户名</th>
									</tr>
									<tr height="66">
										<td>
											<em>麦子非常好，颗粒饱满，麦子非常好，颗粒饱满，麦子非常好，颗粒饱满非常好，颗粒饱满，</em>
										</td>
										<td align="center">											
											<div class="stars f-oh">
												<span class="star active"></span>
												<span class="star active"></span>
												<span class="star active"></span>
												<span class="star"></span>
												<span class="star"></span>
											</div>
										</td>
										<td align="center">2015-04-18 12:22</td>
										<td align="center">
											<label>13812341234</label>
										</td>
									</tr>
									<tr height="66">
										<td>
											<em>麦子非常好，颗粒饱满，麦子非常好，颗粒饱满，麦子非常好，颗粒饱满非常好，颗粒饱满，</em>
										</td>
										<td align="center">											
											<div class="stars f-oh">
												<span class="star active"></span>
												<span class="star active"></span>
												<span class="star active"></span>
												<span class="star"></span>
												<span class="star"></span>
											</div>
										</td>
										<td align="center">2015-04-18 12:22</td>
										<td align="center">
											<label>13812341234</label>
										</td>
									</tr>
								</table>
								<!-- 分页 -->
								<div class="esc-page mt20 f-tac f-fr mr39">
									<a href="#" class="prev-page">上一页</a>
									<a class="active" href="#">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">5</a><a href="#">6</a><font>...</font>
									<a href="#" class="next-page">下一页</a>
									<span>
										<label>去</label>
										<input type="text" />
										<label>页</label>
									</span>
									<input class="btn" type="submit" value="确定" />
								</div>
							</div>
							<!-- 块4 -->
							<div class="tracea-title">
								<span>交易记录</span>
							</div>
							<div class="jy-record">
								<table cellpadding="0" cellspacing="0" width="778" class="r-table">
									<tr height="32">
										<th align="left" width="50%">
											<em>采购商家</em>
										</th>
										<th width="25%">采购数量</th>
										<th width="25%">采购时间</th>
									</tr>
									<tr height="40">
										<td>
											<em>丽华快餐采购中心</em>
										</td>
										<td align="center">100公斤</td>
										<td align="center">2015-04-18 12:22</td>
									</tr>
									<tr height="40">
										<td>
											<em>丽华快餐采购中心</em>
										</td>
										<td align="center">100公斤</td>
										<td align="center">2015-04-18 12:22</td>
									</tr>
								</table>
								<!-- 分页 -->
								<div class="esc-page mt20 f-tac f-fr mr39">
									<a href="#" class="prev-page">上一页</a>
									<a class="active" href="#">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">5</a><a href="#">6</a><font>...</font>
									<a href="#" class="next-page">下一页</a>
									<span>
										<label>去</label>
										<input type="text" />
										<label>页</label>
									</span>
									<input class="btn" type="submit" value="确定" />
								</div>
							</div>

						</div>
						<!-- 追溯信息 -->
						<div class="tab-box f-oh">

							<!-- 块2 -->
							<div class="tracea-title">
								<span>追溯信息</span>
							</div>
							<div class="tracea-erjiMsg">
								<div class="m-title">生产过程信息</div>
								<table cellpadding="0" cellspacing="0" width="100%" class="m-message">
									<tr height="29">
										<td width="50%">
											生产基地位置：丰收汇农副产品有限公司
										</td>
										<td width="50%">
											基地面积：1000亩
										</td>
									</tr>
									<tr height="29">
										<td width="50%">
											种子质量指标：
										</td>
										<td width="50%">
											产地：河北石家庄通利福利亚区新发农产品产业园
										</td>
									</tr>
									<tr height="29">
										<td width="50%">
											土地污染：
										</td>
										<td width="50%">
											品种名：
										</td>
									</tr>
									<tr height="29">
										<td colspan="2">
											物流信息：未发货，物流单位未确认
										</td>
									</tr>
								</table>
								<div class="safety">
									<div class="name">权威机构安全鉴定：</div>
									<ul class="gallery imgs">
				                        <li>
				                            <a href="images/supply-featured-product.jpg">
				                                <img src="images/supply-featured-product.jpg" height="80" width="80">
				                            </a>
				                        </li>
				                        <li>
				                            <a href="images/supply-hall-product.jpg">
				                                <img src="images/supply-hall-product.jpg" height="80" width="80">
				                            </a>
				                        </li>
				                    </ul>
								</div>
								<div class="origin-photo">
									<div class="name">产地照片：</div>
									<!-- 滚动 start -->
									<div class="add_big_box" id="add_big_box2">
				                    	<a class="prev_btn" href="javascript:;"></a>
				                        <div class="f-pr mtauto schj_list_xq">
				                            <ul class="pa clearfix gallery" style="width:2000px;">
				                                <li class="active">
				                                	<a href="images/shop-recommend-img1.jpg">
				                                		<img src="images/shop-recommend-img1.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img2.jpg">
				                                		<img src="images/shop-recommend-img2.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img3.jpg">
				                                		<img src="images/shop-recommend-img3.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img1.jpg">
				                                		<img src="images/shop-recommend-img1.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img2.jpg">
				                                		<img src="images/shop-recommend-img2.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img3.jpg">
				                                		<img src="images/shop-recommend-img3.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img1.jpg">
				                                		<img src="images/shop-recommend-img1.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                                <li>
				                                	<a href="images/shop-recommend-img2.jpg">
				                                		<img src="images/shop-recommend-img2.jpg" width="160" height="100" />
				                                	</a>
				                                </li>
				                            </ul>
				                        </div>
				                        <a class="next_btn" href="javascript:;"></a>
				                    </div>
				                    <!-- 滚动 end -->
								</div>
								<div class="use-table">
									<div class="name">化肥使用表</div>
									<table cellpadding="0" cellspacing="0" width="778" class="u-message">
										<tr height="30">
											<th width="16%">使用时期</th>
											<th width="16%">名称</th>
											<th width="16%">种类</th>
											<th width="16%">用量(千克/亩)</th>
											<th width="16%">品牌</th>
											<th width="20%">供应商</th>
										</tr>
										<tr height="40">
											<td>补肥期施肥</td>
											<td>根力多有机肥</td>
											<td>有机肥</td>
											<td>200</td>
											<td>根力多</td>
											<td>
												<span>河北根力多生物科技股份有限公司</span>
											</td>
										</tr>
									</table>
								</div>
								<div class="use-table">
									<div class="name">农药使用表</div>
									<table cellpadding="0" cellspacing="0" width="778" class="u-message">
										<tr height="30">
											<th width="15%">使用时期</th>
											<th width="20%">名称</th>
											<th width="20%">用量(千克/亩)</th>
											<th width="20%">品牌</th>
											<th width="25%">供应商</th>
										</tr>
										<tr height="40">
											<td>补肥期施肥</td>
											<td>根力多有机肥</td>
											<td>200</td>
											<td>根力多</td>
											<td>
												<span>河北根力多生物科技股份有限公司</span>
											</td>
										</tr>
									</table>
								</div>
								<div class="m-title">生产环节全程监控</div>
								<table cellpadding="0" cellspacing="0" width="778" class="p-table">
									<tr height="30">
										<th width="16%">作业类型</th>
										<th width="16%">开始时间</th>
										<th width="16%">结束时间</th>
										<th width="16%">天气状况</th>
										<th width="20%">作业内容</th>
										<th width="16%">相册</th>
									</tr>
									<tr height="40">
										<td>收获</td>
										<td>2015/01/01</td>
										<td>2015/02/02</td>
										<td>晴朗 20℃~30℃</td>
										<td>汗滴禾下土，粒粒皆辛苦</td>
										<td>
											<a class="chakan_photos" href="javascript:newWindows('view_photos', '查看相册', '500px', '500px', 'view_photos.html');">查看</a>
										</td>
									</tr>
									<tr height="40">
										<td>收获</td>
										<td>2015/01/01</td>
										<td>2015/02/02</td>
										<td>晴朗 20℃~30℃</td>
										<td>汗滴禾下土，粒粒皆辛苦</td>
										<td>
											<a class="chakan_photos" href="javascript:newWindows('view_photos', '查看相册', '500px', '500px', 'view_photos.html');">查看</a>
										</td>
									</tr>
								</table>
							</div>

						</div>
						<!-- 用户评论 -->
						<div class="tab-box f-oh">

							<!-- 块3 -->
							<div class="tracea-title">
								<span>用户评价</span>
							</div>
							<div class="user-rate f-oh">
								<div class="message clearfix">
									<font class="f-fl">累计评价：37691639</font>
									<font class="f-fl clearfix">
										<i>与描述相符： 3分</i>
										<span class="star active"></span>
										<span class="star active"></span>
										<span class="star active"></span>
										<span class="star"></span>
										<span class="star"></span>
									</font>
								</div>
								<table cellpadding="0" cellspacing="0" width="778" class="r-table">
									<tr height="32">
										<th align="left" width="40%">
											<em>评价内容</em>
										</th>
										<th width="20%">评分</th>
										<th width="20%">发布时间</th>
										<th width="20%">用户名</th>
									</tr>
									<tr height="66">
										<td>
											<em>麦子非常好，颗粒饱满，麦子非常好，颗粒饱满，麦子非常好，颗粒饱满非常好，颗粒饱满，</em>
										</td>
										<td align="center">											
											<div class="stars f-oh">
												<span class="star active"></span>
												<span class="star active"></span>
												<span class="star active"></span>
												<span class="star"></span>
												<span class="star"></span>
											</div>
										</td>
										<td align="center">2015-04-18 12:22</td>
										<td align="center">
											<label>13812341234</label>
										</td>
									</tr>
									<tr height="66">
										<td>
											<em>麦子非常好，颗粒饱满，麦子非常好，颗粒饱满，麦子非常好，颗粒饱满非常好，颗粒饱满，</em>
										</td>
										<td align="center">											
											<div class="stars f-oh">
												<span class="star active"></span>
												<span class="star active"></span>
												<span class="star active"></span>
												<span class="star"></span>
												<span class="star"></span>
											</div>
										</td>
										<td align="center">2015-04-18 12:22</td>
										<td align="center">
											<label>13812341234</label>
										</td>
									</tr>
								</table>
								<!-- 分页 -->
								<div class="esc-page mt20 f-tac f-fr mr39">
									<a href="#" class="prev-page">上一页</a>
									<a class="active" href="#">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">5</a><a href="#">6</a><font>...</font>
									<a href="#" class="next-page">下一页</a>
									<span>
										<label>去</label>
										<input type="text" />
										<label>页</label>
									</span>
									<input class="btn" type="submit" value="确定" />
								</div>
							</div>

						</div>
						<!-- 详细信息 -->
						<div class="tab-box f-oh">

							<!-- 块4 -->
							<div class="tracea-title">
								<span>交易记录</span>
							</div>
							<div class="jy-record">
								<table cellpadding="0" cellspacing="0" width="778" class="r-table">
									<tr height="32">
										<th align="left" width="50%">
											<em>采购商家</em>
										</th>
										<th width="25%">采购数量</th>
										<th width="25%">采购时间</th>
									</tr>
									<tr height="40">
										<td>
											<em>丽华快餐采购中心</em>
										</td>
										<td align="center">100公斤</td>
										<td align="center">2015-04-18 12:22</td>
									</tr>
									<tr height="40">
										<td>
											<em>丽华快餐采购中心</em>
										</td>
										<td align="center">100公斤</td>
										<td align="center">2015-04-18 12:22</td>
									</tr>
								</table>
								<!-- 分页 -->
								<div class="esc-page mt20 f-tac f-fr mr39">
									<a href="#" class="prev-page">上一页</a>
									<a class="active" href="#">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">5</a><a href="#">6</a><font>...</font>
									<a href="#" class="next-page">下一页</a>
									<span>
										<label>去</label>
										<input type="text" />
										<label>页</label>
									</span>
									<input class="btn" type="submit" value="确定" />
								</div>
							</div>

						</div>
					</div>

				</div>
				<div class="right f-fr">

					<!-- 他还提供 -->
					<div class="also-serve mb20">
						<div class="title">他还提供</div>
						<div class="list f-oh">
							<a href="#">
								<span class="f-fl">
									[有机大白菜] <i>5.00-8.00</i>元/公斤
								</span>      
								<font class="f-fr">15-04-13</font>
							</a>
						</div>
						<div class="list f-oh">
							<a href="#">
								<span class="f-fl">
									[有机大白菜] <i>5.00-8.00</i>元/公斤
								</span>      
								<font class="f-fr">15-04-13</font>
							</a>
						</div>
						<div class="list f-oh">
							<a href="#">
								<span class="f-fl">
									[有机大白菜] <i>5.00-8.00</i>元/公斤
								</span>      
								<font class="f-fr">15-04-13</font>
							</a>
						</div>
					</div>
					<!-- 同类产品推荐 -->
					<div class="hot-product">
						<div class="title">同类产品推荐</div>
						<div class="list">
							<div class="m-box">
								<dl class="clearfix">
									<dt class="f-fl">
										<a href="#"><img src="images/hot-product-img.jpg" alt=""></a>
									</dt>
									<dd class="name f-fr">
										<a href="#">进口胡萝卜</a>
									</dd>
									<dd class="price f-fr">18.50-21.00 元/公斤</dd>
									<dd class="area f-fr">河南郑州</dd>
								</dl>
							</div>
						</div>
						<div class="list">
							<div class="m-box">
								<dl class="clearfix">
									<dt class="f-fl">
										<a href="#"><img src="images/hot-product-img.jpg" alt=""></a>
									</dt>
									<dd class="name f-fr">
										<a href="#">进口胡萝卜</a>
									</dd>
									<dd class="price f-fr">18.50-21.00 元/公斤</dd>
									<dd class="area f-fr">河南郑州</dd>
								</dl>
							</div>
						</div>
						<div class="list">
							<div class="m-box">
								<dl class="clearfix">
									<dt class="f-fl">
										<a href="#"><img src="images/hot-product-img.jpg" alt=""></a>
									</dt>
									<dd class="name f-fr">
										<a href="#">进口胡萝卜</a>
									</dd>
									<dd class="price f-fr">18.50-21.00 元/公斤</dd>
									<dd class="area f-fr">河南郑州</dd>
								</dl>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>
	</div>

	<!-- 底部 -->
	<div class="footer-helpList">
		<div class="w1190 mtauto f-oh">
			<dl class="list1">
				<dt>交易指南</dt>
				<dd>
					<a href="#">注册登录</a>
				</dd>
				<dd>
					<a href="#">如何买货</a>
				</dd>
				<dd>
					<a href="#">如何卖货</a>
				</dd>				
			</dl>
			<dl class="list2">
				<dt>联系我们</dt>
				<dd>
					<a href="#">关于云农场</a>
				</dd>
				<dd>
					<a href="#">联系我们</a>
				</dd>
				<dd>
					<a href="#">关于我们</a>
				</dd>				
			</dl>
			<dl class="list3">
				<dt>配送支付</dt>
				<dd>
					<a href="#">支付方式</a>
				</dd>
				<dd>
					<a href="#">如何付款</a>
				</dd>
				<dd>
					<a href="#">常见问题</a>
				</dd>				
			</dl>
			<dl class="list4">
				<dt>服务保障</dt>
				<dd>
					<a href="#">退货原则</a>
				</dd>
				<dd>
					<a href="#">售后流程</a>
				</dd>
				<dd>
					<a href="#">免责条款</a>
				</dd>				
			</dl>
			<div class="images f-fr">
				<img class="hotLine" src="images/footer-hotLine.png" alt="">
				<img class="wx" src="images/footer-wx.png" alt="">
				<img class="app" src="images/footer-app.png" alt="">
			</div>
		</div>
	</div>
	<div class="footer">
		<div class="w1190 mtauto f-oh">
			<div class="friendLinks">
				<font>友情链接：</font>
				<a href="#">乡间货的</a><em>丨</em>
				<a href="#">三农网</a><em>丨</em>
				<a href="#">云南苗木</a><em>丨</em>
				<a href="#">致富网</a><em>丨</em>
				<a href="#">肥料价格</a><em>丨</em>
				<a href="#">菜农网</a><em>丨</em>
				<a href="#">农药信息网</a>
			</div>
			<div class="Icp">
				<p>北京天辰云农场有限公司 北京市朝阳区东三环中路39号建外SOHO东区9号楼22F  Copyright ©2014,版权所有北京天辰云农场有限公司，京ICP备14023165号-2</p>
			</div>
		</div>
	</div>

	<!-- 侧边栏 -->
	<div class="scrollBox">
		<div class="box clearfix">

			<!-- 查询 -->
			<div class="cx">
				<a href="#">
					<div class="imgs"></div>
					<div class="imgTips">
						<div class="right-arrow"></div>
						<span>服务站&可信农场查询</span>
					</div>
				</a>
			</div>
			<!-- 下载APP -->
			<div class="load-app">
				<a href="#">
					<div class="imgs"></div>
					<div class="imgTips">
						<div class="right-arrow"></div>
						<span>下载APP</span>
					</div>
				</a>
			</div>
			<!-- 意见反馈 -->
			<div class="feed-back">
				<a href="#">
					<div class="imgs"></div>
					<div class="imgTips">
						<div class="right-arrow"></div>
						<span>意见反馈</span>
					</div>
				</a>
			</div>
			<!-- 联系客服 -->
			<div class="contact-service">
				<a href="#">
					<div class="imgs"></div>
					<div class="imgTips">
						<div class="right-arrow"></div>
						<span>联系客服</span>
					</div>
				</a>
			</div>
			<!-- 回到顶部 -->
			<div class="move-top">
				<a href="javascript:;">
					<div class="imgs"></div>
					<div class="imgTips">
						<div class="right-arrow"></div>
						<span>回到顶部</span>
					</div>
				</a>
			</div>

		</div>
	</div>
	
	<!-- 放大镜 -->
	<link href="{{ constant('STATIC_URL') }}mdg/version2.5/css/MagicZoom.css" rel="stylesheet" media="screen"/>
    <script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/MagicZoom.js"></script>
    <style>
    	.MagicZoomBigImageCont{ border:solid 1px #f9ab14;}
    </style>
    <!-- 图片放大预览 -->
    <link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/zoom.css" media="all">
	<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/zoom.min.js"></script>
	<style>
		#zoom{ *background:#000; *opacity:0.8; *filter:alpha(opacity:80);}
	</style>

</body>

</html>
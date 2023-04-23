{{ partial('layouts/page_header') }}
<div class="contianer pb30">
    <div class="shop_banner w960"></div>
    {{ partial('layouts/navigate') }}
    <div class="shop_symm w960 clearfix">
        <div class="symm_left f-fl">
            {{ partial('layouts/category_left') }}
            {{ partial('layouts/shoper_left') }}
            {{ partial('layouts/comments_left') }}
        </div>
        <!-- 右侧 start -->
        <div class="symm_right f-fr">
            <!-- 服务评分 start -->
            <div class="shop_service">
                <h6>服务评分</h6>
                <ul class="pj_star">
                    <li> <font>服务态度：</font>
                        <div class="star">
                            <span <?php if(isset($score->service) && $score->service >=1) echo 'class="whole_star"';
                                        if(isset($score->service) && $score->service >=0.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->service) && $score->service >=2) echo 'class="whole_star"';
                                        if(isset($score->service) && $score->service >=1.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->service) && $score->service >=3) echo 'class="whole_star"';
                                        if(isset($score->service) && $score->service >=2.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->service) && $score->service >=4) echo 'class="whole_star"';
                                        if(isset($score->service) && $score->service >=3.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->service) && $score->service >=5) echo 'class="whole_star"';
                                        if(isset($score->service) && $score->service >=4.5) echo 'class="half_star"';?>>
                            </span>
                            <em><?php echo isset($shopgrade->service) ? $shopgrade->service : 0;?> 星</em>
                        </div>
                    </li>
                    <li> <font>陪同程度：</font>
                        <div class="star">
                            <span <?php if(isset($score->accompany) && $score->accompany >=1) echo 'class="whole_star"';
                                        if(isset($score->accompany) && $score->accompany >=0.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->accompany) && $score->accompany >=2) echo 'class="whole_star"';
                                        if(isset($score->accompany) && $score->accompany >=1.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->accompany) && $score->accompany >=3) echo 'class="whole_star"';
                                        if(isset($score->accompany) && $score->accompany >=2.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->accompany) && $score->accompany >=4) echo 'class="whole_star"';
                                        if(isset($score->accompany) && $score->accompany >=3.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->accompany) && $score->accompany >=5) echo 'class="whole_star"';
                                        if(isset($score->accompany) && $score->accompany >=4.5) echo 'class="half_star"';?>>
                            </span>
                            <em><?php echo isset($shopgrade->accompany) ? $shopgrade->accompany : 0; ?> 星</em>
                        </div>
                    </li>
                    <li>
                        <font>供货能力：</font>
                        <div class="star">
                            <span <?php if(isset($score->supply) && $score->supply >=1) echo 'class="whole_star"';
                                        if(isset($score->supply) && $score->supply >=0.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->supply) && $score->supply >=2) echo 'class="whole_star"';
                                        if(isset($score->supply) && $score->supply >=1.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->supply) && $score->supply >=3) echo 'class="whole_star"';
                                        if(isset($score->supply) && $score->supply >=2.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->supply) && $score->supply >=4) echo 'class="whole_star"';
                                        if(isset($score->supply) && $score->supply >=3.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->supply) && $score->supply >=5) echo 'class="whole_star"';
                                        if(isset($score->supply) && $score->supply >=4.5) echo 'class="half_star"';?>>
                            </span>
                            <em><?php echo isset($shopgrade->supply) ? $shopgrade->supply : 0;?>  星</em>
                        </div>
                    </li>
                    <li>
                        <font>描述相符：</font>
                        <div class="star">
                            <span <?php if(isset($score->description) && $score->description >=1) echo 'class="whole_star"';
                                        if(isset($score->description) && $score->description >=0.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->description) && $score->description >=2) echo 'class="whole_star"';
                                        if(isset($score->description) && $score->description >=1.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->description) && $score->description >=3) echo 'class="whole_star"';
                                        if(isset($score->description) && $score->description >=2.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->description) && $score->description >=4) echo 'class="whole_star"';
                                        if(isset($score->description) && $score->description >=3.5) echo 'class="half_star"';?>>
                            </span>
                            <span <?php if(isset($score->description) && $score->description >=5) echo 'class="whole_star"';
                                        if(isset($score->description) && $score->description >=4.5) echo 'class="half_star"';?>>
                            </span>
                             

                            <em>
                                <?php echo isset($shopgrade->description) ? $shopgrade->description : 0;?>
                                <!-- {{shopgrade.description}} --> 星</em>
                        </div>
                    </li>
                </ul>
                <?php

                // if(!$shopcomments){?>

                    <div class="pj_mark" onclick="javascript:newWindows('evaluation', '请填写您的评价', '/member/dialog/evaluation/<?php echo $shop->
                        shop_id; ?>');">
                        <span>给他评分</span>
                    </div>

         <?php //}?>
            </div>
            <!-- 服务评分 end -->
            <!-- 最新评价 start -->
            <div class="shop_service">
                <h6>最新评价</h6>
                <div class="pj_detial">
                    {% for item in shopcomments %}
                    <!-- 评价列表 start -->
                    <div class="list">
                        <div class="pj_ren clearfix">
                            <span class="f-fl">
                                评论人：
                                <font>
                                    <?php echo isset($item->user_name)? $item->user_name : '';?>
                                    <!-- {{ item.user_name ? item.user_name : '' }} -->
                                </font>
                            </span>
                            <span class="f-fr">
                                评论时间：
                                <font>
                                        <?php echo isset($item->add_time)? date("Y-m-d H:i:s", $item->add_time) : '';?>
                                </font>
                            </span>
                        </div>
                        <p class="f-ff0">
                            <?php echo isset($item->comment)? $item->comment : '';?></p>
                    </div>
                    {% else %}
                    <div class="list">
                        <div class="nothing">暂无评论</div>
                    </div>
                    {% endfor %}
                    <!-- 评价列表 end -->
                    <!-- 评价列表（空） start -->

                    <!-- 评价列表（空） end --> </div>
            </div>
            <!-- 最新评价 end --> </div>
        <!-- 右侧 end --> </div>
</div>

<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->
</body>
</html>
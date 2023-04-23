<!-- 左侧 start -->

            <!-- 服务评价 start -->
            <div class="evaluation mt20">
                <h6>服务评价</h6>
                <ul class="information">


                    <?php if($shopgrade){?>
                    <li>
                        <font>服务态度：</font>
                        <p>{{shopgrade.service}} 星</p>
                    </li>
                    <li>
                        <font>陪同程度：</font>
                        <p>{{shopgrade.accompany}} 星</p>
                    </li>
                    <li>
                        <font>供货能力：</font>
                        <p>{{shopgrade.supply}} 星</p>
                    </li>
                    <li>
                        <font>描述相符：</font>
                        <p>{{shopgrade.description}} 星</p>
                    </li>
                    <?php }else{?>
                    <li>
                        <font>暂无评分</font>
                        <p></p>
                    </li>
                    <?php }?>

                </ul>

            </div>
            <?php if($shopcomments){?>
            <!-- 服务评价 end -->
                <div class="mark mt20" onclick="javascript:newWindows('evaluation', '请填写您的评价', '/member/dialog/evaluation/<?php echo $shop->shop_id; ?>');">
                <span>给他评分</span>
            </div>
            <?php }?>
<!-- 左侧 end -->


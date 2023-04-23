

<!-- 底部开始 -->
<div class="footer">
    <div class="f_contact">
        <div class="left f-fl">
            
       <?php foreach (Mdg\Models\ArticleCategory::showarticle() as $key => $value) {  ?>
            <dl>
                <dt><?php echo $value["catname"] ?></dt>
                <dd><img src="{{ constant('STATIC_URL') }}mdg/images/helpList_h_line.png"></dd>
                <?php foreach ( Mdg\Models\Articlecategory::getcate($value["id"]) as $k=> $v) { ?>
                <dd class="active">&nbsp;<a href="http://www.5fengshou.com/article/index?p=<?php echo $v['id'] ?>"><?php echo $v["title"]?></a></dd>
                <?php } ?>
            </dl>
      
        <?php } ?>
        </div>
        <div class="f-fl mg"><img src="{{ constant('STATIC_URL') }}mdg/images/helpList_s_line.png"></div>
        <!-- <div class="f-fl"><img src="{{ constant('STATIC_URL') }}mdg/images/a2.png"></div> -->
        <div class="f-fl"><img src="{{ constant('STATIC_URL') }}mdg/images/fengshouhui.png"></div>
    </div>
</div>
<div class="fIcp">
    <p>Copyright ©2014,版权所有北京天辰云农场有限公司，京ICP备14023165号-2</p>
    <p>北京天辰云农场有限公司 北京市朝阳区东三环中路39号建外SOHO东区9号楼22F</p>
</div>
<!-- 底部结束 -->


<!-- <div class="footer">
    <div class="f_contact">
        <div class="left f-fl">
            <dl>
                <dt>购物指南</dt>
                <dd><img src="http://www.ync365.com/themes/default_oold/images/topBottom/helpList_h_line.png" /></dd>
                <dd><a href="http://www.ync365.com/article.php?id=42" title="流程说明" target="_blank" >流程说明</a></dd>
                <dd><a href="http://www.ync365.com/article.php?id=904" title="支付方式" target="_blank">支付方式</a></dd>
                <dd><a href="http://www.ync365.com/article.php?id=906" title="注册登录" target="_blank">注册登录</a></dd>
            </dl>
            <dl>
                <dt>联系我们</dt>
                <dd><img src="http://www.ync365.com/themes/default_oold/images/topBottom/helpList_h_line.png" /></dd>
                <dd><a href="http://www.ync365.com/article.php?id=1" title="免责条款" target="_blank">免责条款</a></dd>
                <dd><a href="http://www.ync365.com/article.php?id=46" title="关于我们" target="_blank">关于我们</a></dd>
                <dd><a href="http://www.ync365.com/article.php?id=47" title="联系我们" target="_blank" >联系我们</a></dd>
            </dl>
            <dl>
                <dt>售后服务</dt>
                <dd><img src="http://www.ync365.com/themes/default_oold/images/topBottom/helpList_h_line.png" /></dd>
                <dd><a href="http://www.ync365.com/article.php?id=39" title="售后政策" target="_blank" >售后政策</a></dd>
                <dd><a href="http://www.ync365.com/article.php?id=40" title="退换货原则" target="_blank">退换货原则</a></dd>
                <dd><a href="http://www.ync365.com/article.php?id=43" title="售后流程" target="_blank">售后流程</a></dd>
            </dl>
            <dl>
                <dt>优惠政策</dt>
                <dd><img src="http://www.ync365.com/themes/default_oold/images/topBottom/helpList_h_line.png" /></dd>
                <dd><a href="http://www.ync365.com/article.php?id=895" target="_blank" title="积分使用">积分使用</a></dd>
                <dd><a href="http://www.ync365.com/article.php?id=903" target="_blank" title="红包使用">红包使用</a></dd>
                <dd><a href="http://www.ync365.com/article.php?id=7482" target="_blank"title="余额使用"  >余额使用</a></dd>
            </dl>
        </div>
       <div class="f-fl mg"><img src="{{ constant('STATIC_URL') }}mdg/images/helpList_s_line.png"></div>
        <div class="f-fl"><img src="{{ constant('STATIC_URL') }}mdg/images/a2.png"></div>
    </div>
</div>
<div class="fIcp">
    <p>Copyright ©2014,版权所有北京天辰云农场有限公司，京ICP备14023165号-2</p>
    <p>北京天辰云农场有限公司 北京市朝阳区东三环中路39号建外SOHO东区9号楼22F</p>
</div> 
 -->
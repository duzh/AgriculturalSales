<!-- 底部 -->
<div class="footer-helpList">
    <div class="box mtauto">
        <?php foreach (Mdg\Models\ArticleCategory::showarticle() as $key =>
        $value) {  ?>
        <dl class="list1">
            <dt>
                <?php echo $value["catname"] ?></dt>
            <?php foreach ( Mdg\Models\Articlecategory::getcate($value["id"]) as $k=>
            $v) { ?>
            <dd>
                <a href="/article/index?p=<?php echo $v['id'] ?>
                    ">
                    <?php echo $v["title"]?></a>
            </dd>
            <?php } ?></dl>
        <?php } ?></div>
</div>
<div class="footer-weixin">
    <img src="{{ constant('STATIC_URL') }}/mdg/version2.4/images/footer-weixin.png"></div>
<div class="footer-Icp">
    <p>
        Copyright ©2014,版权所有北京天辰云农场有限公司，京ICP备14023165号-2北京天辰云农场有限公司 北京市朝阳区东三环中路39号建外SOHO东区9号楼22F
    </p>
</div>

<!-- 侧边栏 
<div class="sideBar clearfix">
    <a class="app" href="#">APP</a>
    <a class="back" href="#"> <font>意见
            <br />
            反馈</font> 
    </a>
    <a class="online" href="#"> <font>在线
            <br />
            客服</font> 
    </a>
    <a class="move-top" href="javascript:;"></a>
</div>
-->

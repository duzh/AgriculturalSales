 <div class="footer-helpList">
        <div class="w1190 mtauto f-oh">
        
        <?php foreach (Mdg\Models\ArticleCategory::showarticle() as $key =>
        $value) {  ?>
            <dl class="list1">
                <dt><?php echo $value["catname"] ?></dt>
            <?php foreach ( Mdg\Models\Articlecategory::getcate($value["id"]) as $k=>
            $v) { ?>
                <dd>
                     <a href="/article/index?p=<?php echo $v['id'] ?>
                    ">
                    <?php echo $v["title"]?></a>
                </dd>
              <?php } ?>     
            </dl>
        <?php } ?>   
            <div class="images f-fr">
                <img class="hotLine" src="{{ constant('STATIC_URL') }}mdg/version2.5/images/footer-hotLine.png" alt="">
                <img class="wx" src="{{ constant('STATIC_URL') }}mdg/version2.5/images/footer-wx.png" alt="">
                <img class="app" src="{{ constant('STATIC_URL') }}mdg/version2.5/images/newfooter-app.png" alt="">
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="w1190 mtauto f-oh f-pr">
            <div class="friendLinks">
                <font>友情链接：</font>
				
                <?php 
				$location='2';
				if(isset($homefooter)){$location='1,2';}				
				foreach ( \Mdg\Models\WebsiteLink::showlinks($location) as $k=>$v) { 				
				?>
                <a href="http://<?php echo $v['website_link'] ?>" target="_blank"><?php echo $v["website_name"]?></a><em>丨</em>
                <?php } ?> 
				
            </div>
            <div class="Icp">
                <p>北京天辰云农场有限公司 北京市朝阳区东三环中路39号建外SOHO东区9号楼22F  Copyright ©2014,版权所有北京天辰云农场有限公司，京ICP备14023165号-2</p>
            </div>
            <style>
                .addRz{ width:83px; height:30px; right:0; bottom:0;}
            </style>
            <div class="addRz pa">
                <a  key ="55cc0d6eefbfb072f1c22c2f"  logo_size="83x30"  logo_type="business"  href="http://www.anquan.org" ><script src="http://static.anquan.org/static/outer/js/aq_auth.js"></script></a>
            </div>
        </div>
    </div>

    <!-- 侧边栏 -->
    <div class="scrollBox">
        <div class="box clearfix">

            <!-- 查询 -->
            <div class="cx">
                <a href="/map/map">
                    <div class="imgs"></div>
                    <div class="imgTips">
                        <div class="right-arrow"></div>
                        <span>服务站&可信农场查询</span>
                    </div>
                </a>
            </div>
            <!-- 下载APP -->
            <div class="load-app">
                <a href="/index/ad">
                    <div class="imgs"></div>
                    <div class="imgTips">
                        <div class="right-arrow"></div>
                        <span>下载APP</span>
                    </div>
                </a>
            </div>
            <!-- 意见反馈 -->
           <!--  <div class="feed-back">
                <a href="#">
                    <div class="imgs"></div>
                    <div class="imgTips">
                        <div class="right-arrow"></div>
                        <span>意见反馈</span>
                    </div>
                </a>
            </div> -->
            <!-- 联系客服 -->
            <div class="contact-service">
                <a href="http://www.5fengshou.com/article/index?p=15">
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
    <script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?88aad2902f4d83623cc6166f7cd1af0b";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-65072905-1', 'auto');
  ga('send', 'pageview');

</script>

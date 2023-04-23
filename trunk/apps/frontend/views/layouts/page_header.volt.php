    <div class="header-top">
        <div class="w1185 mtauto clearfix">
            <?php if (!$this->session->user) { ?>
            <!-- 未登录 -->
            <div class="welcome f-fl">
                <font>您好，欢迎来到云农场网上交易平台！</font>
                <span>
                    请
                    <a href="/member/login">登录</a>&nbsp;&nbsp;|&nbsp;
                    <a href="/member/register">注册</a>
                </span>
            </div>
            <?php } elseif ($this->session->user['mobile']) { ?>

             <div class="welcome-login f-fl">
                <font>您好，<?php echo $this->session->user['name']; ?>   欢迎来到云农场网上交易平台！</font>
                <a class="news" href="/member/messageuser/list">(<?php echo $messagecount; ?>)</a>
                <span>
                    <label class="user">
                        <a href="/member/index">用户中心</a>
                    </label>

                    <!--  检测用户是否完善信息 --> 
                    <?php if ($userInfo) { ?>
                    <label class="list">
                        <a class="close" href="javascript:;">x</a>
                        <a class="ws" href="/member/">完善个人信息</a>
                    </label>
                    <?php } ?>

                </span>&nbsp;&nbsp;|&nbsp;
                <a class="out" href="/member/index/logout">退出</a>
            </div>
            <?php } ?>

            <div class="menu f-fr">
                <font>
                    <a href="/member/ordersbuy/index">我的订单</a>
                </font>
                <font>
                    <a href="#">收藏我们</a>
                </font>
                <font class="has-list">
                    <a class="attr" href="javascript:;">关注我们</a>
                    <span class="wx">
                        <img src="<?php echo constant('STATIC_URL'); ?>/mdg/version2.4/images/header-wx.jpg">
                    </span>
                </font>
                <font class="has-list">
                    <a class="attr" href="javascript:;">客户服务</a>
                    <span class="list">
                        <a href="#">在线客服</a>
                        <a href="#">联系客服</a>
                        <a href="#">帮助中心</a>
                    </span>
                </font>
            </div>

        </div>
    </div>
    <!-- 搜索 -->
    <div class="header-search">
        <div class="w1185 mtauto clearfix">
            <a class="logo f-fl" href='/index'>
                <img src="<?php echo constant('STATIC_URL'); ?>/mdg/version2.4/images/logo.png">
            </a>
            <form action="/index/search" >
            <div class="search-box f-fl">
                <div class="left f-fl clearfix">

                    <div class="selectBox f-fl">
                        <span>
                            <?php 
                            $con = '供应';
                            if(isset($_GET['search_header_type']) && $_GET['search_header_type']) {
                                switch ($_GET['search_header_type']) {
                                    case  'pur':
                                        $con = '采购';
                                        break;
                                    case  'shop':
                                        $con = '店铺';
                                        break;
                                    case  'tag':
                                        $con = '溯源码';
                                        break;
                                    default:
                                        $con = '供应';
                                        break;
                                }
                            }
                            echo $con;
                            ?>
                        </span>
                        <div class="list">
                            <!-- <a href="javascript:;" id='all'><font>全部</font></a> -->
                            <a href="javascript:;" id='sell'><font>供应</font></a>
                            <a href="javascript:;" id='pur'><font>采购</font></a>
                            <a href="javascript:;" id='shop'><font>店铺</font></a>
                            <a href="javascript:;" id='tag'><font>溯源码</font></a>
                        </div>
                    </div>
                    <div class="textBox f-fl">
                        <input type="text" name='keywords' <?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>  placeholder="产品种类" />
                    </div>
                </div>
                <input type="hidden" name='search_header_type'  id='header_search'  value='sell'>
                <input class="btn f-fl" type="submit" value="搜索" />
            </div>
            </form>
            <img class="f-fr hotLine" src="<?php echo constant('STATIC_URL'); ?>/mdg/version2.4/images/header-hotLine.jpg">
        </div>
    </div>
    
    <!-- 导航 -->
    <div class="header-navigation">
        <div class="w1185 mtauto clearfix f-pr">

            <ul class="across-list clearfix">
                
                <li >
                    <a href="/index"> <?php echo $_nav; ?> 首页</a>
                </li>
                <li class="has-list" >
                    <a href="/sell/index/" >供应大厅</a>
                    <div class="small-list commonW1">
                        <?php if ($haeder_cate) { ?>
                            <?php foreach ($haeder_cate as $key => $val) { ?>
                            <font><a  href="/sell/index?mc=<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a></font>
                            <?php } ?>
                        <?php } ?>
                        
                    </div>
                </li>
                <li class="has-list">
                    <a href="/purchase/index/">采购中心</a>
                    <div class="small-list commonW2">
                        <?php if ($haeder_cate) { ?>

                            <?php foreach ($haeder_cate as $key => $val) { ?>
                            <font><a  href="/purchase/index?mc=<?php echo $val['id']; ?>"><?php echo $val['title']; ?></a></font>
                            <?php } ?>
                        <?php } ?>
                        
                    </div>
                </li>
                <li>
                    <a href="/market/index/">价格行情</a>
                </li>
                <li>
                    <a href="/product/fao/">安全种植体系</a>
                </li>
            </ul>
            
            <!-- 
            **  class为navAllsort时是未登录的导航类  此时“我的关注”隐藏 
            **  class为navAllsort和navAllsort-login时是已登录的导航类  此时“我的关注”显示 
            -->
            <div class="vertical-navigation">
                <h6>全部农产品分类</h6>
                <!--  -->

                <div class="navAllsort" <?php if ($_nav == 'index') { ?>style="display:block;" <?php } ?>>
                    <div class="fore showFore">
                        <div class="fore8 clearfix">
                            <span>
                                <a href="#">我的关注</a>
                            </span>
                            <font></font>
                        </div>

                        <div class="foreBox">
                            <div class="fb-list1">
                                <a href="#">大米</a>
                            </div>
                        </div>
                    </div>
                    <?php if ($haeder_cate) { ?>
                    <?php foreach ($haeder_cate as $key => $item) { ?>
                    <div class="fore">
                        <div class="fore<?php echo $key + 1; ?> clearfix">
                            <span>
                                <a href="#"><?php echo ($item['title'] ? $item['title'] : ''); ?></a>
                            </span>
                            <font></font>
                        </div>

                        <div class="foreBox">
                           <?php if(isset($item['cate'])  && $item['cate']) { ?>
                            <?php foreach ($item['cate'] as $row => $val) { ?>

                            <div class="fb-list<?php echo $row; ?>">
                                <?php foreach ($val as $v) { ?>
                                <a href="/sell/index?c=<?php echo $v['id']; ?>"><?php echo $v['title']; ?></a>
                                <?php } ?>
                            </div>
                            <?php } ?>
                            <?php } ?>
                            
                        </div>
                    </div>
                    <?php } ?>
                    <?php } ?>
                 </div>   
            </div>

        </div>
    </div>



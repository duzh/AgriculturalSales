<?php echo $this->partial('layouts/page_header'); ?>
<!-- 轮播图 -->
<div class="ck-slide">

    <ul class="ck-slide-wrapper">
        <?php foreach ($adimg as $key => $ad) { ?>
        <li <?php if ($key == 0) { ?>style="display:block;" <?php } ?>>
            <a href="<?php echo $ad->adsrc; ?>">
                <img <?php if ((!$key)) { ?>style="display:block;"<?php } ?> src="<?php echo constant('IMG_URL'); ?><?php echo $ad->imgpath; ?>" />
            </a>
        </li>
        <?php } ?>
    </ul>

    <div class="ck-slidebox">
        <div class="slideWrap">
            <ul class="dot-wrap">
                <?php foreach ($adimg as $key => $ad) { ?>
                <li <?php if ($key == 0) { ?>class="current"<?php } ?>> <em><?php echo $key; ?></em>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <!-- 轮播图 end -->

    <div class="focus-right w1185 mtauto">
        <div class="box">
            <div class="top">
                <div class="hello">
                    <?php if ($this->session->user) { ?>
                    <p>
                        <span>Hi，<?php echo $this->session->user['name']; ?> <?php echo $timeName; ?>！</span>
                        <br />
                        欢迎来到丰收汇5fengshou.com
                    </p>
                    <?php } else { ?>
                    <p>
                        <span>Hi，请登录！</span>
                        <br />
                        欢迎来到丰收汇5fengshou.com
                    </p>
                    <?php } ?>
                </div>
                <div class="btns">
                    <a class="btn1" href="/member/sell/new">发布产品</a>
                    <a class="btn2" href="javascript:newWindows('newpur', '发布采购', '/member/dialog/newpur');">发布采购</a>
                </div>
            </div>

            <div class="today-market">
                <div class="title">今日行情</div>
                <div class="time"><?php echo date('Y.m.d'); ?><?php echo $getN; ?></div>
                <div class="scroll-box">
                    <ul class="content">
                        <?php foreach ($dayAdvisory as $key => $item) { ?>
                        <li class="link">
                            <a href="/advisory/info?p=<?php echo $item['id']; ?>">
                                <span>[<?php echo $item->title; ?>]</span>
                                <?php echo $item->description; ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                    <div class="scroll-bar">
                        <span></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="wrapper">

    <div class="statis-num w1185 mtauto">
        <div class="list list1">
            <span>
                注册用户
                <br /> <strong><?php echo $usercount; ?></strong>
            </span>
        </div>
        <div class="list list2">
            <span>
                店铺数量
                <br /> <strong><?php echo $shopcount; ?></strong>
            </span>
        </div>
        <div class="list list3">
            <span>
                可信农场数量
                <br />
                <strong><?php echo $storecount; ?></strong>
            </span>
        </div>
        <div class="list list4">
            <span>
                服务站数量
                <br />
                <strong><?php echo $servicecount; ?></strong>
            </span>
        </div>
        <div class="list list5">
            <span>
                成交订单量
                <br />
                <strong><?php echo $ordercount; ?></strong>
            </span>
        </div>
    </div>

    <div class="trust-farm w1185 mtauto">
        <div class="title f-oh">
            <img class="f-fl" src="<?php echo constant('STATIC_URL'); ?>/mdg/version2.4/images/trust-farm-title.png">
            <a class="more f-fr" href="#">更多</a>
        </div>
        <div class="list clearfix">
            <?php foreach ($stores['data'] as $key => $item) { ?>
            <div class="farmImg">
                <a href="<?php echo $item['url']; ?>">
                    <div class="imgs f-oh">
                        <img class="f-fl" src="<?php echo $item['img']; ?>" width='219px' height='219px'></div>
                    <div class="imgName"><?php echo $item['shop_name']; ?></div>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
    <div id='indexcenter'></div>

    <!-- 流程图 -->
    <div class="flow-chart w1185 mtauto">
        <div class="title">
            <img src="<?php echo constant('STATIC_URL'); ?>/mdg/version2.4/images/flow-chart-title.png"></div>
        <div class="box f-oh">
            <img class="f-fl" src="<?php echo constant('STATIC_URL'); ?>/mdg/version2.4/images/flow-chart-img.png">
            <div class="btns f-fr">
                <a class="btn1" href="/member/sell/new">立即发布供应</a>
                <a class="btn2" href="">立即发布采购</a>
            </div>
        </div>
    </div>

</div>
<?php echo $this->partial('layouts/footer'); ?>
<script type="text/javascript">$('#indexcenter').load('index/indexcenter');</script>
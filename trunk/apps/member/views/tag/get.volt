<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}标签查看</span>
        </div>
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
        <div class="center-right f-fr">

            <div class="tag-view f-oh">
                <div class="title f-oh">
                    <span>标签查看</span>
                </div>
                {% if !data.path %}
                <script type="text/javascript" src='/member/tag/getqrcode?tid={{data.tag_id}}&sellid={{data.sell_id}}'></script>
                {% endif %}
                <div class="m-box f-oh f-pr">
                    <div class="message clearfix">
                        <font>品      名：</font>
                        <div class="wz">{{data.goods_name}}</div>
                    </div>
                    <div class="message clearfix">
                        <font>溯 源 码：</font>
                        <div class="wz">{{data.source_no}}</div>
                    </div>
                    <div class="message clearfix">
                        <font>价      格：</font>
                        <div class="wz">{{data.min_price}}~ {{data.max_price}}</div>
                    </div>
                    <div class="message clearfix">
                        <font>生产日期：</font>
                        <div class="wz">{{data.product_date}}</div>
                    </div>
                    <div class="message clearfix">
                        <font>保 质 期：</font>
                        <div class="wz">{{ data.expiration_date}}天</div>
                    </div>
                    <div class="message clearfix">
                        <font>产      地：</font>
                        <div class="wz">{{  data.full_address }}</div>
                    </div>
                    <div class="tag-wx">
                        <img src="{{ erweitu }}" alt="">
                        <div class="xzbq f-tac">
                            {% if isdownload %}
                            <a style="color:#2d64b3;" href="/member/tag/download?id={{data.tag_id}}">下载标签</a>
                            {% endif %}
                        </div>
                    </div>
                </div>
            </div>

        </div>      

    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}

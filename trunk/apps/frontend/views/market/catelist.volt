{{ partial('layouts/page_header') }}
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/css/base.css">
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/css/index.css">
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/css/wuliu.css">
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/js/jquery-1.11.1.min.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/js/index.js"></script>

<div class="wuliu_body_v2">
    <div class="wuliu_v2 w1190 mtauto">
        <div class="mianbao_v2">
            <a href="/">首页</a>
            <span>&gt;</span>
            <a href="/market/">价格行情</a>
            <span>&gt;</span>
            <a href="/market/catelist?cid=1">行情大厅</a>
        </div>
        
        <div class="fenlei_v2">
            <div class="fenlei_top_v2">
                <span>分类：</span>
                <div class="fenlei_zhonglei_v2">
                    <a href="/market/catelist" {% if cid == 0 %} class='on'{% endif %} >全部</a>
                    {% for key, item in chid %}
                    <a href="/market/catelist?cid={{ item.id}}" {% if cid == item.id %}class='on'{% endif %}>
                        {{ item.title }}
                    </a>
                    {% endfor %}
                </div>
            </div>
            <div class="fenlei_bot_v2">
                <span>按首字母：</span>
                <div class="fenlei_zimu_v2">
                    <a href="#" class="on">全部</a>
                    <a href="#wl-name-a">A</a>
                    <a href="#wl-name-b">B</a>
                    <a href="#wl-name-c">C</a>
                    <a href="#wl-name-d">D</a>
                    <a href="#wl-name-e">E</a>
                    <a href="#wl-name-f">F</a>
                    <a href="#wl-name-g">G</a>
                    <a href="#wl-name-h">H</a>
                    <a href="#wl-name-i">I</a>
                    <a href="#wl-name-j">J</a>
                    <a href="#wl-name-k">K</a>
                    <a href="#wl-name-l">L</a>
                    <a href="#wl-name-m">M</a>
                    <a href="#wl-name-n">N</a>
                    <a href="#wl-name-o">O</a>
                    <a href="#wl-name-p">P</a>
                    <a href="#wl-name-q">Q</a>
                    <a href="#wl-name-r">R</a>
                    <a href="#wl-name-s">S</a>
                    <a href="#wl-name-t">T</a>
                    <a href="#wl-name-u">U</a>
                    <a href="#wl-name-v">V</a>
                    <a href="#wl-name-w">W</a>
                    <a href="#wl-name-x">X</a>
                    <a href="#wl-name-y">Y</a>
                    <a href="#wl-name-z">Z</a>
                </div>
            </div>
        </div>
        
        <div class="fenlei_con_v2">
            <div class="fenlei_list_v2">
                <div class="fenlei_listT_v2">
                    <div class="fenlei_lineLeft_v2">&nbsp;</div>
                    <div class="fenlei_lineRight_v2">
                        <table>
                            <tr>
                                <td width="20%">产品名</td>
                                <td width="23%">今日均价/单位</td>
                                <td width="23%">昨日均价/单位</td>
                                <td width="24%">升降幅度（元）</td>
                                <td width="10%">查看详情</td>
                            </tr>
                        </table>
                    </div>
                </div>
                {% for Dkey , Ditem in data %}
                    {% if Ditem %}
                    <div id="wl-name-<?php echo strtolower("{$Dkey}");?>"></div>
                    <div class="fenlei_zimuline_v2">
                        <div class="fenlei_lineLeft_v2">
                            <strong><?php echo strtolower("{$Dkey}");?></strong>
                        </div>
                        <div class="fenlei_lineRight_v2 fenlei_lineP_v2">
                            <table>
                                {% for key, item in Ditem %}
                                <tr>
                                    <td width="20%">{{ item['title']}}</td>
                                    <td width="23%">{{ item['today_avgprice'] }}元/{{ item['unit']}}</td>
                                    <td width="23%">{{ item['yesterday_avgprice'] }}元/{{ item['unit']}}</td>
                                    <td width="24%"><span class="jg_{% if item['ppp'] > 0%}sheng{% else %}jiang{% endif %}_v2">￥{{ item['diff']}}({{ item['ppp']}}%)</span></td>
                                    <td width="10%" class="chakan_link_v2"><a href="/market/get?cid={{ item['mpid']}}">查看</a></td>
                                </tr>
                                {% endfor %}
                            </table>
                        </div>
                    </div>
                    {% endif %}
                {% endfor %}
            </div>
            <div class="fenlei_right_v2">
                <div class="fenlei_rightCon_v2">
                    <div class="fenlei_rightT_v2"><strong>最新行业资讯</strong></div>
                    <div class="fenlei_rightBox_v2">
                         {% for key, item in advisory %}
                        <dl>
                            <dt>
                                <a href="/advisory/adinfo?id={{item['id']}}">
                                <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" height="80" width="120">
                                </a>
                            </dt>
                            <dd>
                                <a href="/advisory/adinfo?id={{item['id']}}" title="">{{item.title}}</a>
                                <span>{{date('Y/m/d',item.addtime)}}</span>
                            </dd>
                        </dl>
                        {% endfor %}
                    </div>
                </div>

                <div class="fenlei_rightCon_v2">
                    <div class="fenlei_rightT_v2"><strong>热门供应产品</strong></div>
                    <div class="fenlei_rightBox_v2">
                        {% for key, item in hot%}
                        <dl>
                            <dt>
                                <a href="/sell/info/{{item['id']}}">
                                {% if item['thumb'] %}
                                  <img src="{{ constant('IMG_URL') }}{{item['thumb']}}" height="80" width="120">
                                {% else %}
                                   <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" height="80" width="120">
                                {% endif %}
                                </a>
                            </dt>
                            <dd>
                                <a href="/sell/info/{{item['id']}}" title="">{{item['title']}}</a>
                                <span>￥{{item['min_price']}}~￥{{item['max_price']}}</span>
                                <span><?php echo Lib\Utils::getC($item["areas_name"] ? $item['areas_name'] : ''); ?></span>
                            </dd>
                        </dl>
                        {% endfor %}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="zzs-iscroll">
    <div class="zzs-iscrollBox" id="c_nav">
        <ul class="zimunav">
            <li><a href="#wl-name-a">A</a></li>
            <li><a href="#wl-name-b">B</a></li>
            <li><a href="#wl-name-c">C</a></li>
            <li><a href="#wl-name-d">D</a></li>
            <li><a href="#wl-name-e">E</a></li>
            <li><a href="#wl-name-f">F</a></li>
            <li><a href="#wl-name-g">G</a></li>
            <li><a href="#wl-name-h">H</a></li>
            <li><a href="#wl-name-i">I</a></li>
            <li><a href="#wl-name-j">J</a></li>
            <li><a href="#wl-name-k">K</a></li>
            <li><a href="#wl-name-l">L</a></li>
            <li><a href="#wl-name-m">M</a></li>
            <li><a href="#wl-name-n">N</a></li>
            <li><a href="#wl-name-o">O</a></li>
            <li><a href="#wl-name-p">P</a></li>
            <li><a href="#wl-name-q">Q</a></li>
            <li><a href="#wl-name-r">R</a></li>
            <li><a href="#wl-name-s">S</a></li>
            <li><a href="#wl-name-t">T</a></li>
            <li><a href="#wl-name-u">U</a></li>
            <li><a href="#wl-name-v">V</a></li>
            <li><a href="#wl-name-w">W</a></li>
            <li><a href="#wl-name-x">X</a></li>
            <li><a href="#wl-name-y">Y</a></li>
            <li><a href="#wl-name-z">Z</a></li>
        </ul>
    </div>
</div>
<script src="{{ constant('STATIC_URL')}}/mdg/market/js/bootstrap.min.js"></script>
<script>
$(function () {
    $('body').scrollspy({ target: '#c_nav' });
    
    $('#c_nav a').click(function(e){
        e.preventDefault(); 
        var linkId = $(this).attr('href');
        scrollTo(linkId);
        return false;
    });
    
    function scrollTo(selectors)
    {
        if(!$(selectors).size()) return;
        var selector_top = $(selectors).offset().top 
        $('html,body').animate({ scrollTop: selector_top }, 'slow');
    }

    // 返回顶部
    (function(){
        var $back_to_top = $('.goTop');
        $back_to_top.on('click', function(event){
            event.preventDefault();
            $('body,html').animate({scrollTop: 0}, 300);
        });
    })();

    $(window).scroll(function(){
        t = $(document).scrollTop();
        if(t>400){
          $('.zzs-iscroll').show();
        }else{
          $('.zzs-iscroll').hide();
        }
    })         

});
</script>
{{ partial('layouts/footer') }}
<style>
    .gl{ margin:10px 0;}
    .gl a{ color:#05780a;}
    .gl a:hover{ text-decoration: underline;}
</style>
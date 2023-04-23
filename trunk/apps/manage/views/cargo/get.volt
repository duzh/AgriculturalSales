<link rel="stylesheet" type="text/css" href="{{ constant('WULIU_URL') }}/mdg/wuliu/css/style.css" /> 
<link rel="stylesheet" href="">
    <div class="wl-Con wl-auto clearfix">
        <div class="wl-conLeft wl-fl">
            <div class="wl-che-selectBox">
                <div class="wl-che-title">
                    济南到北京的车辆
                </div>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="b-l-n"><strong>车源编号</strong></td>
                        <td>{{cargoinfo['goods_no']}}</td>
                        <td><strong>发布日期</strong></td>
                        <td class="b-r-n">{{ cargoinfo['sendtime']}}</td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>货源类别：</strong></td>
                        <td><?php echo Mdg\Models\CargoInfo::$_goods_type[$cargoinfo['goods_type']]?></td>
                        <td><strong>需要车辆类型：</strong></td>
                        <td class="b-r-n"><?php echo Mdg\Models\CargoInfo::$_box_type[$cargoinfo['box_type']]?></td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>需要车辆长度：</strong></td>
                        <td>{{cargoinfo['except_length']!=0.00 ? cargoinfo['except_length'] : 0}}米</td>
                        <td><strong>重量：</strong></td>
                        <td class="b-r-n">{{cargoinfo['goods_weight']!=0.00 ? cargoinfo['goods_weight'] : 0}}吨</td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>体积：</strong></td>
                        <td>{{cargoinfo['goods_size']!=0.00 ? cargoinfo['goods_size'] : 0}}方</td>
                        <td><strong>预期运费：</strong></td>
                        <td class="b-r-n">{{cargoinfo['except_price']!=0.00 ? cargoinfo['except_price'] : 0}}元/吨</td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>出发地：</strong></td>
                        <td>{{cargoinfo['start_pname']}}{{cargoinfo['start_cname']}}{{cargoinfo['start_dname']}}</td>
                        <td><strong>目的地：</strong></td>
                        <td class="b-r-n">{{cargoinfo['end_pname']}}{{cargoinfo['end_cname']}}{{cargoinfo['end_dname']}}</td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>结算方式：</strong></td>
                        <td>{{cargoinfo['settle_type'] ? cargoinfo['settle_type'] : '无'}}</td>
                        <td><strong>信息有效时间：</strong></td>
                        <td class="b-r-n">
                            {% if cargoinfo['expire_time']%}
                            {{date("Y-m-d",cargoinfo['expire_time'])}}
                            {% else %}
                            无
                            {% endif %}
                        </td>   
                    </tr>
                    <tr>
                        <td class="b-l-n" ><strong>联系人手机号：</strong></td>
                        <td id="span_id"><?php if($cargoinfo['contact_phone']){
                            echo substr_replace($cargoinfo['contact_phone'],'****',3,4);
                        }else{
                            echo $cargoinfo['phone_number'];
                        }
                        ?><a href='#' onclick="funclook('http://yncmdg.b0.upaiyun.com/{{cargoinfo['phone_img']}}')">(查看)</a></td>

                       <td><strong>是否长期：</strong></td>
                        <td class="b-r-n">
                            {% if cargoinfo['is_long']==1 %}
                            是
                            {% else %}
                            否
                            {% endif %}
                        </td>   

                    </tr>
                    <tr>
                        <td class="b-l-n b-b-n"><strong>备注</strong></td>
                        <td class="b-r-n b-b-n" colspan="3" title="{{ cargoinfo['demo']}}"><?php echo mb_substr($cargoinfo['demo'],0,50) ? $cargoinfo['demo']."..." : '无';?></td>
                    </tr>
                </table>
            </div>
        </div>
        <script>
    function funclook(value){
        $("#span_id").html("<img src="+value+">");
    }

</script>
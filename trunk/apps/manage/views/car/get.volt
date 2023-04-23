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
                        <td>{{ data['car_no'] }}</td>
                        <td><strong>发布日期</strong></td>
                        <td class="b-r-n">{{ data['sendtime']}}</td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>出发地址</strong></td>
                        <td>{{ data['start_pname']}}{{ data['start_cname']}}{{ data['start_dname']}}</td>
                        <td><strong>期望流向</strong></td>
                        <td class="b-r-n">{{ data['end_pname']}}{{ data['end_cname']}}{{ data['end_dname']}}</td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>预计发车时间</strong></td>
                        <td>{{data['ext']['departtime']}}</td>
                        <td><strong>载重</strong></td>
                        <td class="b-r-n">{{ data['ext']['carry_weight'] }}吨</td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>车辆信息</strong></td>
                        <td>{{ _BOX_TYPE[data['ext']['box_type']] }}　　{{  _BODY_TYPE[data['ext']['body_type']]}}</td>
                        <td><strong>车板长度</strong></td>
                        <td class="b-r-n">{{ data['ext']['length']}}米</td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>车牌号</strong></td>
                        <td>{{ data['car_licence'] }}</td>
                        <td><strong>联系人</strong></td>
                        <td class="b-r-n">{{ data['contact_man'] }}</td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>手机号</strong></td>
                        <td>{{ data['contact_phone'] }}</td>
                        <td><strong>重货价</strong></td>
                        <td class="b-r-n">
                            <?php echo $data['heavy_goods']>0 ? $data['heavy_goods']. '元/吨' : '面议'; ?>
                            </td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>轻货价</strong></td>
                        <td><?php echo $data['light_goods']>0 ? $data['light_goods']. '元/方' : '面议'; ?></td>
                        <td><strong>车龄</strong></td>
                        <td class="b-r-n">{{ data['ext']['use_time'] }}年</td>
                    </tr>

                    <tr>
                        <td class="b-l-n"><strong>是否长期</strong></td>
                        <td><?php  echo $data['ext']['is_longtime'] == 1 ? '是': '否'; ?></td>
                        <td><strong>运行方式</strong></td>
                        <td class="b-r-n"><?php echo isset($_TRANSPORT_TYPE[$data['ext']['transport_type']]) ? $_TRANSPORT_TYPE[$data['ext']['transport_type']] : ''; ?></td>
                    </tr>
                    
                    <tr>
                        <td class="b-l-n b-b-n"><strong>备注</strong></td>
                        <td class="b-r-n b-b-n" colspan="3" title="{{ data['ext']['demo']}}"><?php echo mb_substr($data['ext']['demo'],0,50)."...";?></td>
                    </tr>
                </table>
            </div>
        </div>

<title>专线查看</title>
<link rel="stylesheet" href="http://mdgdev.ync365.com/mdg/wuliu/css/style.css">
    <div class="wl-Con wl-auto clearfix">
        <div class="wl-conLeft wl-fl">
            <div class="wl-che-selectBox">
                <div class="wl-che-title">
                    {{data.start_pname}}到{{data.end_pname}}专线
                </div>
                <div class="wl-zx-box">
                    <div class="wl-zx-l">
                        <table>
                            <tr>
                                <td class="b-l-n" colspan="2"><strong>货物出发地</strong></td>
                            </tr>
                            <tr>
                                <td class="b-l-n"><strong>出发地</strong></td>
                                <td>{{ data.start_pname}}{{data.start_cname}}{{data.start_dname}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>网点名</strong></td>
                                <td>{{ start['net_name']}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>公司名</strong></td>
                                <td>{{ start['company_name']}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>联系人</strong></td>
                                <td>{{ start['contact_man']}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>固定电话</strong></td>
                                <td>{{ start['fix_phone']}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>手机</strong></td>
                                <td>{{ start['mobile_phone']}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>QQ</strong></td>
                                <td>{{ start['qq']}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n b-b-n"><strong>地址</strong></td>
                                <td class="b-b-n">{{ start['address']}}</td>
                            </tr>




                        </table>
                    </div>
                    <div class="wl-zx-r">
                        <table>
                            <tr>
                                <td class="b-r-n" colspan="2"><strong>货物目的地</strong></td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>目的地</strong></td>
                                <td>{{ data.end_pname}}{{data.end_cname}}{{data.end_dname}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>网点名</strong></td>
                                <td>{{ end['net_name']}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>公司名</strong></td>
                                <td>{{ end['company_name']}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>联系人</strong></td>
                                <td>{{ end['contact_man']}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>固定电话</strong></td>
                                <td>{{ end['fix_phone']}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>手机</strong></td>
                                <td>{{ end['mobile_phone']}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n"><strong>QQ</strong></td>
                                <td>{{ end['qq']}}</td>
                            </tr>
                            <tr>
                                <td class="b-r-n b-b-n"><strong>地址</strong></td>
                                <td class="b-b-n">{{ end['address']}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="wl-bot-xinx">
                    <table>
                        <tr>
                            <td class="b-l-n b-t-n"><strong>单程/往返</strong></td>
                            <td class="b-t-n"><strong>重货价</strong></td>
                            <td class="b-t-n"><strong>轻货价</strong></td>
                            <td class="b-r-n b-t-n"><strong>备注</strong></td>
                        </tr>
                        <tr>
                            <td class="b-l-n b-b-n">
                                {% if data.type %}
                                往返
                                {% else %}
                                单程
                                {% endif %}
                            </td>
                            <td class="b-b-n">{{ data.light_price}}元/吨</td>
                            <td class="b-b-n">{{ data.heavy_price}}元/方</td>
                            <td class="b-r-n b-b-n">{{ data.demo}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>
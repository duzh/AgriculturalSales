{% if data.status == 0 %}
<input type="button" value="审核通过" class="btn" onclick="ShowDiv('MyDiv','fade')"/>
<input type="button" value="审核未通过" class="btn" onclick="ShowDiv1('MyDiv1','fade1')"/>
{% endif %}

<div class="main">
  <div class="main_right">
    <div class="bt2">标签详细</div>
    <div align="left" style="margin-top:20px;">
      <div>
       

          <div class="cx">

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td  colspan="2" class="cx_title1">1.基本信息</td>
              </tr>
              <tr>
                <td class="cx_title">品名：</td>
                <td class="cx_content">{{ data.goods_name}}</td>
              </tr>

              <tr>
                <td class="cx_title">所属分类：</td>
                <td class="cx_content">{{ cname }}</td>
              </tr>

              <tr>
                <td class="cx_title">*  产地：</td>
                <td class="cx_content">{{ address }}{{ data.address }}</td>
              </tr>
              <tr>
                <td class="cx_title">*  生产商：</td>
                <td class="cx_content">{{ data.productor }}</td>
              </tr>

              <tr>
                <td class="cx_title">*  生产日期：</td>
                <td class="cx_content">{{ data.product_date }}</td>
              </tr>
              <tr>
                <td class="cx_title">*  保质期：</td>
                <td class="cx_content">{{ data.expiration_date }}</td>
              </tr>
              <tr>
                <td class="cx_title">加工地：</td>
                <td class="cx_content">{{ data.process_place}}</td>
              </tr>
              <tr>
                <td class="cx_title">加工商：</td>
                <td class="cx_content">{{ data.process_merchant}}</td>
              </tr>
            </table>

            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
              <tr colspan="2">
                <td class="cx_title1" colspan="2">2. 质量评估</td>
              </tr>
              <tr>
                <td class="cx_title">质量等级：</td>
                <td class="cx_content">
                  <?php echo isset($data->
                  TagQuality->quality_level) ? $data->TagQuality->quality_level : 0?>
                </td>
              </tr>

              <tr>
                <td class="cx_title">农残含量：</td>
                <td class="cx_content">
                  <?php echo isset($data->
                  TagQuality->pesticide_residue) ? $data->TagQuality->pesticide_residue : 0?>
                </td>
              </tr>
              <tr>
                <td class="cx_title">重金属含量：</td>
                <td class="cx_content">
                  <?php echo isset($data->
                  TagQuality->heavy_metal) ? $data->TagQuality->heavy_metal : 0?>
                </td>
              </tr>
              <tr>
                <td class="cx_title" valign="top"> 是否转基因：</td>
                <td >
                  <?php echo isset($data->TagQuality->is_gene) ? $data->TagQuality->is_gene ? '是' : '否': '否' ;?></div>
              </td>
            </tr>
            <!-- <tr>
              <td class="cx_title">检验员：</td>
              <td class="cx_content">
                <?php echo isset($data->TagQuality->inspector) ? $data->TagQuality->inspector : 0?></td>
            </tr>
            <tr>
              <td class="cx_title">检验时间：</td>
              <td class="cx_content">
                <?php echo isset($data->
                TagQuality->inspecttime) ? $data->TagQuality->inspecttime : 0?>
              </td>
            </tr>
            <tr >
              <td class="cx_title">安全鉴定机构名称：</td>
              <td class="cx_content">
                <?php echo isset($data->
                TagQuality->certifying_agency) ? $data->TagQuality->certifying_agency : 0; ?>
              </td>
            </tr> -->

            <tr>
              <td class="cx_title">权威机构安全鉴定文件：</td>
              <td class="cx_content">
                <?php
                                    if(isset($TagCertifying) &&$TagCertifying) {
                                      foreach ($TagCertifying as $key => $val) {
                                        echo " <img src='{$val['path']}' alt='' width='201px' height='201px'>";
                                      }
                                      
                                    }
                                 ?>
              </td>
            </tr>

          </table>

          <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tr colspan="2">
              <td class="cx_title1" colspan="2">3. 生产信息</td>
            </tr>
            <tr>
              <td class="cx_title">生产基地位置：</td>
              <td class="cx_content">
                <?php echo isset($data->
                TagProduct->product_place) ? $data->TagProduct->product_place : ''?>
              </td>
            </tr>

            <tr>
              <td class="cx_title">土地肥力：</td>
              <td class="cx_content">
                <?php echo isset($data->TagProduct->manure) ? $data->TagProduct->manure : ''?></td>
            </tr>
            <tr>
              <td class="cx_title">土地污染：</td>
              <td class="cx_content">
                <?php echo isset($data->TagProduct->pollute) ? $data->TagProduct->pollute : ''?></td>
            </tr>
            <tr>
              <td class="cx_title" valign="top">品种名：</td>
              <td >
                <?php echo isset($data->TagProduct->breed) ? $data->TagProduct->breed : ''?></td>
            </tr>
            <tr>
              <td class="cx_title">种子质量指标：</td>
              <td class="cx_content">
                <?php echo isset($data->
                TagProduct->seed_quality) ? $data->TagProduct->seed_quality : ''?>
              </td>
            </tr>
            <!-- <tr>
              <td class="cx_title">肥料种类：</td>
              <td class="cx_content">
                <?php echo isset($data->
                TagProduct->manure_type) ? $data->TagProduct->manure_type : ''?>
              </td>
            </tr>
            <tr >
              <td class="cx_title">农药种类：</td>
              <td class="cx_content">
                <?php echo isset($data->
                TagProduct->pesticides_type) ? $data->TagProduct->pesticides_type : ''?>
              </td>
            </tr>
            <tr >
              <td class="cx_title">肥料用量：</td>
              <td class="cx_content">
                <?php echo isset($data->
                TagProduct->manure_amount) ? $data->TagProduct->manure_amount : ''?>
              </td>
            </tr>
            <tr >
              <td class="cx_title">农药用量：</td>
              <td class="cx_content">
                <?php echo isset($data->
                TagProduct->pesticides_amount) ? $data->TagProduct->pesticides_amount : ''?>
              </td>
            </tr> -->
            <tr>
              <td class="cx_title">加工方式：</td>
              <td class="cx_content">
                <?php echo isset($data->
                TagProduct->process_type) ? $data->TagProduct->process_type : ''?>
              </td>
            </tr>
            <tr>
              <td class="cx_title" valign="top">产地照片：</td>
              <td >
                <div class="cx_content1" >
                  {% for key,val in imageProduct %}
                  <img src="{{val['path']}}" alt="" width='201px' height='201px'>{% endfor %}</div>
              </td>
            </tr>
          </table>


          <!-- 农药信息 -->
          
            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
              {% for key, pesticide in TagPesticide %}
                  <tr colspan="2">
                    <td class="cx_title1" colspan="2">农药信息- {{ key  + 1 }}</td>
                  </tr>
                    <tr>
                      <td class='cx_title'>使用时期：</td>
                      <td class='cx_content'>
                        {{ pesticide['use_period'] ? pesticide['use_period'] : ''}}
                      </td>
                    </tr>

                    <tr>
                      <td class='cx_title'>名称：</td>
                      <td class='cx_content'>
                        {{ pesticide['pesticide_name'] ? pesticide['pesticide_name'] : ''}}
                        
                      </td>
                    </tr>

                    <tr>
                      <td class='cx_title'>用量(千克/亩)：</td>
                      <td class='cx_content'>
                        {{ pesticide['pesticide_amount'] ? pesticide['pesticide_amount'] : ''}}
                        
                      </td>
                    </tr>
                    <tr>
                      <td class='cx_title'>品牌：</td>
                      <td class='cx_content'>
                        {{ pesticide['pesticide_brand'] ? pesticide['pesticide_brand'] : ''}}
                        
                      </td>
                    </tr>
                    <tr>
                      <td class='cx_title'>供应商：</td>
                      <td class='cx_content'>
                        {{ pesticide['pesticide_suppliers'] ? pesticide['pesticide_suppliers'] : ''}}
                        
                      </td>
                    </tr>  
            {% endfor %}
            </table>

          <!-- 肥料信息 -->
          <table width="100%" border="0" cellspacing="0" cellpadding="0" >
              {% for key, Manure in TagManureList %}
                  <tr colspan="2">
                    <td class="cx_title1" colspan="2">肥料信息</td>
                  </tr>
                    <tr>
                      <td class='cx_title'>使用时期：</td>
                      <td class='cx_content'>
                        {{ Manure['use_period'] ? Manure['use_period'] : ''}}
                      </td>
                    </tr>

                    <tr>
                      <td class='cx_title'>名称：</td>
                      <td class='cx_content'>
                        {{ Manure['manure_name'] ? Manure['manure_name'] : ''}}
                        
                      </td>
                    </tr>
                    <tr>
                      <td class='cx_title'>种类：</td>
                      <td class='cx_content'>
                        {{ Manure['manure_type']  ? Manure['manure_type'] ==1 ? '有机肥' :'化肥': ''}}
                        
                      </td>
                    </tr>

                    <tr>
                      <td class='cx_title'>用量(千克/亩)：</td>
                      <td class='cx_content'>
                        {{ Manure['manure_amount'] ? Manure['manure_amount'] : ''}}
                        
                      </td>
                    </tr>
                    <tr>
                      <td class='cx_title'>品牌：</td>
                      <td class='cx_content'>
                        {{ Manure['manure_brand'] ? Manure['manure_brand'] : ''}}
                        
                      </td>
                    </tr>
                    <tr>
                      <td class='cx_title'>供应商：</td>
                      <td class='cx_content'>
                        {{ Manure['manure_suppliers'] ? Manure['manure_suppliers'] : ''}}
                        
                      </td>
                    </tr>  
            {% endfor %}

            </table>


          {% for key,val in plant %}
          <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tr colspan="2">
              <td class="cx_title1" colspan="2">{% if key == 0 %} 4. 生产信息{% endif %}</td>
            </tr>
            <tr>
              <td class="cx_title">作业类型：</td>
              <td class="cx_content">
                <?php echo isset($_operate_type[$val['operate_type']]) ? $_operate_type[$val['operate_type']] : ''?>
              </td>
            </tr>

            <tr>
              <td class="cx_title">时间：</td>
              <td class="cx_content">
                <?php 
                echo isset($val['begin_date']) ? $val['begin_date'] : '';
                echo "<br />";
                echo isset($val['end_date']) ? $val['end_date'] : '';
                ?></td>
            </tr>
            <tr>
              <td class="cx_title">天气状态：</td>
              <td class="cx_content">
                <?php echo isset($val['weather']) ? $val['weather'] : '';
                ?></td>
            </tr>
            <tr>
              <td class="cx_title" valign="top">作业内容：</td>
              <td >
                <?php echo isset($val['comment']) ? $val['comment'] : '' ?></td>
            </tr>
      

            <tr>
              <td class="cx_title" valign="top">作业照片：</td>
              <td >
                <div class="cx_content1" >
                  {% for val in val['imgList'] %}
                  <img src="{{val['path']}}" alt="" width='201px' height='201px'>{% endfor %}</div>
              </td>
            </tr>
          </table>
          {% endfor %}

        </div>
        <div align="center" style="margin-top:20px;">
          
        </div>
      </div>
      <!-- main_right 结束  --> </div>

    <script type="text/javascript" src="{{ constant('STATIC_URL') }}js/jquery/ld-select.js"></script>
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
    <script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=2159" ></script>
    <link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
    <link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}js/validator/jquery.validator.css" />
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}js/validator/jquery.validator.js"></script>
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}js/validator/local/zh_CN.js"></script>
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}js/lhgdialog/lhgdialog.min.js?skin=igreen"></script>
    <link href="http://js.static.ync365.com//jquery-ui/jquery-ui.css" rel="stylesheet">

    <script src="http://js.static.ync365.com//jquery-ui/jquery-ui.js"></script>




  <!-- 审核通过弹框开始  -->
  <div id="fade" class="black_overlay"></div>
  
  <form action="/manage/tag/tagaudit"   method="post" id='via' >
  <div id="MyDiv" class="white_content2">
    <div class="gb">
      确定审核通过
      <a href="#" onclick="CloseDiv('MyDiv','fade')"></a>
    </div>
    <div class="shenh">
      <ul>
        <li>
          <lable></lable>
          <div>
            
           通过以后供应商将有权使用带有丰收汇LOGO的标签

            <br>
            <span class="msg-box" for="inputID" id='inputIDdns' style='display:none;'></span>
            
          </div>
        </li>
        <li>
          <lable>&nbsp;</lable>
          <div>
            <input name="" type="submit" value="确定" class="btn3"/>
            <input type="hidden" name='id' value='{{ data.tag_id}}'> <!-- # 隐藏ID -->
            <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv('MyDiv','fade')" />
          </div>
        </li>
      </ul>
    </div>
    </form>

  </div>
  <!-- 审核通过弹框结束  -->
  <!-- 审核未通过弹框开始  -->
  <div id="fade1" class="black_overlay"></div>
  <form action="/manage/tag/tagunauditun"   method="post"  >
  <div id="MyDiv1" class="white_content2">
    <div class="gb">
      确定审核未通过
      <a href="#" onclick="CloseDiv1('MyDiv1','fade1')"></a>
    </div>
    <div class="shenh">
      <ul>
        <li>
          <lable>请输入拒绝理由：</lable>
          <div>
            <input name="reject" type="text"  value=''  data-rule="拒绝理由:required;" data-target="#rejectinputID"   />
            <span class="msg-box" for="inputID" id='rejectinputID' style=''></span>
          </div>
        </li>
        <li>
          <lable>&nbsp;</lable>
          <div>
            <input type="submit" value="确定" class="btn3"/>
            <input type="hidden" name='id' value='{{ data.tag_id}}'> <!-- # 隐藏ID -->
            <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv1('MyDiv1','fade1')"/>
          </div>
        </li>
      </ul>
    </div>
    </form>
  </div>

    <script type="text/javascript">
//弹出隐藏层
function ShowDiv(show_div,bg_div){
document.getElementById(show_div).style.display='block';
document.getElementById(bg_div).style.display='block' ;
var bgdiv = document.getElementById(bg_div);
bgdiv.style.width = document.body.scrollWidth;
$("#"+bg_div).height($(document).height());
};
//关闭弹出层
function CloseDiv(show_div,bg_div)
{
document.getElementById(show_div).style.display='none';
document.getElementById(bg_div).style.display='none';
};
//弹出隐藏层
function ShowDiv1(show_div,bg_div){
document.getElementById(show_div).style.display='block';
document.getElementById(bg_div).style.display='block' ;
var bgdiv = document.getElementById(bg_div);
bgdiv.style.width = document.body.scrollWidth;
$("#"+bg_div).height($(document).height());
};
//关闭弹出层
function CloseDiv1(show_div,bg_div)
{
document.getElementById(show_div).style.display='none';
document.getElementById(bg_div).style.display='none';
};
</script>


    <style>
.upload_btn {width: 121px;height: 31px;line-height: 42px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f;}
</style>
    <div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
  </html>
</body>
</html>
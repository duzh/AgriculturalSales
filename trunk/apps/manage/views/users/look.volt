

<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
  <div class="main_right">
    <div class="bt2">编辑会员信息</div>
    <div class="cx">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">

                <tr>
                    <td class="cx_title">用户ID：</td>
                    <td class="cx_content">
                        {{ user.id }}
                     </td>
                </tr>

                <tr>
                    <td class="cx_title">真实姓名：</td>
                    <td class="cx_content">
                        {{name}}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">性别：</td>
                    <td class="cx_content">
                    {% if sex==1 %}男{% else %}女{% endif %}    
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">主营业务：</td>
                    <td class="cx_content">                     
                      {% for key,val in cate %}
                      {% if val["parent_id"] == 0 %}
                     {% if main_category==val['id'] %} {{val["title"]}}{% endif %}
                      {% endif %}
                      {% endfor %}
                     </td>
                </tr>

                <tr>
                    <td class="cx_title">手机号：</td>
                    <td class="cx_content">
                        {{ user.username }}
                        <!-- {{ text_field("mobile", "size" : 30,'class':'txt') }} -->
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">头像：</td>
                    <td class="cx_content">
                    <?php if(isset($picture_path)){ $path=$picture_path;}else{$path='';} ?>
                    <img src="{% if path %}{{ constant('IMG_URL') }}{{path}}{% else %}{{ constant('STATIC_URL') }}mdg/images/trust-farm-img.jpg
                    {% endif %}">                         
                     </td>
                </tr>
                <tr>
                     <td class="cx_title">身份类型：</td>
                    <td class="cx_content">
                       {{ radio_field("usertype", "value" : "1","onclick" : "selects('卖')") }}种植户/养殖户 (请种植户，养殖户，以及农业合作社经营者选此项)</br>
                       {{ radio_field("usertype", "value" : "2","onclick" : "selects('买')") }}采购商 (请采购商选此项)

                    </td>
                </tr>
                <tr>
                    <td class="cx_title"  valign="top">所在地区：</td>
                    <td > 
                       <div class="cx_content1"> 
                            {{area_name}}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title" valign="top" id="myusers" >我关注的采购：</td>
                    <td class="cx_content">
                        {{category_pu_name}}
                    </td>
                </tr>

                <tr>
                    <td class="cx_title" valign="top" id="myusers" >我关注的供应：</td>
                    <td class="cx_content">
                        {{category_sell_name}}
                    </td>
                </tr>
                <tr>
                  <td class="cx_title">发布平台：</td>
                    <td class="cx_content">
                      {% for key,val in plat %}
                        {% if key == 0 or key == 3 %}
                          <input type="radio" name="plat" value="{{ key }}" {% if key == publish_set %} checked {% endif %}/>{{ val }} 
                        {% endif %}
                      {% endfor %}
                  </td>
                </tr>
                 
         </table>
         
    </div> 
  </div>

  


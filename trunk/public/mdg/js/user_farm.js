       var sid = '';
var upload_total = {};
$(function() {
    /* 可信农场 */
    /*  银行卡 */
    bankImg($('#user_bankcard_picture'), 29, $('#user_show_bankcard_picture'), 'html', $('#user_bankcard_picture_hide'));
    /* 用户手持身份证 */
    bankImg($('#user_person_picture'), 30, $('#user_show_person_picture'), 'html', $('#user_person_picture_hide'));
    /* 用户身份证 */
    bankImg($('#user_idcard_picture'), 31, $('#user_show_idcard_picture'), 'html', $('#user_idcard_picture_hide'));
    /*  用户农场信息 */
    bankImg($('#user_picture_path'), 32, $('#user_show_picture_path'), 'append', $('#user_picture_path_hide'));
    /*  用户农场信息 */
    bankImg($('#user_picture_path_contact'), 42, $('#user_show_picture_path_contact'), 'append', $('#user_picture_path_contact_hide'));
    /* 身份证背面照 */
    bankImg($('#user_idcard_picture_back'), 34, $('#user_show_idcard_picture_back'), 'html', $('#user_idcard_picture_back_hide'));

    /* 农产信息 */
    /* 银行卡信息 */
    bankImg($('#ent_bankcard_picture'), 29, $('#ent_show_bankcard_picture'), 'html', $('#ent_bankcard_picture_hide'));
    /* 农产 个人营业 ent_identity_picture_lic */
    bankImg($('#ent_identity_picture_lic'), 33, $('#ent_show_identity_picture_lic'), 'html', $('#ent_identity_picture_lic_hide'));
    /* 农产 个人身份证 */
    bankImg($('#ent_idcard_picture'), 31, $('#ent_show_idcard_picture'), 'html', $('#ent_idcard_picture_hide'));
    /* 农产农场信息  */
    bankImg($('#ent_picture_path'), 32, $('#ent_show_picture_path'), 'append', $('#ent_picture_path_hide'));
    /* 用户农场合同  */
    bankImg($('#ent_picture_path_contact'), 42, $('#ent_show_picture_path_contact'), 'append', $('#ent_picture_path_contact_hide'));
    /*  身份证背面  */
    bankImg($('#ent_idcard_picture_back'), 34, $('#ent_show_idcard_picture_back'), 'html', $('#ent_idcard_picture_back_hide'));

    /*  组织机构代码证*/
    bankImg($('#ent_identity_picture_org'), 11, $('#ent_show_identity_picture_org'), 'html', $('#ent_identity_picture_org_hide'));
    /* 税务登记证  */
    bankImg($('#ent_identity_picture_tax'), 10, $('#ent_show_identity_picture_tax'), 'html', $('#ent_identity_picture_tax_hide'));


    /*  组织机构代码 */
    bankImg($('#ent_organization_code'), 11, $('#ent_show_organization_code'), 'html', $('#ent_organization_code_hide'));
    /*  税务登记证（选填） */
    bankImg($('#ent_tax_registration'), 10, $('#ent_show_tax_registration'), 'html', $('#ent_tax_registration_hide'));


    /* 可信农场 end*/
    selectBycate(1, 0);


})



/*  获取所有选中分类结果 */
function getAllCateResult(type) {
    /* 获取result */
    var txt = '';
    $('#result-box_' + type).find('em').each(function() {
        txt += $(this).attr('data-id') + ',';
    });

    $('#category_name_text_' + type).val(txt);
    if (txt) {
        $('#category_name_text_' + type).next('i').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
    } else {
        $('#category_name_text_' + type).next('i').html('<span class="msg-wrap n-error" role="alert"><span class="n-icon"></span><span class="n-msg">不能为空</span></span>');
    }


}

/* 上传图片实例化 */
function bankImg(id, type, show_img, limit, tip_id) {
    key = id.attr('id');
    if (typeof upload_total[key] == 'undefined') upload_total[key] = 0;
    //银行正面照
    id.uploadify({
        'width': '89',
        'height': '25',
        'swf': '/uploadify/uploadify.swf',
        'uploader': '/upload/tmpfile',
        'fileSizeLimit': '2MB',
        'fileTypeExts': '*.jpg;*.png;*.jpeg;*.bmp;*.png',
        'formData': {
            'sid': sid,
            'type': type,
            'member': 1,
            'key' : key
        },
        'buttonClass': 'upload_btn',
        'buttonText': '上传图片',
        'multi': false,
        onDialogOpen: function() {
            $('.gy_step').eq(1).addClass("active").siblings().removeClass("active");
        },

        onUploadSuccess: function(file, data, response) {
           
            data = $.parseJSON(data);
            alert(data.msg);
            if (data.status) {
                key = id.attr('id');
                if(limit=='html'){
                    upload_total[key]=1;
                }else{
                     upload_total[key]++;
                }
            
                // show_img.attr("src":data.path);
                if (limit == 'append') {
                    show_img.append(data.html);
                } else if (limit == 'html') {
                    show_img.html(data.html);
                }
               
                  tip_id.val(1);
                  if (type == 32 || type == 42) {
                    tip_id.val(data.total);
                  }
                

                tip_id.next('i').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
                
                if ( (upload_total[key] == 5 && key == 'user_picture_path_contact') || (upload_total[key] == 5 && key == 'ent_picture_path_contact')  
                    || (upload_total[key] == 5 && key == 'user_picture_path') ||  (upload_total[key] == 5 && key == 'ent_picture_path') 
                    ) {
                     id.uploadify('disable', true); 
                }
            }
        }
    });
}



// 删除图片
function closeImg(obj, id, mkey) {
    

    $.getJSON('/upload/deltmpfile', {
        id: id
    }, function(data) {
        alert(data.msg);
        if (data.state) {
            $('#dl_' + id).slideUp();
            
            upload_total[''+mkey+'']--;
            $('#'+mkey).uploadify('disable', false);
           
            if(upload_total[''+mkey+''] == 0){
                $('#'+mkey+'_hide').val('');
            }
            // if (    (upload_total[''+mkey+''] == 0 && mkey == 'user_picture_path_contact') || 
            //         (upload_total[''+mkey+''] == 0 && mkey == 'ent_picture_path_contact') ||
            //         (upload_total[''+mkey+''] == 0 && mkey == 'user_picture_path') ||
            //         (upload_total[''+mkey+''] == 0 && mkey == 'ent_picture_path') 
            //     ) {
            //                         // alert(mkey + " 00000") ; 
            //         $('#'+mkey+'_hide').val('');
            //     // $('#'+mkey+'_hide_tip').html('<span class="msg-wrap n-error" role="alert"><span class="n-icon"></span><span class="n-msg">图片不能为空</span></span>');
            // }
        

            
        }
    });
}
//添加
var add_arr = [];
var add_cid = [];

function selectBycate(cid, type) {

    $.get('/ajax/getcate', {
        'parent_id': cid
    }, function(json) {
        var str = '';
        data = eval(json);
        for (var o in data) {
            str += '<a href="javascript:;" data-id="' + data[o].region_id + '">' + data[o].region_name + '</a>';
        }

        $(".categrey-option .erji-box a").unbind("click");
        $(".categrey-option .btn-box .btn1").unbind("click");
        $(".categrey-option .btn-box .btn2").unbind("click");
        $(".categrey-option .result-box em").unbind("click");
        $('#show_cate_chid_' + type).html(str);

        $('.categrey-option .erji-box a').click(function() {
            $(this).parents('.categrey-option').find('.result-box em').removeClass('active');
            $(this).parent().find('a').removeClass('active');
            $(this).addClass('active');
        });

        $('.categrey-option .result-box em').click(function() {
            $(this).parents('.categrey-option').find('.erji-box a').removeClass('active');
            $(this).parent().find('em').removeClass('active');
            $(this).addClass('active');
        });

        //添加
        var add_arr = [];
        var add_cid = [];
        var flag = 0;

        $('.categrey-option .btn-box .btn1').click(function() {
            $(this).parents('.categrey-option').find('.erji-box a').each(function() {
                if ($(this).hasClass('active')) {
                    var txt = $(this).text();
                    var cid = $(this).attr('data-id');
                    add_arr.push(txt);
                    add_cid.push(cid);

                } else {
                    return;
                };
            });


            if (add_arr.length > 0) {
                /* 检测是否有重复数据 */
                flag = 0;
                $('#result-box_' + type).find('em').each(function(index, el) {
                    if ($.trim($(el).text()) == $.trim(add_arr[0])) {
                        flag = 1;
                    }
                });

                if (flag) {
                    alert('分类重复');
                    add_arr = [];
                    add_cid = [];
                    return false;
                }

                var str = "<em data-id='" + add_cid[0] + "'>" + add_arr[0] + "</em>";
                $(str).appendTo('#result-box_' + type);
                getAllCateResult(type);
                $(this).parents('.categrey-option').find('.result-box em').click(function() {
                    $(this).parents('.categrey-option').find('.erji-box a').removeClass('active');

                    $(this).parent().find('em').removeClass('active');
                    $(this).addClass('active');
                });

                add_arr = [];
                add_cid = [];
                flag = 0;

            } else {
                alert("请选择分类！");
            }
        });


        //删除
        var delete_arr = [];
        $('.categrey-option .btn-box .btn2').click(function() {
            $(this).parents('.categrey-option').find('.result-box em').each(function() {
                if ($(this).hasClass('active')) {
                    var txt = $(this).text();
                    delete_arr.push(txt);
                    $(this).remove();
                } else {
                    return;
                };
            });

            if (delete_arr.length > 0) {
                getAllCateResult(type);
                return;
            } else {
                alert("请选择分类！");
            };
        });

    });
}



// 地区联动
$(".selectAreas").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,

    style: {
        "width": 250
    }
});
// 地区联动
$(".class_bank_address").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});
/* 公司地址 */
$(".ent_selectAreas").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,

    style: {
        "width": 250
    }
});
/* 申请负责区域 */
$(".user_area_selectAreas").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,

    style: {
        "width": 250
    }
});
/* 企业负责区域 */
$(".ent_area_selectAreas").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,

    style: {
        "width": 250
    }
});


/**/
$(".ent_class_bank_address").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});
/**/
$(".ent_farm_selectAreas").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});

/**/
$(".area_select").ld({
    ajaxOptions: {
        "url": "/ajax/getareasfull"
    },
    defaultParentId: 0,
    style: {
        "width": 250
    }
});



var jsFileName = "user_farm.js";
var rName = new RegExp(jsFileName + "(\\?(.*))?$")
var jss = document.getElementsByTagName('script');
for (var i = 0; i < jss.length; i++) {
    var j = jss[i];
    if (j.src && j.src.match(rName)) {
        var oo = j.src.match(rName)[2];
        if (oo && (t = oo.match(/([^&=]+)=([^=&]+)/g))) {
            for (var l = 0; l < t.length; l++) {
                r = t[l];
                var tt = r.match(/([^&=]+)=([^=&]+)/);
                if (tt && tt[1] == 'sid') {
                    sid = tt[2];
                }
            }
        }
    }
}
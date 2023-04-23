{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">

    <div class="main_right">
        <div class="bt2">查看文章信息</div>
        <div class="cx">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">文章标题：</td>
                    <td class="cx_content">{{ article.title }}</td>
                </tr>
                <tr>
                    <td class="cx_title">文章分类：</td>
                    <td class="cx_content">
                            <?php echo Mdg\Models\Article::returncategory($article->cid)?>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">是否显示：</td>
                    <td class="cx_content">
                      
                       {{ isshow[article.is_show] }}
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">关键字：</td>
                    <td class="cx_content">{{ article.keywords }}</td>
                </tr>
                <tr>
                    <td class="cx_title">标签：</td>
                    <td class="cx_content">{{ article.tags }}</td>
                </tr>
                <tr>
                    <td class="cx_title"  valign="top">网页描述：</td>
                    <td >
                        <div class="cx_content1">{{ article.description }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title" valign="top">文章内容：</td>
                    <td class="cx_content">
                        <div class="cx_content1">
                            {{article.content}}
                        </div>
                    </td>
                </tr>
            </table>

        </div>
       
    </div>
    <!-- main_right 结束  -->
</form>
</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>

<!-- 头部 -->
<style>
.content a{color:#666;}
</style>
{{ partial('layouts/page_header') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">

		<div class="bread-crumbs">
			<a href="/">首页</a>&nbsp;>&nbsp;{% if (first) %}<a href="/article/?p={{ first.id }}">帮助中心</a>&nbsp;>&nbsp;{% endif %}{% if (catfirst) %}<a href="/article/index?p={{ catfirst.id }}">
        <?php echo Mdg\Models\Articlecategory::getcategory($article->cid); ?></a>&nbsp;>&nbsp;{% endif %}<?php echo $article->title; ?> 
		</div>
		<div class="helpCenter-left f-fl">
			<div class="title">帮助中心</div>
			<?php foreach ($category as $key => $value) {  ?>
			<!-- list -->
			<div class="m-title"><?php echo $value["catname"] ?></div>
			<div class="list">
			<?php foreach ( Mdg\Models\Articlecategory::getcate($value["id"]) as $k=> $v) { ?>
				<a <?php if($p==$v['id']){ echo "class='active'"; } ?> href="/article/index?p=<?php echo $v['id'] ?>"><?php echo $v["title"]?></a>
			 <?php } ?>
			</div>
			<?php } ?>
		</div>
		
		<!-- 右侧内容 -->
		<div class="helpCenter-con f-fr">
			<div class="title">
				<span><?php echo $article->title; ?></span>
			</div>
			<div class="content" style="font-size:16px; color:#666;line-height:26px; margin-top:30px;">
					<?php echo $article->content; ?>
			</div>
		</div>
	</div>
</div>





<!-- 底部 -->
{{ partial('layouts/footer') }}


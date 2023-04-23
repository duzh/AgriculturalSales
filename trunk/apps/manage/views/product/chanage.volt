
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="tk5">
    什么是转移商品分类?
    <br/>
    在添加商品或者在商品管理中,如果需要对商品的分类进行变更,那么你可以通过此功能,正确管理你的商品分类。
</div>
<div class="tk4">
    从此分类：
   <select name="parent_id">
		<?php if(!empty($cat_list)){ ?>
			<?php foreach ($cat_list as $cat) { ?>
				<option value="<?php echo $cat['id']; ?>" <?php if($id==$cat['id']){ echo "selected";} ?> ><?php echo $cat['title']; ?></option>
				<?php if(!empty($cat['child'])) { ?>
					<?php foreach ($cat['child'] as $child) { ?>
					<option value="<?php echo $child['id']; ?>" <?php if($id==$child['id']){ echo "selected";} ?> >&nbsp&nbsp&nbsp<?php echo $child['title']; ?></option>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	</select>
    转移到：
    <form method="post" action="/manage/category/chanagepro/" id="mysub" style="display:inline;">
		<input type="hidden" name="catid" value="<?php echo $id;?>" >
		<select name="dst_id">
			<?php foreach ($cat_list as $cat) { ?>
			<option value="<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></option>
				<?php if(!empty($cat['child'])) { ?>
					<?php foreach ($cat['child'] as $child) { ?>
					<option value="<?php echo $child['id']; ?>">&nbsp&nbsp&nbsp<?php echo $child['title']; ?></option>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</select>
		<input type="submit" value="开始转移" class="sub">
	</form>
</div>
       
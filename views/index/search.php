<h1>Search</h1>
<div class="main_page">
	<div id="search_block">
			<form method="post" action="<?php echo URL;?>index/search" class="search_form">
				<input type="text" class="input_box" name="title" placeholder="Type item to search">
				<input type="submit" value="Search" class="submit"/>
			</form>
	</div>
	<?php if(!empty($this->results)){?>
		<h5>Search Results for <?php echo $this->search_title;?></h5>
		<ul>
			<?php foreach($this->results as $result){?>
				<li>
					<a href ="<?php echo $result['href'];?>" target="_blank"><?php echo $result['title'];?></a>
					<span><?php echo $result['price'];?></span>
				</li>
			<?}?>
		</ul>
	<?}elseif(!empty($this->search_title)){?>
		<h3><?php echo $this->search_title;?> did not return any results</h3>
	<?}?>
	<div class="clear_fix"></div>
</div>
<script type="text/javascript">
function delete_post(id){
   $.ajax({
       url: '<?php echo URL;?>post/delete/'+id+'',
       type: 'post',
       success:function (data, textStatus) {$("#post_"+id).html(data);}
    
});
}
</script>
	

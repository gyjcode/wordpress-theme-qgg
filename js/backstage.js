// 视频列表后台增加分集
jQuery(document).ready(function($){
	
	$('.meta-list-field').on('click','a.add-item',function(){
		
		$(".meta-list-item .delete-item").css("visibility","hidden")
		
		if(event){ event.preventDefault(); }
		meta_lists = $(this).closest('.meta-lists');  // 向上查找第一个类名为 meta-lists 的祖先元素
		data_name = $(this).attr('data-name');
		html_format = $('#' + data_name).html();
		count = 0;
		count = meta_lists.find('.meta-list-item').length + 1;
		$('#video-update-num-id').val(count);
		html_temp = html_format.replace(/({{i}})/g,count);
		$(this).before(html_temp);
		
	});
		
	$('.meta-list-field').on('click','a.delete-item',function(){
		
		item_num = $(".meta-list-item").length
		
		event.preventDefault();
		meta_lists = $(this).closest('.meta-lists');
		count = 0;
		count = meta_lists.find('.meta-list-item').length - 1;
		$('#video-update-num-id').val(count);
		$(this).closest('.meta-list-item').remove();
		if($(".meta-list-item").length >1){
			$(".meta-list-item .delete-item:last").css("visibility","visible");
		}
	});	
	
});


/**
 * AJAX 评论
 * wordpress\wp-includes\js\comment-reply.js
 */

jsdefine('comment', function (){

return {
    init: function (){
        
        (function($){
            // 评论列表内链接新窗口打开
            $('#comments-list .url').attr('target', '_blank')
            // 常用 DOM 元素
            var domBody             = (window.opera) ? ( document.compatMode == "CSS1Compat" ? $('html') : $('body') ) : $('html,body');         // Opera 浏览器兼容处理
            var domComtAuthorInfo   = $('#comment-author-info');
            var domChangeAuthorInfo = $('.comments-box .change-author-info');
            var domTipLoading       = $('.comments-box .tips .loading');
            var domTipError         = $('.comments-box .tips .error');
            var domComtSubmit       = $('#comment-submit');
            var domCancelComtReply  = $('#cancel-comment-reply-link');
            var domComtTextarea     = $('.comments-box textarea');
            
            // 更改评论用户信息
            domChangeAuthorInfo.on('click', function(){
                $(this).hide()
                domComtAuthorInfo.slideDown(300)
            })
            
            // 评论状态提示信息
            domTipLoading.hide();
            domTipError.hide();

            // 新评论计数与存储
            var newComtNum  = 0, newComtArr  = [];

            // 提交评论
            domComtSubmit.attr('disabled', false);    // 确保提交按钮可用
            $('#comment-form').submit(function() {
                // 显示评论提交中···
                domTipLoading.slideDown(300);
                // 禁用提交按钮
                domComtSubmit.attr('disabled', true).fadeTo('slow', 0.5);

                // AJAX 提交评论
                $.ajax({
                    url     : GSM.ajax_url,
                    data    : $(this).serialize()+ "&action=comment",
                    type    : $(this).attr('method'),
                    error   : function (result) {
                        // console.log(result)
                        // 隐藏提交中，显示错误信息
                        domTipLoading.slideUp(500);
                        domTipError.slideDown(500).html( '<span>' + result.responseText + '</span>' );
                        // 延时启用提交按钮并隐藏错误信息
                        setTimeout(function () {
                            domComtSubmit.attr('disabled', false).fadeTo('slow', 1);
                            domTipError.slideUp(500)
                        }, 3000)
                    },
                    success : function (result) {
                        // console.log(result)    // 返回结果见：func_comment.php
                        // 显示评论提交中···
                        domTipLoading.slideUp(300); 
                        newComtArr.push($('#comment').val());               // 评论信息添加到数组中
                        // 清空评论框
                        domComtTextarea.each(function() { this.value = ''});
                        
                        var domComtTitleCount  = $('#comments-title .count'),
                            domTempFormDiv     = $('#wp-temp-form-div'),    // 占位，评论成功后评论框返回到这个占位的 DIV 处
                            domRespondFormDiv  = $('#comments-box'),
                            domRespondForm     = $('#comment-form'),
                            domComtList        = $('#comments-list'),
                            domInputComtParent = $('#comment_parent');
                        
                        // 评论计数 +1 
                        if ( domComtTitleCount.length ) {
                            comtCount = parseInt(domComtTitleCount.text().match(/\d+/));
                            newText = domComtTitleCount.text().replace(comtCount, comtCount + 1)
                            domComtTitleCount.text(newText)
                        }

                        // 用户修改按钮显示 # 非登录用户
                        domChangeAuthorInfo.show()
                        domComtAuthorInfo.slideUp()
                        // 非登录用户评论时将名字填上
                        if( !domRespondForm.children('.author.notlogged').length ){
                            var authorHtml = '<p class="author notlogged">'+$('#comment-info-author').val()+'</p>'
                            domRespondForm.children('img').after(authorHtml)    
                        }
                        
                        // 新评论累加
                        newComtNum++;
                        newComtId = 'new-comment-'+ newComtNum;
                        // 生成新评论盒子
                        if (domInputComtParent.val() == '0') {    // 父评论
                            newComtHtml = '<ol class="comment-new" id="'+newComtId+'"></ol>';
                        } else {    //子评论
                            newComtHtml = '<ul class="children" id="'+newComtId+'"></ul>';
                        }
                        // 将生成的评论盒子插入到指定位置
                        if (domInputComtParent.val() == '0') {
                            if ( domComtList.children('ol').length){
                                domComtList.children('ol').before(newComtHtml);
                            } else {
                                domComtList.html(newComtHtml);
                            }
                        } else {   // 子评论插入到输入框后
                            domRespondFormDiv.parent().after(newComtHtml);
                        }
                        // 将请求的结果嵌入新评论盒子
                        $('#'+newComtId).append(result);
                        $('#'+newComtId).fadeIn(1000);

                        // 延时提交15秒，启用提交按钮
                        wattingToSubmit();

                        // 评论回复完成，返回主评论
                        // 隐藏取消按钮
                        domCancelComtReply.css("display", "none");
                        domCancelComtReply.onclick = null;
                        // 回到主评论
                        domInputComtParent.val('0');
                        console.log(domTempFormDiv)
                        if (domTempFormDiv && domRespondFormDiv) {
                            domRespondFormDiv.insertBefore(domTempFormDiv.parent());
                            domTempFormDiv.remove()
                        }
                    }
                });
                return false
            });
            
            // 延时提交，指定 wait 时间后启用提交按钮，防止误点击连续提交
            var wait = 15,
                submit_val = domComtSubmit.val();
            function wattingToSubmit() {
                if (wait > 0) {
                    domComtSubmit.val(wait);
                    wait--;
                    setTimeout(wattingToSubmit, 1000)
                } else {
                    domComtSubmit.val(submit_val).attr('disabled', false).fadeTo('slow', 1);
                    wait = 15
                }
            }
            
            // 添加评论函数
            addComment = {
                /**
	             * 移动评论框到回复位置
                 * @param {string} addBelowId HTML ID of element the form follows.
                 * @param {string} commentId  被回复评论的数据库 ID
                 * @param {string} respondId  回复表单的 HTML 标签 ID
                 * @param {string} postId     当前文章的数据库 ID
                 * @param {string} replyTo    Form heading content.
                 */
                moveForm: function (addBelowId, commentId, respondId, postId) {

                    var domTempFormDiv     = this.getElementById('wp-temp-form-div'), 
                        domBelowElement    = this.getElementById(addBelowId),
                        domRespondFormDiv  = this.getElementById(respondId),
                        domCancelComtReply = this.getElementById('cancel-comment-reply-link'),
                        domInputComtParent = this.getElementById('comment_parent'),    // 存储父评论 ID 的 input 标签，点击回复时 WordPress 自动将当前评论的 ID 值赋给 comment_parent
                        domInputComtPost   = this.getElementById('comment_post_ID');
                    
                    // 没有占位表单则创建一个（标记原评论框位置）
                    if ( !domTempFormDiv ) {
                        domTempFormDiv = document.createElement('div');
                        domTempFormDiv.id = 'wp-temp-form-div';
                        domTempFormDiv.style.display = 'none';
                        domRespondFormDiv.parentNode.insertBefore(domTempFormDiv, domRespondFormDiv)
                    }
                    // 没有指定滚动到的元素，则返回主评论
                    if ( !domBelowElement ) {
                        domInputComtParent.value = '0',
                        domTempFormDiv.parentNode.insertBefore(domRespondFormDiv, domTempFormDiv),
                        domTempFormDiv.parentNode.removeChild(domTempFormDiv)
                    } else {
                        domBelowElement.parentNode.insertBefore(domRespondFormDiv, domBelowElement.nextSibling)
                    }
                    
                    // 使页面滚动到合适的位置
                    domBody.animate({
                        scrollTop: $('#comments-box').offset().top - 180
                    }, 300);
                    
                    // 赋值 comment_post_ID 与 comment_parent 用于提交数据库
                    if (domInputComtPost && postId) domInputComtPost.value = postId;
                    if (domInputComtParent && commentId) domInputComtParent.value = commentId;

                    // 显示评论【取消】按钮
                    domCancelComtReply.style.display = 'inline-block';
                    // 绑定 # 取消评论事件
                    domCancelComtReply.onclick = ()=>{
                        // input 标签 comment_parent 重置 value，避免提交时回复给具体评论
                        domInputComtParent.value = '0';
                        // 返回主评论
                        if (domTempFormDiv && domRespondFormDiv) {
                            domTempFormDiv.parentNode.insertBefore(domRespondFormDiv, domTempFormDiv);
                            domTempFormDiv.parentNode.removeChild(domTempFormDiv)
                        }
                        // 隐藏评论【取消】按钮
                        domCancelComtReply.style.display = 'none';
                        // 解绑 # 取消评论事件
                        domCancelComtReply.onclick = null;
                        return false
                    };
                    // 评论框获得焦点
                    this.getElementById('comment').focus()

                    return false
                },

                // 工具 # 根据 ID 获取 DOM 元素
                getElementById: function (elementId) {
                    return document.getElementById(elementId)
                }
            };
            
            // 点击回复评论，移动表单至指定位置
            $('.comment-reply-link').on('click', function (){
                // 获取数据
                var belowElement   = $(this).data('belowelement');
                var commentId      = $(this).data('commentid');
                var respondElement = $(this).data('respondelement');
                var postId         = $(this).data('postid');

                if( !$(this).attr('onclick') && belowElement && commentId && respondElement && postId ){
                    return addComment.moveForm( belowElement, commentId, respondElement, postId )
                }
            })

            // 小工具
            // 表情 emojis
            $("#comment-form .emojis").click(function(){ 
                $("#comment-form .emojis-wrapper").toggle(500);
                if( $("#comment-form .emojis i").css("color") === "rgb(153, 153, 153)" ){
                    $("#comment-form .emojis i").attr('style', 'color: #3cb4f0');
                }else{
                    $("#comment-form .emojis i").attr('style', 'color:;');
                }
            });
            
            // 展开收起
            // 关闭未超出行数按钮
            var comtNum               = $("#comments-list .content").length;
            var comtMetaHeight        = $("#comments-list .content .meta").height();
            var comtPaddingTop        = parseFloat( $("#comments-list .content").css("padding-top") );
            var comtPaddingBottom     = parseFloat( $("#comments-list .content").css("padding-bottom") );
            var comtContentLineHeight = parseFloat( $("#comments-list .content").children("p").css("line-height") );
            var domToggleMores        =  $("#comments-list .meta .more");
            // 遍历计算哪些评论显示展开按钮
            for (var i=0; i<comtNum; i++){
                var comtContentHeight     = $("#comments-list .content")[i].offsetHeight;
                var currentHeight =  comtContentHeight- comtPaddingBottom- comtPaddingTop - comtMetaHeight;
                var rowNum = currentHeight/comtContentLineHeight;
                if( rowNum >= 3 ){
                    domToggleMores[i].style.display = "inline-block";
                }else{
                    domToggleMores[i].style.display = "none";
                }
            }
            // 点击展开收起
            domToggleMores.click(function(){
                if( $(this).text() == "展开" ){
                    $(this).parent().parent().children("p").css({ "-webkit-line-clamp" : "999999999", });
                    $(this).text("收起")
                }else{
                    $(this).parent().parent().children("p").css({ "-webkit-line-clamp" : "3",  });
                    $(this).text("展开")
                }
            });
    
        })(jQuery)
        
    },
    
}

});
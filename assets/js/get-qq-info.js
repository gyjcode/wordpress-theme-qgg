/* AJAX 请求QQ信息 */
function get_qq_info(){
    var qq_num = $('#comt-qq').val();
    if(qq_num){
        if( !isNaN(qq_num)){
            $.ajax({
                url      : GSM.uri+"/action/get_qq_info.php",
                type     : "get",
                data     : { qq:qq_num },
                dataType : "json",
                success  : function(data){
                    $email = qq_num+'@qq.com';
                    $name = data[qq_num][6]=="" ? 'QQ游客' : data[qq_num][6];
                    $url = 'http://q1.qlogo.cn/g?b=qq&nk='+qq_num+'&s=100';
                    //$url = data[qq_num][0]=="" ? '' : data[qq_num][0];
                    
                    $("#comt-email").val($email);
                    $("#comt-author").val($name);
                    if($url){
                        $("#comt-title .avatar").attr('src', $url);
                        $("#comt-title .avatar").removeAttr('srcset');
                    }
                },
                error    : function(err){
                    $("#comt-author").val('QQ游客');
                    $("#comt-email").val(qq_num+'@qq.com');
                    $('#comment').focus();
                }
            });
        }else{
            $("#comt-author").val('你输入的好像不是QQ号码');
            $("#comt-email").val('你输入的好像不是QQ号码');
        } 
    }else{
        $("#comt-author").val('请输入您的QQ号');
        $("#comt-email").val('请输入您的QQ号');
    }
};
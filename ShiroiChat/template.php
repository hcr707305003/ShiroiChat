<script src="<?=Typecho_Common::url("ShiroiChat/style/jquery.min.js", Helper::options()->pluginUrl)?>"></script>
<script>
    window.onload = function(){
        //初始化
        changeServer($('input[name="chat_mode"]:checked').val());
        //修改
        $('input[name="chat_mode"]').change(function () {
            changeServer($(this).val());
        });
    }

    function changeServer(chat_mode) {
        if(chat_mode == 'websocket') {
            $('input[name="websocket_ip"]').parent().show();
            $('input[name="websocket_port"]').parent().show();
        } else {
            $('input[name="websocket_ip"]').parent().hide();
            $('input[name="websocket_port"]').parent().hide();
        }
    }
</script>
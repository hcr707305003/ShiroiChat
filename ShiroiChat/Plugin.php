<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * ShiroiChat 基于layim的聊天工具
 * @package ShiroiChat
 * @author shiroi
 * @version 1.0.0
 * @link https://shiroi.top
 */
class ShiroiChat_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('admin/menu.php')->navBar = array('ShiroiChat_Plugin', 'render');
        //加载消息
        Typecho_Plugin::factory('Widget_Archive')->footer = array('ShiroiChat_Plugin', 'footer');
    }

    /**
     * 获取插件配置面板
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        //jquery
        $jquery = new Typecho_Widget_Helper_Form_Element_Radio('jquery',
            array('start'=>_t('是'),'stop'=>_t('否')),'',_t('是否加载jquery'),_t('是否加载jquery设置'));
        $form->addInput($jquery);
        //开始陌生人聊天
        $stranger_status = new Typecho_Widget_Helper_Form_Element_Radio('stranger_status',
            array('start'=>_t('是'),'stop'=>_t('否')),'',_t('是否让陌生人查看'),_t('是否让陌生人查看聊天信息'));
        $form->addInput($stranger_status);
        //聊天模式
        $chat_mode = new Typecho_Widget_Helper_Form_Element_Radio('chat_mode',
            array('ajax'=>_t('ajax轮询'),'websocket'=>_t('websocket')),'',_t('聊天模式'));
        $form->addInput($chat_mode);
        //javascript
        include_once(__DIR__ . '/template.php');
        //websocket ip
        $websocket_ip = new Typecho_Widget_Helper_Form_Element_Text('websocket_ip', NULL, 'localhost', _t('websocket ip地址'), _t('websocket ip地址：<a style="color: red">默认localhost 如果有其他需求请自行设置防火墙</a>'));
        $form->addInput($websocket_ip);
        //websocket port
        $websocket_port = new Typecho_Widget_Helper_Form_Element_Text('websocket_port', NULL, '1234', _t('websocket端口设置'), _t('websocket 端口'));
        $form->addInput($websocket_port);
    }

    /**
     * 加载尾部
     */
    public static function footer()
    {
        //加载资源
        $jquery_js = self::toValue('jquery') == 'start' ? self::getFilePath('js/jquery.min.js'): '';
        $layui_css = self::getFilePath('layui/css/layui.css');
        $layui_js = self::getFilePath('layui/layui.js');
        $setConfig = '<script>
            let http = "http://tp5.com/index.php/index/";
                layui.use("layim", function(layim){
                    //基础配置
                    layim.config({
            
                        //获取主面板列表信息
                        init: {
                            url: http+"index/getList" //接口地址（返回的数据格式见下文）
                            ,type: "get" //默认get，一般可不填
                            ,data: {} //额外参数
                        }
                        //获取群员接口
                        ,members: {
                            url: http+"index/getMembers" //接口地址（返回的数据格式见下文）
                            ,type: "get" //默认get，一般可不填
                            ,data: {} //额外参数
                        },
                        uploadFile: {
                            url: http+"upload/uploadFile"
                        }
                        ,uploadImage: {
                            url: http+"upload/uploadimg"
                        }
                        ,brief: false //是否简约模式（默认false，如果只用到在线客服，且不想显示主面板，可以设置 true）
                        ,title: "我的消息" //主面板最小化后显示的名称
                        ,maxLength: 3000 //最长发送的字符长度，默认3000
                        ,isfriend: true //是否开启好友（默认true，即开启）
                        ,isgroup: true //是否开启群组（默认true，即开启）
                        ,right: "0px" //默认0px，用于设定主面板右偏移量。该参数可避免遮盖你页面右下角已经的bar。
                        ,chatLog: http+"Chatlog/index" //聊天记录地址（如果未填则不显示）
                        ,find: http+"findgroup/index" //查找好友/群的地址（如果未填则不显示）
                        ,copyright: false //是否授权，如果通过官网捐赠获得LayIM，此处可填true
                    });
            
                    //建立WebSocket通讯
                    var socket = new WebSocket("ws://127.0.0.1:7272");
            
                    //连接成功时触发
                    socket.onopen = function(){
                        // 登录
                        // var login_data = "{"type":"init","id":"{$uinfo.id}","username":"{$uinfo.username}","avatar":"{$uinfo.avatar}","sign":"{$uinfo.sign}"}";
//                        socket.send( login_data );
                        //console.log( login_data );
                        console.log("websocket握手成功!");
                    };
            
                    //监听收到的消息
                    socket.onmessage = function(res){
                        //console.log(res.data);
                        var data = eval("("+res.data+")");
                        switch(data["message_type"]){
                            // 服务端ping客户端
                            case "ping":
                                socket.send("{\'type\':\'ping\'}");
                                break;
                            // 登录 更新用户列表
                            case "init":
                                //console.log(data["id"]+"登录成功");
                                //layim.getMessage(res.data); //res.data即你发送消息传递的数据（阅读：监听发送的消息）
                                break;
                            //添加 用户
                            case "addUser":
                                //console.log(data.data);
                                layim.addList(data.data);
                                break;
                            //删除 用户
                            case "delUser":
                                layim.removeList({
                                    type: "friend"
                                    ,id: data.data.id //好友或者群组ID
                                });
                                break;
                            // 添加 分组信息
                            case "addGroup":
                                // console.log(data.data);
                                layim.addList(data.data);
                                break;
                            case "delGroup":
                                layim.removeList({
                                    type: "group"
                                    ,id: data.data.id //好友或者群组ID
                                });
                                break;
                            // 检测聊天数据
                            case "chatMessage":
                                //console.log(data.data);
                                layim.getMessage(data.data);
                                break;
                            // 离线消息推送
                            case "logMessage":
                                setTimeout(function(){layim.getMessage(data.data)}, 1000);
                                break;
                            // 用户退出 更新用户列表
                            case "logout":
                                break;
                            //聊天还有不在线
                            case "ctUserOutline":
                                // console.log("11111");
                                layer.msg("好友不在线", {"time" : 1000});
                                break;
            
                        }
                    };
            
                    //layim建立就绪
                    layim.on("ready", function(res){
            
                        layim.on("sendMessage", function(res){
                            //console.log(res);
                            // 发送消息
                            var mine = JSON.stringify(res.mine);
                            var to = JSON.stringify(res.to);
                            var login_data = "{\'type\':\'chatMessage\',\'data\':{\'mine\':"+mine+", \'to\':"+to+"}}";
                            socket.send( login_data );
            
                        });
                    });
            
                });
        </script>';
        echo "{$jquery_js}{$layui_css}{$layui_js }{$setConfig}";
        //陌生人是否可视 (boolean)
        $stranger_status = self::toValue('stranger_status');
//        echo "<script>console.log('{$stranger_status}');</script>";


        //聊天模式
        $chat_mode = self::toValue('chat_mode');//ajax(每秒定时轮询) websocket(开启一个ws服务)
//        echo "<script>console.log('{$chat_mode}');</script>";

    }

    /**
     * 值返回
     * @param $value
     * @return string
     * @throws Typecho_Exception
     */
    public static function toValue($value)
    {
        return htmlspecialchars(Typecho_Widget::widget('Widget_Options')->plugin('ShiroiChat')->$value);
    }

    /**
     * 组成js css
     * @param $name
     * @return string
     */
    public static function getFilePath($name)
    {
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $path = Typecho_Common::url("ShiroiChat/style/{$name}", Helper::options()->pluginUrl);
        if ($extension == 'css') {
            return '<link rel="stylesheet" href="' . $path . '">';
        } elseif ($extension == 'js') {
            return '<script src="' . $path . '"></script>';
        }
    }

    /**
     * 开启ajax轮询服务
     */
    public static function ajax()
    {

    }

    /**
     * 开启websocket服务
     */
    public static function websocket()
    {

    }

    public static function deactivate(){}

    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
}
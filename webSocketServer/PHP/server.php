<?
//按需加载
spl_autoload_register(function ($class) {
    $filename = __DIR__ . "/{$class}.php";
    if(file_exists($filename)) include($filename);//引入相关文件
});

$config = array(
    'address' => 'localhost',
    'port' => '8000',
    'event' => 'callback',
    'log' => true,
);
$websocket = new \websocket\WebSocketServer($config);
$websocket->run();

//回调函数的函数名
function callback($type, $event)
{
    global $websocket;
    $websocket->write($event['sign'], $event['msg']);
    var_dump($type);//接受的类型
    var_dump($event);//接受的信息
}
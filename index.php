<?php
// 验证微信服务器请求
$token = '123456'; // 填写你的 Token
$signature = $_GET['signature'];
$timestamp = $_GET['timestamp'];
$nonce = $_GET['nonce'];
$echoStr = $_GET['echostr'];

$tmpArr = array($token, $timestamp, $nonce);
sort($tmpArr, SORT_STRING);
$tmpStr = implode($tmpArr);
$tmpStr = sha1($tmpStr);

if ($tmpStr == $signature) {
    if (isset($_GET['echostr'])) {
        // 验证通过，返回验证字符串
        echo $echoStr;
    } else {
        
        // 判断是否为关注事件
        $xmlData = file_get_contents('php://input');
        if (!empty($xmlData)) {
            $xmlObj = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA);
            $msgType = trim($xmlObj->MsgType);

            // 判断消息类型为事件消息且事件为关注事件
            if ($msgType == 'event' && trim($xmlObj->Event) == 'subscribe') {
                // 构造关注回复消息
                $replyContent = '欢迎关注公众号！';

                $replyXml = "<xml>
                                <ToUserName><![CDATA[{$xmlObj->FromUserName}]]></ToUserName>
                                <FromUserName><![CDATA[{$xmlObj->ToUserName}]]></FromUserName>
                                <CreateTime>" . time() . "</CreateTime>
                                <MsgType><![CDATA[text]]></MsgType>
                                <Content><![CDATA[{$replyContent}]]></Content>
                            </xml>";
                // 输出回复消息
                echo $replyXml;
                exit; // 结束程序，避免后续逻辑执行
            } else if ($msgType == 'text') {
                // 接收到文本消息，转发到关键词回复的 PHP 文件
                include 'hf.php';
            } else {
                // 其他非文本消息回复
                $replyContent = '公众号不支持接收非文本消息哦';
                $replyXml = "<xml>
                                <ToUserName><![CDATA[{$xmlObj->FromUserName}]]></ToUserName>
                                <FromUserName><![CDATA[{$xmlObj->ToUserName}]]></FromUserName>
                                <CreateTime>" . time() . "</CreateTime>
                                <MsgType><![CDATA[text]]></MsgType>
                                <Content><![CDATA[{$replyContent}]]></Content>
                            </xml>";
                // 输出回复消息
                echo $replyXml;
                exit; // 结束程序，避免后续逻辑执行
            }
        }
    }
} else {
    // 验证失败
    echo 'Invalid request';
}

?>
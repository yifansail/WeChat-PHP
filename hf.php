<?php
// 指定 CSV 文件路径
$filename = 'file.csv';//如果你的不一样自行修改

// 定义常驻文本函数
function 广告() {
    return '我是常驻文本' . "\n" . '----------------'. "\n";
}

// 读取 CSV 文件
$file = fopen($filename, 'r');
if ($file) {
    // 定义关键词和回复内容的数组
    $keywords = array();

    // 逐行读取关键词和回复内容
    while (($row = fgetcsv($file)) !== false) {
        $keyword = $row[0];
        $reply = $row[1];

        // 将关键词和回复内容添加到数组中
        $keywords[] = array('keyword' => $keyword, 'reply' => $reply);
    }

    fclose($file);

    // 处理接收的消息
    $xmlData = file_get_contents('php://input');
    if (!empty($xmlData)) {
        $xmlObj = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA);

        // 提取关键词
        $keyword = trim($xmlObj->Content);
        
        // 模糊匹配关键词并获取回复内容
        $replyContent = '';
        
        $foundMatch = false; // 用于标记是否找到匹配的关键词
        
        foreach ($keywords as $item) {
            $itemKeyword = $item['keyword'];
            if (stripos($itemKeyword, $keyword) !== false) {
                $foundMatch = true;
                
                // 关键词有回复的情况下的上方常驻
                $replyContent .= 广告() . "\n";
                
                $replyContent .= $item['reply'] . "\n";
            }
        }

        // 如果没有匹配到回复内容，则使用默认回复
        if (!$foundMatch) {
            $defaultReply = '在没有对应关键词的情况下的默认回复内容';

            // 默认回复情况下的上方常驻
            //$replyContent .= 广告() . "\n";
            //没有关键词回复的时候，上方的常驻是默认不显示的，如果需要，就把上面两段段//删了就有了
            // 添加默认回复
            $replyContent .= $defaultReply . "\n";
        }

        // 添加下方常驻话语
        $constantPhrase ='----------------' ."\n" . '下方常驻内容';
        $replyContent .= "\n" . $constantPhrase;

        // 构造回复消息
        $replyXml = "<xml>
                        <ToUserName><![CDATA[{$xmlObj->FromUserName}]]></ToUserName>
                        <FromUserName><![CDATA[{$xmlObj->ToUserName}]]></FromUserName>
                        <CreateTime>" . time() . "</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[{$replyContent}]]></Content>
                    </xml>";

        // 输出回复消息
        echo $replyXml;
    }
} else {
    echo "无法打开文件：$filename";
}
?>

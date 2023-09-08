# WeChat-PHP
微信公众号开发，关注回复，关键词回复突破上限等

## 实现功能
关注回复
非文本消息回复
文本消息常驻内容回复
文本消息对应关键词模糊匹配回复
文本消息无对应关键词默认回复

## 使用教程
公众号开发，需要服务器或者虚拟机。需要一个域名（我没直接用过IP）
建议新人安装宝塔进行设置（不介绍安装方法，[宝塔官网](https://www.bt.cn/new/index.html)
安装Nginx,php。理论上任意版本都可以。
添加网站，把本项目文件下载上传到对应网站文件夹下，把公众号服务器配置设置的令牌(Token)，填写到index.php对应位置中。
（建站的具体教程，建议自行百度。别忘了选择PHP版本，另外建议用微信提供的[测试账号](https://mp.weixin.qq.com/debug/cgi-bin/sandbox?t=sandbox/login)先进行调试

index.php：这里有配置token，关注回复，非文本消息回复，进行文本关键词回复的php文件位置。
hf.php：读取csv表格第一列内容，作为关键词，回复对应第二列内容。关键词是全模糊匹配，不懂自行测试几种情况就知道了。常驻内容消息。
file.csv：关键词和回复内容存储列表。第一列A是关键词，第二列B是回复内容。支持一些html，比如超链接

如果你不修改文件名的情况下，只需要修改好token，就能实现，上述操作。
后续更新也就在csv内添加内容就行，建议在本地用Excel进行添加，后续保存成csv表格就行。

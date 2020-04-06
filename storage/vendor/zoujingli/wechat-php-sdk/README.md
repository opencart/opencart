[![Latest Stable Version](https://poser.pugx.org/zoujingli/wechat-php-sdk/v/stable)](https://packagist.org/packages/zoujingli/wechat-php-sdk)
[![Total Downloads](https://poser.pugx.org/zoujingli/wechat-php-sdk/downloads)](https://packagist.org/packages/zoujingli/wechat-php-sdk)
[![Latest Unstable Version](https://poser.pugx.org/zoujingli/wechat-php-sdk/v/unstable)](https://packagist.org/packages/zoujingli/wechat-php-sdk)
[![License](https://poser.pugx.org/zoujingli/wechat-php-sdk/license)](https://packagist.org/packages/zoujingli/wechat-php-sdk)

此`SDK`运行最底要求`PHP`版本`5.3`, 建议在`PHP7`上运行以获取最佳性能。

微信的部分接口需要缓存数据在本地，因此对目录需要有写权限。

我们鼓励大家使用`composer`来管理您的第三方库，方便后期更新操作（尤其是接口类）。

近期`access_token`经常无故失效，`SDK`已加入失败状态检测，自动重新获取`access_token`并返回结果.

此`SDK`已历经数个线上项目验证与考验，可靠性与稳定性极高，欢迎`fork`或`star`此项目。

# 新微信开发工具推荐
WeChatDeveloper：https://github.com/zoujingli/WeChatDeveloper

**微信SDK开发帮助及交流**
--
* **在做微信开发前，必需先阅读微信官方文档，此SDK也是基于之上进行的封装。**

* **文档链接地址**：http://www.kancloud.cn/zoujingli/wechat-php-sdk

* **Think.Admin**：https://github.com/zoujingli/Think.Admin

* **开发交流QQ群：513350915（新）**

**若对您有帮助，可以赞助并支持下作者哦，谢谢！**
--
![](http://plugs.ctolog.com/pay.png)


**官方接口文档链接**
--
* 使用前需先打开微信帐号的开发模式，详细步骤请查看微信公众平台接口使用说明：  
* 微信公众平台： http://mp.weixin.qq.com/wiki/
* 微信企业平台： http://qydev.weixin.qq.com/wiki/
* 微信开放平台：https://open.weixin.qq.com/
* 微信支付接入文档：https://mp.weixin.qq.com/cgi-bin/readtemplate?t=business/course2_tmpl&lang=zh_CN
* 微信商户平台：https://pay.weixin.qq.com

**微信`SDK`项目源文件托管**
--
* SDK 为开源项目，你可以把它用于任何地址，并不受任何约束，欢迎`fork`项目。
* 通过 [Github](https://github.com/zoujingli/wechat-php-sdk) 下载 SDK 源代码
* 通过 [OSChina](http://git.oschina.net/zoujingli/wechat-php-sdk) 下载 SDK 源代码
* 通过 [Composer](https://getcomposer.org) 包管理工具下载 SDK 源代码

**微信`SDK`封装对接及功能**
--
* 接入验证 （初级权限）
* 自动回复（文本、图片、语音、视频、音乐、图文） （初级权限）
* 菜单操作（查询、创建、删除） （菜单权限）
* 客服消息（文本、图片、语音、视频、音乐、图文） （认证权限）
* 二维码（创建临时、永久二维码，获取二维码URL） （服务号、认证权限）
* 长链接转短链接接口 （服务号、认证权限）
* 标签操作（查询、创建、修改、移动用户到标签） （认证权限）
* 网页授权（基本授权，用户信息授权） （服务号、认证权限）
* 用户信息（查询用户基本信息、获取关注者列表） （认证权限）
* 多客服功能（客服管理、获取客服记录、客服会话管理） （认证权限）
* 媒体文件（上传、获取） （认证权限）
* 高级群发 （认证权限）
* 模板消息（设置所属行业、添加模板、发送模板消息） （服务号、认证权限）
* 卡券管理（创建、修改、删除、发放、门店管理等） （认证权限）
* 语义理解 （服务号、认证权限）
* 获取微信服务器IP列表 （初级权限）
* 微信JSAPI授权(获取ticket、获取签名) （初级权限）
* 数据统计(用户、图文、消息、接口分析数据) （认证权限）
* 微信支付（网页支付、扫码支付、交易退款、给粉丝打款）（认证服务号并开通支付功能）

**接口权限备注：**
--
* 初级权限：基本权限，任何正常的公众号都有此权限
* 菜单权限：正常的服务号、认证后的订阅号拥有此权限
* 认证权限：分为订阅号、服务号认证，如前缀服务号则仅认证的服务号有此权限
* 支付权限：仅认证后的服务号可以申请此权限

**微信开放第三方平台** ---  （案例及文档整理中）
--
* 公众号授权服务
* 公众号推送消息代处理
* 公众号基础业务代处理
* 公众号支付代发起

**微信`SDK`版权声明**
--
* 此SDK基于`MIT`协议发布，任何人可以用在任何地方，不受约束
* 此SDK部分代码来自互联网，若有异议，可以联系作者进行删除

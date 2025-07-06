<?php

// +----------------------------------------------------------------------
// | WeChatDeveloper
// +----------------------------------------------------------------------
// | 版权所有 2014~2025 ThinkAdmin [ thinkadmin.top ]
// +----------------------------------------------------------------------
// | 官方网站: https://thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// | 免责声明 ( https://thinkadmin.top/disclaimer )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/zoujingli/WeChatDeveloper
// | github 代码仓库：https://github.com/zoujingli/WeChatDeveloper
// +----------------------------------------------------------------------

/**
 * 【 名词解释 】
 * 应用私钥：用来给应用消息进行签名，请务必要妥善保管，避免遗失或泄露。
 * 应用公钥：需要提供给支付宝开放平台，平台会对应用发送的消息进行签名验证。
 * 支付宝公钥：应用收到支付宝发送的同步、异步消息时，使用支付宝公钥验证签名信息。
 * CSR 文件：CSR 即证书签名请求（Certificate Signing Request），CSR 文件（.csr）是申请证书时所需要的一个数据文件。
 * 应用公钥证书：在开放平台上传 CSR 文件后可以获取 CA 机构颁发的应用证书文件（.crt），其中包含了组织/公司名称、应用公钥、证书有效期等内容，一般有效期为 5 年。
 * 支付宝公钥证书：用来验证支付宝消息，包含了支付宝公钥、支付宝公司名称、证书有效期等内容，一般有效期为 5 年。
 * 支付宝根证书：用来验证支付宝消息，包含了根 CA 名称、根 CA 的公钥、证书有效期等内容。
 */

/**
 * 应用公钥证书SN（app_cert_sn）和支付宝根证书SN（alipay_root_cert_sn）的 sn 是指什么？
 * 使用公钥证书签名方式下， 请求参数中需要携带应用公钥证书SN（app_cert_sn）、支付宝根证书SN（alipay_root_cert_sn），这里的SN是指基于开放平台提供的计算规则，动态计算出来的公钥证书序列号，与X.509证书中内置的序列号（serialNumber）不同。
 * 具体的计算规则如下：解析X.509证书文件，获取证书签发机构名称（name）以及证书内置序列号（serialNumber）。 将name与serialNumber拼接成字符串，再对该字符串做MD5计算。
 * 可以参考开放平台SDK源码中的 AlipaySignature.getCertSN 方法实现
 *
 * 不直接使用证书文件中内置的序列号原因：开放平台支持开发者上传自己找第三方权威CA签发的证书，而证书文件中内置序列号只能保证同一家签发机构签发的证书不重复
 */

return [
    // 沙箱模式
    'debug'            => true,
    // 签名类型 ( RSA|RSA2 )
    'sign_type'        => 'RSA2',
    // 应用ID
    'appid'            => '2021000122667306',
    // 应用私钥内容 ( 需1行填写，特别注意：这里的应用私钥通常由支付宝密钥管理工具生成 )
    'private_key'      => 'MIIEowIBAAKCAQEAndH26KVe3Iy+8GxVxDuG9ZolYrqGNm8Jpdi9GrQdM81ad4pPyul2NVO+9C2Kr6a6jK6Qw1gyzcwYxtkUC7xoLZUSPpmSH7sH3sD6r2B7Mf5FsrVSa29lcm1+3UkyFgZjYTkx45lfbLmAFHOzOl0WfGkMW0Sq3N/5OMr074E4EnYtALdE3jVQCDf8bzqN3j/Kwe7f10Aglvxili2BrFM564silqcbiJ8U1zDmTdZvmEkP7ia/YVkmt5w3rh7ZBoaubtcM/rVGYXL2hQPwr/pquNCTu7Eh1RcWfpcnbuw+gOnaNyXmNFmZkeNlegXIifcunt1GK6a1pX090R8eFN3LjQIDAQABAoIBACrLY4OETCvL8n6pMbyLU7ZHfTm/UGN0So5xLh4OlxiT56MgmzBvjAE72zzFGKU2tcEuGM0Pnn8Vh+ZruLbR+QHbOV5GMExwX9r0Q0XJCL7uryGdb2L4iu6zaEJC9dTpGIulgbSwwyJtTqC9Gu2Jjm5f4dzhyt8n0KGozzAevwCqI9RaJSD96gGWLbMlHCyWKGy1OdBP4V/+agPyHAGZ9gqpfKY7y4L0My8gUxhWzQWOwihtFACjV66ULhutUYT2bro3j1k9UekKlX7IiWrssPmmmw2vfUbrKiNugF6zkfyStPt7jGJ0CdzAHWe3pyF72TyO5NU2NGcX8eKgYlY2crUCgYEAzOcg5Zot9X+Ao+fYH/Oq/3eGZd6krzByiXfcjuRco7mGODwmUnzt3PpPT1fPry9TxarTajt+A9LuxqWawfQ9eWAfrTGAbtDJB0LYo6CynDqUoRqBukROuNaLQiUqEreOQqt08o6VVblgVLv8475ij8s4z/6C2NSSjgUJHf0PL38CgYEAxS0bcXGI+WtempZ4Q3QMTUmp/+B3zuw9JzSV1gvbVi7MleI9V62V2IXHPSXL5mRhYOuQWR0MnVOhbo69fkEA8HpdSd1q2JjaeS+OiZ0ditcJISQJbqWtvmF2+XtQcbVwfID69GWGxyBEHHTW8AtAzIPc6T7x2izyzBw0lXDHSvMCgYEAi+Y61ckhG/9EC6TeMWKjG+21u5P6CQshCK7nzkAo6DhhZb/bwnI9zaSxxdCEom3D2rA5zMx1y5KXKNYlBcwGtPpmZk/oCsFOoECJvZ6YlIaCuERq0oyU2yrQxgat5T2iSe7a2El1uKPrG6+GiNCSZu8wCQMSv4zTy1ew0+LWHW0CgYAvX7ESRpcEZjmqprBqdH1oLGS9566hdr0SqF2/ucWPJVteP6dBY6F3Dl1aYbRlvIRxBuf9oS8gtbE5oO4CYZfaL2wujRZYyBDlwPlcMvWgIB4/aish/IiMD1rIgkpHp7JJF6w0ABiryyLSO3hQ4ENHX/85wzfUlawYQkaYCSq45QKBgCdqrv58KD8tDYn4JnaHJNE+5TgKK5cNhYLZLAsYz7x1KfPdkiC7y/hnenn3TWkm4xw8Tw1rJ1ZIJ24iZgTCTO7EEsB7jZegvg4z/4zVbSK2Y4VI1lJ7jlyqmwg0ArimXTNZFoy66h9c9t2smG40YEZCmmmTLEqVlWgyR1MU5iM5',
    // 公钥模式，支付宝公钥内容 ( 需1行填写，特别注意：这里不是应用公钥而是支付宝公钥，通常是上传应用公钥换取支付宝公钥，在网页可以复制 )
    'public_key'       => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuQ70Xj4AHsIsjjuFK2EexkUVDGc6KCBzhHylt5vbgGTJFzrElV6Ri3O4nxwtKzLWykRfjOC5M9z5vt7NrmSc3WPal0B82TNt0SDVO3gDRvnFswB9pC1WHBTRqiGEsX6LpNOCiykyYHAmc3b74R1rotxytOpLt9/HwjZuGStouUQ7gLqnFRwYLKis8tW9FY5NEL9HPENREcRoaUE8zHE6l4jNi5g2Dvs4r5KqnTNvmeRTc87ZylKs4JPhSWskOaqBJmDAcTR770x0G694tKjW5sbm4a/PxWOV3eEG9XLA4CcS6gwHG1KsRu+eTPszQwkEOZCT8PxZJ6SbaUwZsUO4ZQIDAQAB',
    // 证书模式，应用公钥证书路径 ( 新版资金类接口转 app_cert_sn，如文件 appCertPublicKey.crt )
    'app_cert_path'    => __DIR__ . '/alipay/appPublicCert.crt', // 'app_cert' => '证书内容',
    // 证书模式，支付宝根证书路径 ( 新版资金类接口转 alipay_root_cert_sn，如文件 alipayRootCert.crt )
    'alipay_root_path' => __DIR__ . '/alipay/alipayRootCert.crt', // 'root_cert' => '证书内容',
    // 证书模式，支付宝公钥证书路径 ( 未填写 public_key 时启用此参数，如文件 alipayPublicCert.crt )
    // 'alipay_cert_path' => __DIR__ . '/alipay/alipayPublicCert.crt', // 'public_key' => '证书内容'
    // 支付成功通知地址
    'notify_url'       => '',
    // 网页支付回跳地址
    'return_url'       => '',
];
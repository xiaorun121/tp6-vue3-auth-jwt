<?php
// +----------------------------------------------------------------------
// | Cookie设置
// +----------------------------------------------------------------------
return [
    // cookie 保存时间
    'expire'    => 60 * 60 * 24,
    // cookie 保存路径
    'path'      => '/',
    // cookie 有效域名
    'domain'    => '',
    //tp6如果php.ini中的session.cookie_secure 默认配置是不打开的，config/cookie.php中的secure设置为true的话将不能读取session，需要session.cookie_secure手动打开设置才可以,
    //原来tp5的是有ini_set('session.cookie_secure', $config['secure']);这个配置，tp6没有了，建议同步设置一下
    //  cookie 启用安全传输 false  secure设置为true的话将不能读取session
    'secure'    => false,
    // httponly设置
    'httponly'  => false,
    // 是否使用 setcookie
    'setcookie' => true,
    // samesite 设置，支持 'strict' 'lax'
    // 'samesite'  => '',
];

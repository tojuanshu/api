<?php
return array(
    
    // 解析api_name的方式:
    // server_name
    // url_path
    
    
    // api插件配置
    'plugin' => array(
        // test插件
        'test' => array(
            // 请求接口
            //'url' => 'https://test.imzhaike.com/Wap/Fly/index',
            //'url' => 'http://api.yuan37.lo:8888/server.php',
            'url' => 'http://tp5.framework.lo',
            
            // 响应路径
            //'server_api_path' => __DIR__ . '/Lib/localhost/',
            
            // 交互方式
            'parse_request_type' => 'post',
        ),
        'post' => array(
            // 接口地址
            'url' => 'http://api.yuan37.lo:8080/server.php',
            'server_api_path' => __DIR__ . '/Lib/localhost/',
        ),        
        'localhost' => array(
            // 响应路径
            'server_api_path' => __DIR__ . '/Lib/localhost/',
        ),        
    ),





);
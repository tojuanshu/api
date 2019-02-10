<?php

// 引入文件
include __DIR__ . '/../api.fun.php';

// 加载模块
load_api(true);

// 调取接口
$data = Api::test('index.server', [
    'username' => 'xuyuanx',
    'password' => 'cccc',
]); 

print_r($data);
exit;
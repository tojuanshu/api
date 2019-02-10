<?php

// 引入文件
include __DIR__ . '/../api.fun.php';

// 加载模块
load_api(true);


// 启动服务
$data = Api::agent();

echo json_encode($data);



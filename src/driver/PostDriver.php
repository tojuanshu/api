<?php
/**
 * @link https://github.com/yuan37
 * @copyright Copyright (c) 2019 xuyuan All rights reserved.
 * @author xuyuan <1184411413@qq.com>
 */

namespace yuan37\api\driver;


use yuan37\api\library\Driver;


/**
 * Driver for Fly-Api
 * @function: Api请求驱动器
 * @author: xuyuan
 */
class PostDriver extends Driver {
	
    
    /**
     * 驱动初始化操作
     */
    protected function bootstrap($params)
    {
        // print_r($params);exit;
        return $params;
    }

    
    
    
    /**
     * 将API调用端的的参数发送到指定接口
     * @param: 调用端发过来的参数
     * @return: 接口返回的响应结果
     */
    protected function clientRequest($params)
    {

        // print_r($this->config);exit;
        
        // 参数处理
        // $params['data']['api'] = $params['api'];
        
        
        $config = array(
            // 请求URL
            'url' => $this->config['url'],
            
            // 请求方式
            'method' => 'POST',
            
            // 请求的待JSON格式化数据
            'post' => $params['data'],
            
            // 请求头
            'row' => array(
                'API_NAME' => $params['api'],
            ),
        );
        

        // 请求
        $http=H();
        return $http->request($config);
        
    }    
    

    
    /**
     * 对接口返回响应结果进行处理
     * 只处理接口层面的状态
     * 业务层面的状态由由调用方和响应方决定
     */
    protected function clientResponse($http)
    {
        // http响应200
		if ($http->getCode() == '200') {
            // 获取http响应体
			$content = $http->getContent();

            // 解析http响应体
            $data = json_decode($content, true);        
            // 检查不合格
            if (!isset($data['code'], $data['info'], $data['data'])) {
                // 返回响应结果
                return array(
                    'code' => 10010,
                    'info' => 'error',
                    'data' => $content,
                );            
            } else {
                // 返回响应结果
                return array(
                    'code' => $data['code'],
                    'info' => $data['info'],
                    'data' => $data['data'],
                );            
            }            
            
            
		} else {
			$content = $http->getMessage();
            return array(
                'code' => 10000,
                'info' => $http->getMessage(),
                'data' =>  $http->getDebug(),
            );
            
            
            
		} 
    }

 
    /**
     * 服务端接收请求并处理
     */
    protected function serverRequest($params)
    {
        // return $params['data'];
        
        // 获取API路径
        $path = $this->config['server_api_path'] . '/' . lcfirst($params['class']) . 'Api.php';

        $path = realpath($path);
        
        if ($path) {
            include($path);
        }
        
        // 获取API对象
        $objName = $params['class'] . 'Api';
        $obj = new $objName;
        $action = $params['method'];
        
        
        // 执行方法
		if (method_exists($obj, $action)) {
			$return = $obj->$action($params['data']);
		} else {	
			$return = $obj->other($params['data']);
		}        
        

        return $return;            
        
    }
    

    /**
     * 解析请求
     */
    protected function parseRequest($params)
    {
        
        $data = $_POST;        
        
        $params['data'] = $data;
        
        return $params;
        
    }

    
    /**
     * 服务端对响应结果处理
     */
    protected function serverResponse($params)
    {
        // 返回响应结果
        $response =  array(
            'code' => 20010,
            'info' => 'success',
            'data' => $params,
        );   
        
        return json_encode($response);
        
        
    }
	
}

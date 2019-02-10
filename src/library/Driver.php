<?php
/**
 * @link https://github.com/yuan37
 * @copyright Copyright (c) 2019 xuyuan All rights reserved.
 * @author xuyuan <1184411413@qq.com>
 */

namespace yuan37\api\library;

/**
 * 中转器
 */
abstract class Driver {
	protected $module;
	protected $class;
	protected $method;
	protected $data=array();
    protected $config = array();
	protected $result='';
	
	//abstract function run($method,$before,$after);
	//访问一个不存在的方法,先给个机会访问接口自已实现
	/*
    public function __call($method,$params){
		if(method_exists($this,'bootstrap')){
			return $this->bootstrap($method);
		}else{	
			return "can't ask a not exists method '{$method}'";
		}
	}
    */
    
    /**
     * 执行
     */
    public function clientRun($params)
    {
        
        // 参数预处理
		$params = $this->bootstrap($params);


        
        // 设置插件配置
        $this->config = $params['config'];
        
        // 执行方法
		if (method_exists($this, $params['method'])) {
			$return = $this->$params['method']($params);
		} else {	
            $content = $this->clientRequest($params);



            $response = $this->clientResponse($content);



            $return = $response; 
		}        
        
        // 返回接口处理结果
        return $return;
        
        
    }

    public function serverRun($params)
    {
        
        // 解析请求参数
        $params = $this->parseRequest($params);
        
        // 参数预处理
		$params = $this->serverBootstrap($params);

        // 设置插件配置
        $this->config = $params['config'];
        
        // 执行方法
		if (method_exists($this, $params['method'])) {
			$return = $this->$params['method']($params);
		} else {	
            $content = $this->serverRequest($params);
            $response = $this->serverResponse($content);
            $return = $response; 
		}        

        // 返回接口处理结果
        return $return;
        
        
    }    
    
    /**
     * 触发器：执行相应操作
     */
    private function trigger($params)
    {
        $content = $this->serverRequest($params);
        $response = $this->serverResponse($content);
        return $response;        
    }
    

    /**
     * 初始化
     */
    protected function clientBootstrap($params)
    {
        return $params;
    }

    /**
     * 初始化
     */
    protected function serverBootstrap($params)
    {
        return $params;
    }

    /**
     * 请求
     */
    protected function clientRequest($params)
    {
        return $params;
    }
    
    /**
     * 响应
     */
    protected function clientResponse($content)
    {
        return $content;
    }  

    /**
     * 请求
     */
    protected function serverRequest($params)
    {
        return $params;
    }
    
    /**
     * 响应
     */
    protected function serverResponse($content)
    {
        return $content;
    }  
	
}
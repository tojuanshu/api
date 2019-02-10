<?php
/**
 * @link https://github.com/yuan37
 * @copyright Copyright (c) 2019 xuyuan All rights reserved.
 * @author xuyuan <1184411413@qq.com>
 */

namespace yuan37\api\library;


/**
 * API远程服务组件
 * Use:
 * Api::driver('controller.action', $params);
 * 
 */

class Api
{

	static public $classMap=array();


	static public $obj=array();//实例化后的API对象
    

    /**
     * 代理端：调用代理服务
     */
    static public function agent()
    {
        // 确定请求解析方式
        // 解析请求获取取:
        // $plugin,$action,$params
        
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);

        // print_r($params['api']);exit;

        if (empty($params)) {
            $params = array();
        }
        
        if (!empty($params['api'])) {
            $api = $params['api'];
            $api_arr = explode('.', $api);
        }
        
        unset($params['api']);
        
        
        
        
        
        if (empty($api) || count($api_arr) != 3) {
            $return = array(
                'code' => 10000,
                'info' => 'api request error',
                'data' => $params,
            );
            return json_encode($return);
        } else {
            $module = $api_arr[0];
            $action = $api_arr[1] . '.' . $api_arr[2];
            $params = $params;
            
        }


        // 调取接口
        // $data = Api::localhost($api, $params);         
        
        
        // $params['jhfgioqhdvhldfiosdh58848454_server'] = true;
        
        
        return self::$module($action, $params);
    }


    
    /**
     * 服务端：调用启动服务
     */
    static public function server()
    {
        /*
        $return = array(
            'code' => 10000,
            'info' => 'api request error',
            'data' => $_SERVER,
        );        
        
        
        return json_encode($return);
        */
        
        
        
        
        
        
        // 确定请求解析方式
        // 解析请求获取:
        // $plugin,$action,$params
        /*
        $input = file_get_contents('php://input');
        $params = json_decode($input, true);
        */
        // print_r($params['api']);exit;

        
        
        // 确定请求解析方式
        $api = empty($_SERVER['HTTP_APINAME']) ? '' : $_SERVER['HTTP_APINAME'];
        if (!empty($api)) {
            $api_arr = explode('.', $api);
        } else {
            $api_arr = array();
        }
        
        
        // 解析请求获取 
        if (empty($api) || count($api_arr) != 3) {
            $return = array(
                'code' => 10000,
                'info' => 'api request error',
                'data' => $api,
            );
            return json_encode($return);
        } else {
            $module = $api_arr[0];
            $action = $api_arr[1] . '.' . $api_arr[2];
            $params = array();
            
        }


        // 调取接口
        // $data = Api::localhost($api, $params);         
        
        
        $params['jhfgioqhdvhldfiosdh58848454_server'] = true;
        
        
        
        
        return self::$module($action, $params);
    }
    
	/**
	 * 其它方法都定义为私有方法，保证访问的所有的方法都被__callstatic捕获
     */
	static public function __callstatic($action, $params)
    {
		//解析参数
		list($module, $class, $method, $data)=self::parseSpace($action, $params);
        
        
        
        if (!empty($data['jhfgioqhdvhldfiosdh58848454_server'])) {
            $type = 'server';
            
        } else {
            $type = 'client';
        }
        
        
        unset($data['jhfgioqhdvhldfiosdh58848454_server']);        
        
        
        
        // 读取配置
        $config = include __DIR__ . '/../../test/' .  '/api.conf.php';

       
        
        
		$className= "\\yuan37\\api\driver\\" . ucfirst($module).'Driver';

		//加载文件，取得实例
		$file = __DIR__ . '/../driver/' . ucfirst($module).'Driver' . '.php';

        
		if(!isset(self::$obj[$className])){
			if(is_file($file)){
				require($file);
				self::$obj[$className]=new $className();
			}else{
				return 'error: <'.$module . '.' . $class . '.' . $method . '> not exists';			
			}		
		}
        
		$obj=self::$obj[$className];        
        


        $config_plugin_name = lcfirst($module);
        if (!empty($config['plugin'][$config_plugin_name])) {
            $config_plugin = $config['plugin'][$config_plugin_name];
        } else {
            $config_plugin = array();
        }


		//初始化，执行，返回
		// return $obj->_init(array(
        if ($type == 'client') {
            return $obj->clientRun(array(
                'module'=>$module,
                'class'=>$class,
                'method'=>$method,
                'config' => $config_plugin,
                'api' => lcfirst($module) . '.' . lcfirst($class) . '.' . lcfirst($method),
                'data'=>$data
                )
            );
        } else {
            return $obj->serverRun(array(
                'module'=>$module,
                'class'=>$class,
                'method'=>$method,
                'config' => $config_plugin,
                'api' => lcfirst($module) . '.' . lcfirst($class) . '.' . lcfirst($method),
                'data'=>$data
                )
            );            
        }
        
        
        
        
	}

	
	
	
	
	
	
	
	//解析参数，
	static private function parseSpace($module,$params)
    {
		//$path=ucfirst($module);						//空间名
		$type=explode('.',$params[0]);				//对象名:方法名
		if(count($type)==1){
			$class=$type[0];
			$action=$type[0];		
		}else if(count($type)==2){
			$class=$type[0];		
			$action=$type[1];		
		}else{
			$class='self';
			$action='error';
		}
        
        $module = self::camelCase($module, true);
        $class = self::camelCase($class, true);
        $action = self::camelCase($action, false);
		$data=isset($params[1])?$params[1]:array(); //参数	
		
		return array($module,$class,$action,$data);
	}
    
    
    
    /**
     * 下划线转骆峰结构
     */
    static private function camelCase($str, $all = true)
    {
        $str_c = strtolower($str);
        $str_c = str_replace('_', ' ', $str_c);
        $str_c = ucwords($str_c);
        $str_c = str_replace(' ', '', $str_c);  
        if (!$all) {
            $str_c = lcfirst($str_c);
        }
        
        return $str_c;
    }
    


}



<?php


$online_status = 0;//1正式，0调试

if($online_status == 1){//正式在线
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
if($online_status == 0){//调试
	error_reporting(E_ALL );
}


$cache_log_flag = FALSE;


//error_reporting(E_ALL);
$GLOBALS["log_flag"] = FALSE;
$GLOBALS["cache_1"] = null;//缓存1


class MyDbClass {

	public $database="mediavolap";
	public $db6user="wuwei";
	public $db6passwd="";
	public $db6host="192.168.3.144";
	public $db6port="3306";
	
	
	

	//通过一句sql从db6中得到一个res,这个函数先留着，以后有可能出现不是所有数据都取到本地而是
	public function db6_get_res($sql,$log_flag = FALSE,$log_path="/home/wuwei/logs/web_logs/query.log"){

		$to_conn_str = "$this->db6host:$this->db6port";
		

		/*
		 * newlink	可选。如果用同样的参数第二次调用 mysql_connect()，将不会建立新连接，
		 * 而将返回已经打开的连接标识。参数 new_link 改变此行为并使 mysql_connect() 总是打开新的连接，甚至当 mysql_connect() 曾在前面被用同样的参数调用过。
		 clientflag
		 可选。client_flags 参数可以是以下常量的组合：
		 MYSQL_CLIENT_SSL - 使用 SSL 加密
		 MYSQL_CLIENT_COMPRESS - 使用压缩协议
		 MYSQL_CLIENT_IGNORE_SPACE - 允许函数名后的间隔
		 MYSQL_CLIENT_INTERACTIVE - 允许关闭连接之前的交互超时非活动时间
		 */
		$begin_time = time();
		$begin_date = strtotime("Y-m-d H:i:S");
		
		$conn = mysql_connect($to_conn_str,$this->db6user,$this->db6passwd);

		mysql_query("set names utf8;",$conn);
		
		$res = mysql_query($sql,$conn);

		$end_time = time();
		
		if($GLOBALS["log_flag"]  == TRUE ||$log_flag == TRUE){//全局指定或者单条指定
			$fp = fopen($log_path, "a");
			fprintf($fp, "begin_date:%s\tsql:%s\tspend:%d\n",$begin_date ,$sql,$end_time - $begin_time );
			fclose($fp);
		}
		
		
		return $res;
	}
	//返回序列化的数组
	public function db6_get_data_array($sql,$log_flag = FALSE,$log_path="/home/wuwei/logs/web_logs/query.log"){

		$to_conn_str = "$this->db6host:$this->db6port";
		

		/*
		 * newlink	可选。如果用同样的参数第二次调用 mysql_connect()，将不会建立新连接，
		 * 而将返回已经打开的连接标识。参数 new_link 改变此行为并使 mysql_connect() 总是打开新的连接，甚至当 mysql_connect() 曾在前面被用同样的参数调用过。
		 clientflag
		 可选。client_flags 参数可以是以下常量的组合：
		 MYSQL_CLIENT_SSL - 使用 SSL 加密
		 MYSQL_CLIENT_COMPRESS - 使用压缩协议
		 MYSQL_CLIENT_IGNORE_SPACE - 允许函数名后的间隔
		 MYSQL_CLIENT_INTERACTIVE - 允许关闭连接之前的交互超时非活动时间
		 */
		$begin_time = time();
		$begin_date = strtotime("Y-m-d H:i:S");
		
		$conn = mysql_connect($to_conn_str,$this->db6user,$this->db6passwd);

		mysql_query("set names utf8;",$conn);
		
		$res = mysql_query($sql,$conn);

		$end_time = time();
		
		$array_to_return = array();
		
		$offset = 0;
		
		while($row = mysql_fetch_array($res,MYSQL_ASSOC )){//只产生关联数组
			foreach($row as $key=>$value){
				$array_to_return[$offset][$key] = $value;
			}
			$offset++;
		}
		
		
		
		if($GLOBALS["log_flag"]  == TRUE ||$log_flag == TRUE){//全局指定或者单条指定
			$fp = fopen($log_path, "a");
			fprintf($fp, "begin_date:%s\tsql:%s\tspend:%d\n",$begin_date ,$sql,$end_time - $begin_time );
			fclose($fp);
		}
		
		return $array_to_return;
		
	}
	
	
	
	public function db6_get_data_array_from_cache($sql,$cache_flag=true,$log_flag=false,$log_path="/home/wuwei/logs/web_logs/query.log"){

		$cache_1_ip = "10.0.2.140";
		$chache_1_port = 6390 ;
		
		
		$to_cache_flag = fales;
		$cache_log_flag = false;//如果命中就记日志，用于调试
		$md5 = null;
		$cache_1 = null;
		$cache_log_path = "/home/wuwei/logs/web_logs/cache.log";

		if($cache_flag==true){//程序要使用cache
				
				
				
			//  选项设置
			/*
			 $options = array(
			 'servers' => array('192.168.3.49:11211'), //memcached 服务的地址、端口，可用多个数组元素表示多个 memcached 服务
			 'debug' => FALSE,  //是否打开 debug,如果打开的话会把telnet的回显显示出来
			 'compress_threshold' => 10240,  //超过多少字节的数据时进行压缩
			 'persistant' => true  //是否使用持久连接
			 );
			 */
			//  创建 memcached 对象实例
				

			if($GLOBALS["cache_1"] == null){//之前连过，这是一个单例的实现
			$cache_1 = new Redis();
			$cache_1->connect( $cache_1_ip, $chache_1_port);
			$GLOBALS["cache_1"] = $cache_1;
			
			
			}else{
				$cache_1 = $GLOBALS["cache_1"] ;
			}
				
			//$cache_1 = new memcached($options);

				
				
				
			if($cache_1){//连上了
				$md5 = md5($sql);
				$cached_string = $cache_1->get($md5);

				if($cached_string){//得到了缓存中的内容
						
					if($cache_log_flag ){//要写缓存日志

						$cache_log_fp = fopen($cache_log_path, "a");
						fprintf($cache_log_fp, "get md5:%s\n",$md5);
						fclose($cache_log_fp);
					}
					return json_decode($cached_string, true);
				}
			}else{
				$to_cache_flag = true;//准备得到结果之后放入缓存
			}

		}

		$to_conn_str = "$this->db6host:$this->db6port";


		/*
		 * mysql的参数
		 * newlink	可选。如果用同样的参数第二次调用 mysql_connect()，将不会建立新连接，
		 * 而将返回已经打开的连接标识。参数 new_link 改变此行为并使 mysql_connect() 总是打开新的连接，甚至当 mysql_connect() 曾在前面被用同样的参数调用过。
		 clientflag
		 可选。client_flags 参数可以是以下常量的组合：
		 MYSQL_CLIENT_SSL - 使用 SSL 加密
		 MYSQL_CLIENT_COMPRESS - 使用压缩协议
		 MYSQL_CLIENT_IGNORE_SPACE - 允许函数名后的间隔
		 MYSQL_CLIENT_INTERACTIVE - 允许关闭连接之前的交互超时非活动时间
		 */
		$begin_time = time();
		$begin_date = strtotime("Y-m-d H:i:S");

		$conn = mysql_connect($to_conn_str,$this->db6user,$this->db6passwd);

		mysql_query("set names utf8;",$conn);

		$res = mysql_query($sql,$conn);

		$end_time = time();

		$array_to_return = array();

		$offset = 0;

		while($row = mysql_fetch_array($res)){
			foreach($row as $key=>$value){
				$array_to_return[$offset][$key] = $value;
			}
			$offset++;
		}


		if($to_cache_flag==true && $cache_1 && ( count($array_to_return) > 0)){//要缓存且缓存连接上了,数据库返回的也有结果
			if($cache_log_flag ){//要写缓存日志
				$cache_log_fp = fopen($cache_log_path, "a");

				fprintf($cache_log_fp, "set md5:%s\n",$md5);
				fclose($cache_log_fp);
			}
				
			$cache_1->set($md5,json_encode($array_to_return));//把结果放入内存
			$cache_1->expire($md5,3600);
		}

		if($GLOBALS["log_flag"]  == TRUE ||$log_flag == TRUE){//全局指定或者单条指定
			$fp = fopen($log_path, "a");
			fprintf($fp, "begin_date:%s\tsql:%s\tspend:%d\n",$begin_date ,$sql,$end_time - $begin_time );
			fclose($fp);
		}

		return $array_to_return;

	}
	
}

//调用函数的框架
function mv_call_user_func(){
	$begin_time = microtime(TRUE);//开始时间

	$arg_array = func_get_args();

	$cnt = count($arg_array);
	$new_param_array = array();
	$function = $arg_array[0];
	//钱栋军：得到函数名称。
	for($i = 0,$k = -1 ; $i < $cnt; $i++,$k++){
		if($i > 0)
		$new_param_array[ $k ] = $arg_array[$i];
		//钱栋军：这个函数调用时传递的参数（除函数名之外）

	}
	//调用函数
	call_user_func_array($function, $new_param_array);


	$end_time  = microtime(TRUE);
	
	$to_log_array = array("begin_time" =>$begin_time,
	"end_time"=>$end_time,
	"func_name" => $function);
	
	
	if($GLOBALS["cache_1"] == null){//之前连过，这是一个单例的实现
			$cache_1 = new Redis();
			$cache_1->connect( $cache_1_ip, $chache_1_port);
			$GLOBALS["cache_1"] = $cache_1;
			
			
			}else{
				$cache_1 = $GLOBALS["cache_1"] ;
			}
			$cache_1->LPUSH("log_queue_1",json_encode($to_log_array));
			/*钱栋军： json_encode会输出：{"begin_time"：$begin_time,	"end_time"：$end_time,"func_name"： $function}的格式
			LPUSH的作用就是在头部插入：key为log_queue_1， value为json_encode($to_log_array)值
			*/

}

?>
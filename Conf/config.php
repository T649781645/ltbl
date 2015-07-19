<?php
//应用公共配置文件
return array(
	//'配置项'=>'配置值'
	'LOAD_EXT_CONFIG' 			=> 'db', 	// 加载数据库扩展配置文件

	//应用分组配置
	'APP_GROUP_LIST' 			=> 'Home,Admin',//分组名
	'DEFAULT_GROUP' 			=> 'Home',//默认组
	//独立分组
	'APP_GROUP_MODE'			=> 0,//独立分组开关(默认为0)
	//'APP_GROUP_PATH' 			=> 'Modules',//独立分组控制器目录（独立分组开关打开时有效）
	//开发调试配置
	'APP_STATUS' 				=> 'debug', //应用调试模式状态
	'APP_STATUS' 				=> 'test', //应用调试模式状态
	'APP_VERIFY'				=> false,//是否启用验证码

	//系统配置
	'URL_CASE_INSENSITIVE'		=>  false,//开启不区分大小写 True为不区分 False区分
	'URL_MODEL'                 =>  2, // 如果你的环境不支持PATHINFO 请设置为3
    'SESSION_AUTO_START'        =>  true,//自动开启SESSION
	'URL_HTML_SUFFIX'			=>	'html' ,//伪静态后缀
    'SHOW_PAGE_TRACE'           =>  1,//调试信息TRACE

	//常用路径定义
	'TMPL_PARSE_STRING'  =>array(
    '__PUBLIC__' => '/Public', // 更改默认的__PUBLIC__ 替换规则
    '__JS__' => '/Public/Js', // 增加新的JS类库路径替换规则
    '__UPLOAD__' => '/Uploads', // 增加新的上传路径替换规则
	)
);
?>
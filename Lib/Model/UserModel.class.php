<?php
class UserModel extends RelationModel {
	//自动验证规则
	protected $_validate = array(
		//array('account','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证account字段是否唯一
		//array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
	);


	//关联规则
	protected $_link = array(
		//用户与用户组的多对多关联
		'Role'=>array(
		'mapping_type'    =>MANY_TO_MANY,//关联类型
		'mapping_name'	  =>'group',//关联的映射名称
		'class_name'   =>'role',//关联的模型对应的数据表
		'relation_table'=>'think_role_user',//中间表
		'foreign_key'	=>'user_id',//外键
		'relation_foreign_key'=>'role_id',//关联表的外键名称
		'mapping_fields'	=>'id,name',
		'mapping_order'		=>'id',
		'mapping_limit'		=>'100',
         ),
    );
}
?>
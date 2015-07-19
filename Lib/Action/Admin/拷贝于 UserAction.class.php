<?php
class UserAction extends CommonAction {
	function index(){
		$User = D('User');
		$list = $User->relation(true)->field('id,account,status,last_login_time,last_login_ip,login_count')->order('id asc')->Select();
		$this->assign('list',$list);
		//echo $User->getlastSql();
		//dump($list);
		C('SHOW_PAGE_TRACE',1);
		$this->display();
	}
	function del(){
		$User = M('User');
		$id = $this->_param('id');
		switch(gettype($id)){
			//单独删除
			case 'string':
				$result = $User->delete($id);
				M('role_user')->where(array('user_id'=>array('eq',$id)))->delete();//删除对应关系
				if(!false == $result){
					$this->success('删除成功！');
				}else{
					$this->error('删除失败！');
				}
				break;
			//批量删除
			case 'array':
				$result = $User->delete(implode(',',$id));
				M('role_user')->where(array('user_id'=>array('in',implode(',',$id))))->delete();//删除对应关系
				if(!false == $result){
					$this->success('删除成功！');
				}else{
					$this->error('删除失败！');
				}
			break;
			default:
				$this->error('参数错误！');
			break;
		}
	}
	
	//添加帐号
	function add(){
		if($this->isGet()){
			$User = M('Role');
			$result = $User->where(array('status'=>1))->field('id,name')->select();
			$this->assign('role',$result);
			$this->display();
		}
		if($this->isPost() or $this->isAjax()){
			$User = M('User');
			$validate = array(
				//array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
				array('account','require','帐号必须！'), //帐号必须
				array('account','','帐号名称已经存在！',1,'unique',1),
				array('password','require','密码必须！'), 
				array('email','email','邮箱格式不对！',2),
			);
			$User->setProperty("_validate",$validate);//动态验证
			$date = $User->create();
			if(!$date){$this->error($User->getError());}//验证不通过则抛出错误信息
			$date['password'] = md5($_POST['password']);
			$date['create_time'] = time();
			$result_account = $User->add($date);
			//将用户添加到组
			if(count($_POST[['role']!=0)){//添加帐号时勾选了用户组（权限组）才执行
				$User = M('role_user');
				$date = array();
				for($i=0;$i<count($_POST['role']);$i++){
					$date[$i]['role_id'] = $_POST['role'][$i];
					$date[$i]['user_id'] = $result_account;
				}
				//此处应该继续检查组是否存在，存在后才添加
				$result_role = $User->addAll($date);
				if((!false == $result_account) and (!false == $result_role)){
					$this->success('新增帐号成功');
				}else{
					$this->error('增加帐号失败！');
				}
			}else{//没有勾选用户组（权限组）的时候执行
				if(!false == $result_account){
					$this->success('新增帐号成功');
				}else{
					$this->error('增加帐号失败！');
				}
			}
		}
	}
	
	
	//修改编辑
	function edit($id=0){
		if($this->isGet()){
			$User = D('User');
			$result = $User->relation(true)->find($id);
			$this->assign('list',$result);
			$role = $result['group'];
			$role_id = array();
			for($i=0;$i<count($role);$i++){
				$role_id[$i] = $role[$i]['id'];
			}
			$this->assign('role_id',implode(',',$role_id));
			$User = M('Role');
			$role = $User->where(array('status'=>1))->field('id,name')->Select();
			$this->assign('role',$role);
			$this->display();
		}
		if($this->isPost()){
			$User = M('User');
			$date = $User->create();
			if($date['password']==NULL){
			   $date['password'] = $User->where(array('id'=>$date['id']))->getField('password');//密码字段为空时获取原来的密码
			}else{
			   $date['password'] = md5($date['password']);
			}
			$result = $User->save($date);
			if(false !== $result){
				$this->success('更新资料成功！');
			}else{
				$this->error('更新资料失败！');
			}
		}
	}
	
	//快捷更新状态
	function status($id=0,$status=0){
		if($status == -1){$status = 0;}
		$status = $status?'0':'1';//取反状态值
		$result = M('User')->where(array('id'=>$id))->setField(array('status'=>$status));
		redirect(__URL__.'/index');
	}

}
?>
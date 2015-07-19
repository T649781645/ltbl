<?php
class RoleAction extends CommonAction {
	public function index(){
		$User = M('Role');
		$list = $User->Select();
		$this->assign('list',$list);
		$this->display();
	}
	
	public function user(){
		
	}
	
	//添加组
	 public function add(){
		 if($this->isPost() or $this->isAjax()){
			$User = M('role');
			$date = $User->create();
			$date['create_time'] = time();
			$date['update_time'] = time();
			$result = $User->add($date);
			if(!false == $result){
				$this->success('新增组成功');
			}else{
				$this->error('增加组失败！');
			}
		}else{
			$User = M('node');
			$result = $User->field('id,name,title,level')->where(array('level'=>array('eq',2)))->select();
			for($i=0;$i<count($result);$i++){
				$map['pid&level'] = array($result[$i]['id'],3,'_multi'=>true); 
				$result[$i]['action'] = $User->field('id,name,title,level')->where($map)->select();
			}
			$this->assign('list',$result);
			$this->display();
		}
	}

	//删除组													
	public function del(){
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
	public function edit($id=0){
		if($this->isGet()){
			$User = M('Role');
			$role = $User->find($id);
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
	public function status($id=0,$status=0){
		$status = $status?'0':'1';//取反状态值
		$result = M('Role')->where(array('id'=>$id))->setField(array('status'=>$status));//更新组状态
		//更新组内用户状态
		$t = M('Role_user');
		$user_id = M('Role_user')->where(array('role_id'=>$id))->field('user_id')->select();//通过中间表查出当前用户组内的成员ID
		if(count($user_id)>0){
			$id = array();
			for($i=0;$i<count($user_id);$i++){//构造成员id数组
				$id[$i] = $user_id[$i]['user_id'];
			}
			$User = M('User');
			$status = $status?'1':'-1';//做特殊标记，用于禁用后启用时对用户状态的还原
			if($status == 1){//还原状态（将组内状态为-1的用户设置为1状态）
				$map['id&status'] = array(array('in',implode(',',$id)),-1,'_multi'=>true); 
				$result = $User->where($map)->setField(array('status'=>$status));
				if(false == $result){
					$this->error($User->getError());//捕获异常信息，并输出显示
				}
			}else if($status == -1){//设置禁用状态（（将组内状态为1的用户设置为-1状态）
				$map['id&status'] = array(array('in',implode(',',$id)),1,'_multi'=>true);
				$result = $User->where($map)->setField(array('status'=>$status));
				if(false == $result){
					$this->error($User->getError());//捕获异常信息，并输出显示
				}
			}
		}
		redirect(__URL__.'/index');
	}

}
?>
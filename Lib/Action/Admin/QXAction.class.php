<?php
class QXAction extends Action {

    public function index(){
	
	}
	
	//添加组
	 public function add_group(){
		 if($this->isPost() or $this->isAjax()){
			$User = M('role');
			$date = $User->create();
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
			dump($result);
			$this->assign('list',$result);
			$this->display();
		}
	}
	
	//添加帐号
	 public function add_account(){
	 	if($this->isPost() or $this->isAjax()){
			$User = M('user');
			$date = $User->create();
			$date['password'] = md5($_POST['password']);
			$result_account = $User->add($date);
			//将用户添加到组
			$User = M('role_user');
			$date = array();
			for($i=0;$i<count($_POST['role']);$i++){
				$date[$i]['role_id'] = $_POST['role'][$i];
				$date[$i]['user_id'] = $result_account;
			}
			// 此处应该继续检查组是否存在，存在后才添加
			$result_role = $User->addAll($date);
			if((!false == $result_account) and (!false == $result_role)){
				$this->success('新增帐号成功');
			}else{
				$this->error('增加帐号失败！');
			}
		}else{
			$User = M('role');
			$map = array();
			$map['status'] = 1;
			$result = $User->where($map)->select();
			$this->assign('role',$result);
			$this->display();
		}			
	}
	
	
	//授权
	public function add_access(){
	 	if($this->isPost() or $this->isAjax()){
			$user_id = $_POST['user'];
			$role_id = $_POST['role'];			
			$User = M('role_user');
			$temp = array();
			foreach($role_id as $key=>$role){
				for($i=0;$i<count($user_id);$i++){
					$date[$i]['role_id'] = $role;
					$date[$i]['user_id'] = $user_id[$i];
				}
				$temp = array_merge($temp,$date);
			}
			//dump($temp);die();
			$result = $User->addAll($temp);
			if($result){
				$this->success('授权成功','',2);
			}else{
				$this->error('授权失败！请刷新页面再试。');
			}
		}else{
			$User = M('user');
			$result_user = $User->where('status=1')->select();
			$User = M('role');
			$result_role = $User->where('status=1')->select();
			$this->assign('list',$result_user);
			$this->assign('role',$result_role);
			$this->display();
		}			
	}
	
}
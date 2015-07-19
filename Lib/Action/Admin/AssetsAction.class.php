<?php
class AssetsAction extends CommonAction {
	public function index(){
		$User = M('Stock');
		$list = $User->Select();
		for($i = 0;$i < count($list);$i++){
			$list[$i]['title'] = M('Assets')->where(array('name'=>$list[$i]['name']))->getField('title');
		}
		$this->assign('list',$list);
		$this->display();
	}
	/*入库*/
	public function add(){
		if($this->isPost() or $this->isAjax()){
			/*执行入库*/
			$User = M('Stock');
			$validate = array(
				//array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
				array('number',number,'入库数量必须是一个正整数！'), 
				//array('number',number > 0,'入库数量必须是一个正整数！'), 
			);
			$User->setProperty("_validate",$validate);//动态验证
			$date = $User->Create();
			if(!$date){$this->error($User->getError());}//验证不通过则抛出错误信息
			$date['time'] = time();
			$date['trading'] = strtotime($date['trading']);
			$date['ip'] = get_client_ip();
			$date['user'] = $_SESSION['loginUserName'];
			$date['type'] = '入库';
			$result = $User->add($date);
			if(!false == $result){
				$update = M('Assets')->where(array('name'=>$date['name']))->setInc('number',$date['number']);
				if(false == $update){$this->error('更新数量失败！');}
				$this->success('入库成功！');
			}else{
				$this->error('入库失败！');
			}
		}else{
			/*渲染入库页面*/
			$User = M('Assets');
			$list = $User->Select();
			$this->assign('trading',time());
			$this->assign('list',$list);
			$this->display();
		}	
	}
	/*出库*/
	public function del(){
		if($this->isPost() or $this->isAjax()){
			/*执行入库*/
			$User = M('Stock');
			$validate = array(
				//array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间]),
				array('number',number,'入库数量必须是一个正整数！'), 
				//array('number',number > 0,'出库数量必须是一个正整数！'),
			);
			$User->setProperty("_validate",$validate);//动态验证
			$date = $User->Create();
			if(!$date){$this->error($User->getError());}//验证不通过则抛出错误信息
			$date['time'] = time();
			$date['ip'] = get_client_ip();
			$date['user'] = $_SESSION['loginUserName'];
			$date['type'] = '出库';
			$result = $User->add($date);
			if(!false == $result){
			/*判断库存数量是否够出库数量后更新数量*/
			$kucun = M('Assets')->where(array('name'=>$date['name']))->getField('number');//获取库存数量
			if(empty($kucun) and ($kucun < $date['number'])){
				$this->error('库存数量不足！');
			}else{
				$result = M('Assets')->where(array('name'=>$date['name']))->setDec('number',$date['number']);//更新数量
			}
				$this->success('出库成功！');
			}else{
				$this->error('出库失败！');
			}
		}else{
			/*渲染入库页面*/
			$User = M('Assets');
			$list = $User->Select();
			$this->assign('list',$list);
			$this->display();
		}	
	}
	
	/*移除流水记录*/
	public function remove(){
			/*渲染入库页面*/
			$this->error('移除流水功能开发中');
			$this->display();
	}
	
	public function computer() {
		$User = M('Computer');
		import('ORG.Util.Page');// 导入分页类
		$count = $User->count();// 查询满足要求的总记录数
		$Page  = new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
		$show  = $Page->show();// 分页显示输出
		$list = $User->limit($Page->firstRow.','.$Page->listRows)->Select();
		$this->assign('list',$list);
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}
	
	public function ip(){
		$User = M('Ip');
		for($i=1;$i<254;$i++){
			$date['ip'] = '192.168.5.'.$i;
			$date['status'] = '0';
			$User->add($date);
		}
	}
	
	public function test(){
	$User = M('Computer');
		$list = $User->distinct(true)->getField('user',true);
		//echo count($list);
		//echo md5('123456');
		for($i=0;$i<count($list);$i++){
			$date['account'] = $list[$i];
			$date['nickname'] = $list[$i];
			$date['password'] = 'e10adc3949ba59abbe56e057f20f883e';
			$date['create_time'] = time();
			$date['status'] = 1;
			$reslust = M('User')->add($date);
		}
		dump($list);
	}
}
?>
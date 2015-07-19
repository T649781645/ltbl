<?php
class NodeAction extends CommonAction {
	public function index(){
		$User = M('Node');
		$list = $User->where(array('level'=>2))->Select();
		$this->assign('list',$list);
		$this->display();
	}
	
	
	//快捷更新状态
	public function status($id=0,$status=0){
		$status = $status?'0':'1';//取反状态值
		$result = M('Node')->where(array('id'=>$id))->setField(array('status'=>$status));
		redirect(__URL__.'/index');
	}	
	
}
?>

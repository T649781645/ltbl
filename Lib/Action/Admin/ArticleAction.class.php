<?php
class ArticleAction extends CommonAction {
    public function index(){
		$User = M("article");
		import('ORG.Util.Page');// 导入分页类
		$count      = $User->count();// 查询满足要求的总记录数
		$Page       = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$list = $User->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);// 赋值分页输出
		C( 'SHOW_PAGE_TRACE', true );//TRACE信息
	$this->display();
	}

	//添加文章
	public function add(){
		if ($this->isPost()){//检测到POST时才提交数据，否则显示添加页面
			$User = M('article');
			$date = $User->create();
			$date['date'] = strtotime($this->_param('date'));//将日期时间转换成时间戳存储
			$User->add($date);
			$this->success('保存完成',__APP__.'/Article/index');
		}else {
		$this->assign('time',date('Y-m-d H:i:s',time()));
		$this->display();
		}
	}

	//文章修改编辑
	public function edit() {
		if ($this->isPost()){//检测为POST提交则更新数据，否则执行下面的读取输出操作
				$User = M('Article');
				$User->create();
				$User->save();
				$this->success('保存完成',__APP__.'/Article/index');
			}else{
				$User = M('article');
				$id = $this->_param('id');
				$info = $User->where('id='.$id)->select();//获取URL参数指定的记录
				$this->assign('info',$info);
				//获取栏目信息
				$User = M('class');
				$class = $User->where('status=1 and molds="article"')->order('isindex')->select();
				$this->assign('class',$class);
				$this->display();
			}
	}

	//直接审核文章
	public function update(){
		$User = M("article");
		$id = $this->_param('id');
		$status = $User->where('id='.$id)->getField('status');//取得当前状态
		$status = $status?"0":"1";//按当前状态取反
	    $User-> where('id='.$id)->setField('status',$status);//更新状态值
		if($User){
			$this->success('更改状态成功！');
			//$this->redirect('Article/index');
		}else{
            $this->success('更改状态失败！');
           }
   }

   	//删除核文章
	public function delete(){
		$User = M("article");
		$id = $this->_param('id');
	    $User-> where('id='.$id)->delete();//更新状态值
		if($User){
			$this->success('删除成功！');
		}else{
            $this->success('删除失败！');
           }
   }

//批量删除核文章
public function delete_batch(){
	$User = M("article");
	$id = $this->_param('id');//获取操作的id数组
	if($id){
		$User->where(array('id'=>array('in',$id)))->delete();//删除
			if($User){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！');
				}
		}else{
			$this->error('删除失败，没有获取到操作参数id！');
	}
}


}
<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Adminc\Controller;
use Think\Controller;
use Adminc\Model\AuthRuleModel;
use Adminc\Model\AuthGroupModel;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class AdmincController extends Controller {

   

    /**
     * 获取控制器菜单数组,二级菜单元素位于一级菜单的'_child'元素中
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final public function getMenus($controller=CONTROLLER_NAME){
        $menus  =   session('ADMIN_MENU_LIST.'.$controller);
        if(empty($menus)){
            // 获取主菜单
            $where['pid']   =   0;
            $where['hide']  =   0;
            if(!C('DEVELOP_MODE')){ // 是否开发者模式
                $where['is_dev']    =   0;
            }
            $menus['main']  =   M('Menu')->where($where)->order('sort asc')->field('id,title,url')->select();
            $menus['child'] =   array(); //设置子节点
            foreach ($menus['main'] as $key => $item) {
                // 判断主菜单权限
                if ( !IS_ROOT && !$this->checkRule(strtolower(MODULE_NAME.'/'.$item['url']),AuthRuleModel::RULE_MAIN,null) ) {
                    unset($menus['main'][$key]);
                    continue;//继续循环
                }
                if(strtolower(CONTROLLER_NAME.'/'.ACTION_NAME)  == strtolower($item['url'])){
                    $menus['main'][$key]['class']='current';
                }
            }

            // 查找当前子菜单
            $pid = M('Menu')->where("pid !=0 AND url like '%{$controller}/".ACTION_NAME."%'")->getField('pid');
            if($pid){
                // 查找当前主菜单
                $nav =  M('Menu')->find($pid);
                if($nav['pid']){
                    $nav    =   M('Menu')->find($nav['pid']);
                }
                foreach ($menus['main'] as $key => $item) {
                    // 获取当前主菜单的子菜单项
                    if($item['id'] == $nav['id']){
                        $menus['main'][$key]['class']='current';
                        //生成child树
                        $groups = M('Menu')->where(array('group'=>array('neq',''),'pid' =>$item['id']))->distinct(true)->getField("group",true);
                        //获取二级分类的合法url
                        $where          =   array();
                        $where['pid']   =   $item['id'];
                        $where['hide']  =   0;
                        if(!C('DEVELOP_MODE')){ // 是否开发者模式
                            $where['is_dev']    =   0;
                        }
                        $second_urls = M('Menu')->where($where)->getField('id,url');

                        if(!IS_ROOT){
                            // 检测菜单权限
                            $to_check_urls = array();
                            foreach ($second_urls as $key=>$to_check_url) {
                                if( stripos($to_check_url,MODULE_NAME)!==0 ){
                                    $rule = MODULE_NAME.'/'.$to_check_url;
                                }else{
                                    $rule = $to_check_url;
                                }
                                if($this->checkRule($rule, AuthRuleModel::RULE_URL,null))
                                    $to_check_urls[] = $to_check_url;
                            }
                        }
                        // 按照分组生成子菜单树
                        foreach ($groups as $g) {
                            $map = array('group'=>$g);
                            if(isset($to_check_urls)){
                                if(empty($to_check_urls)){
                                    // 没有任何权限
                                    continue;
                                }else{
                                    $map['url'] = array('in', $to_check_urls);
                                }
                            }
                            $map['pid']     =   $item['id'];
                            $map['hide']    =   0;
                            if(!C('DEVELOP_MODE')){ // 是否开发者模式
                                $map['is_dev']  =   0;
                            }
                            $menuList = M('Menu')->where($map)->field('id,pid,title,url,tip')->order('sort asc')->select();
                            $menus['child'][$g] = list_to_tree($menuList, 'id', 'pid', 'operater', $item['id']);
                        }
                    }
                }
            }
            session('ADMIN_MENU_LIST.'.$controller,$menus);
        }
        return $menus;
    }

    /**
     * 返回后台节点数据
     * @param boolean $tree    是否返回多维数组结构(生成菜单时用到),为false返回一维数组(生成权限节点时用到)
     * @retrun array
     *
     * 注意,返回的主菜单节点数组中有'controller'元素,以供区分子节点和主节点
     *
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    final protected function returnNodes($tree = true){
        static $tree_nodes = array();
        if ( $tree && !empty($tree_nodes[(int)$tree]) ) {
            return $tree_nodes[$tree];
        }
        if((int)$tree){
            $list = M('Menu')->field('id,pid,title,url,tip,hide')->order('sort asc')->select();
            foreach ($list as $key => $value) {
                if( stripos($value['url'],MODULE_NAME)!==0 ){
                    $list[$key]['url'] = MODULE_NAME.'/'.$value['url'];
                }
            }
            $nodes = list_to_tree($list,$pk='id',$pid='pid',$child='operator',$root=0);
            foreach ($nodes as $key => $value) {
                if(!empty($value['operator'])){
                    $nodes[$key]['child'] = $value['operator'];
                    unset($nodes[$key]['operator']);
                }
            }
        }else{
            $nodes = M('Menu')->field('title,url,tip,pid')->order('sort asc')->select();
            foreach ($nodes as $key => $value) {
                if( stripos($value['url'],MODULE_NAME)!==0 ){
                    $nodes[$key]['url'] = MODULE_NAME.'/'.$value['url'];
                }
            }
        }
        $tree_nodes[(int)$tree]   = $nodes;
        return $nodes;
    }


    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
     *
     * @param sting|Model  $model   模型名或模型实例
     * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
     * @author 朱亚杰 <xcoolcc@gmail.com>
     *
     * @return array|false
     * 返回数据集
     */
    protected function lists ($model,$where=array(),$order='',$field=true){
        $options    =   array();
        $REQUEST    =   (array)I('request.');
        if(is_string($model)){
            $model  =   M($model);
        }

        $OPT        =   new \ReflectionProperty($model,'options');
        $OPT->setAccessible(true);

        $pk         =   $model->getPk();
        if($order===null){
            //order置空
        }else if ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = '`'.$REQUEST['_field'].'` '.$REQUEST['_order'];
        }elseif( $order==='' && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);

        if(empty($where)){
            $where  =   array('status'=>array('egt',0));
        }
        if( !empty($where)){
            $options['where']   =   $where;
        }
        $options      =   array_merge( (array)$OPT->getValue($model), $options );
        $total        =   $model->where($options['where'])->count();

        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $page = new \Think\Page($total, $listRows, $REQUEST);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $this->assign('_total',$total);
        $options['limit'] = $page->firstRow.','.$page->listRows;

        $model->setProperty('options',$options);

        return $model->field($field)->select();
    }

    /**
     * 处理文档列表显示
     * @param array $list 列表数据
     * @param integer $model_id 模型id
     */
    protected function parseDocumentList($list,$model_id=null){
        $model_id = $model_id ? $model_id : 1;
        $attrList = get_model_attribute($model_id,false,'id,name,type,extra');
        // 对列表数据进行显示处理
        if(is_array($list)){
            foreach ($list as $k=>$data){
                foreach($data as $key=>$val){
                    if(isset($attrList[$key])){
                        $extra      =   $attrList[$key]['extra'];
                        $type       =   $attrList[$key]['type'];
                        if('select'== $type || 'checkbox' == $type || 'radio' == $type || 'bool' == $type) {
                            // 枚举/多选/单选/布尔型
                            $options    =   parse_field_attr($extra);
                            if($options && array_key_exists($val,$options)) {
                                $data[$key]    =   $options[$val];
                            }
                        }elseif('date'==$type){ // 日期型
                            $data[$key]    =   date('Y-m-d',$val);
                        }elseif('datetime' == $type){ // 时间型
                            $data[$key]    =   date('Y-m-d H:i',$val);
                        }
                    }
                }
                $data['model_id'] = $model_id;
                $list[$k]   =   $data;
            }
        }
        return $list;
    }
}

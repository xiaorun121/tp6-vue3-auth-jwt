<?php
declare (strict_types = 1);

namespace app\controller;
use app\model\School as ModelSchool;
use think\facade\Validate;
use think\Request;

class School
{
    /**
     * 显示资源列表
     * 【get】http://0608.cc/school
     * 【get】http://0608.cc/school?page=2
     * 
     * @return \think\Response
     */
    public function index(ModelSchool $school)
    {
        $pageSize = 3;
        $res = $school->getPage($pageSize);
        if ($res) {
            return json(['code' => 0, 'msg' => 'ok', 'res' => $res]);
        } else {
            return json(['code' => 1, 'msg' => 'no', 'res' => null]);
        }
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     * 【post】http://0608.cc/school?name=shbw&city=&num=10000
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request, ModelSchool $school)
    {
        // 接收数据
        $data['name'] = $request->param('name', '');
        $data['city'] = $request->param('city', '');
        $data['num'] = $request->param('num', '0');
        // 数据验证
        $rule = [
            'name' => 'require|max:30|min:2',
            'city' => 'require',
            'num'  => 'require'
        ];
        $message = [
            'name.require'  => '学校名称是必填项',
            'name.max'      => '学校名称最多30位',
            'name.min'      => '学校名称最少2位',
            'city.require'  => '学校所在城市是必填项',
            'num.require'   => '学校现有人数是必填项'
        ];
        // 粘贴来自于手册：验证->验证规则->方法定义
        $validate = Validate::rule($rule)->message($message);
        if (!$validate->check($data)) {
            return json(['code' => 1, 'msg' => $validate->getError(), 'res' => null]);
        }
        // 调用模型
        $res = $school->addOne($data);
        if ($res) {
            return json(['code' => 0, 'msg' => '添加成功', 'res' => $res]);
        } else {
            return json(['code' => 1, 'msg' => '添加失败', 'res' => $res]);
        }
    }

    /**
     * 显示指定的资源
     * 【get】http://0608.cc/school/3
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id, ModelSchool $school)
    {
        $where['id'] = $id;
        $res = $school->selOne($where);
        if ($res) {
            return json(['code' => 0, 'msg' => '查询成功', 'res' => $res]);
        } else {
            return json(['code' => 1, 'msg' => '查询失败', 'res' => $res]);
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     * 【put】http://0608.cc/school/3?name=bjbw3&city=bj3&num=30000
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id, ModelSchool $school)
    {
        // 接收数据
        $where['id'] = $id;
        $data['name'] = $request->param('name', '');
        $data['city'] = $request->param('city', '');
        $data['num'] = $request->param('num', '0');
        // 数据验证
        $rule = [
            'name' => 'require|max:30|min:2',
            'city' => 'require',
            'num'  => 'require'
        ];
        $message = [
            'name.require'  => '学校名称是必填项',
            'name.max'      => '学校名称最多30位',
            'name.min'      => '学校名称最少2位',
            'city.require'  => '学校所在城市是必填项',
            'num.require'   => '学校现有人数是必填项'
        ];
        // 粘贴来自于手册：验证->验证规则->方法定义
        $validate = Validate::rule($rule)->message($message);
        if (!$validate->check($data)) {
            return json(['code' => 1, 'msg' => $validate->getError(), 'res' => null]);
        }
        // 调用模型
        $res = $school->updOne($where, $data);
        if ($res) {
            return json(['code' => 0, 'msg' => '修改成功', 'res' => $res]);
        } else {
            return json(['code' => 1, 'msg' => '修改失败', 'res' => $res]);
        }
    }

    /**
     * 删除指定资源
     * 【delete】http://0608.cc/school/1
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id, ModelSchool $school)
    {
        $where['id'] = $id;
        $res = $school->delOne($where);
        if ($res) {
            return json(['code' => 0, 'msg' => '删除成功', 'res' => $res]);
        } else {
            return json(['code' => 1, 'msg' => '删除失败', 'res' => $res]);
        }
    }
}

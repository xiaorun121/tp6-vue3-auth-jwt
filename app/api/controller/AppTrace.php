<?php

declare(strict_types=1);

namespace app\api\controller;

use app\api\model\AppTrace as ModelAppTrace;
use app\BaseController;
use think\Request;
use think\facade\Request as RequestFacade;

use app\api\model\Chat;
use app\api\model\Admin;
use app\api\model\AppTraceUpdate;
use think\facade\Db;

class AppTrace extends BaseController
{

    // 状态
    protected $status_arr = ['已上架' => 1, '审核中' => 2, '待审核' => 3, '账号禁用' => 4, '分配中' => 5, '已暂停' => 6, '待验证' => 7, '已下架' => 8, '账号关联' => 9, '更新中' => 10, '其他' => 11];

    // 类型: 1：H5 2：原生 3:UNIAPP 4:H5+TP 5:IOS 6: Unity 7:Cocos
    protected $appType = ['H5' => 1, '原生' => 2, 'UNIAPP' => 3, 'H5+TP' => 4, 'IOS' => 5, 'Unity' => 6, 'Cocos' => 7];

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(ModelAppTrace $appTrace, Request $request)
    {
        $data = $request->param();

        $page = $data['page'];
        $pageSize = $data['pageSize'];
        $where = [];

        if (!empty($data['api_vps']) || !empty($data['app_name']) || !empty($data['app_password']) || !empty($data['app_type']) || !empty($data['b_project']) || !empty($data['bag_time']) || !empty($data['domain_url']) || !empty($data['package_name']) || !empty($data['status'])) {


            $logsData  = '搜索条件：<br>';

            if (!empty($data['api_vps'])) {
                $where['api_vps'] = $data['api_vps'];
                $logsData .= "接口服务器为：" . $data['api_vps'] . " <br>";
            }
            if (!empty($data['app_name'])) {
                $where[] = ['app_name', 'like', '%' . $data['app_name'] . '%'];
                $logsData .= "应用名为：" . $data['app_name'] . " <br>";
            }
            if (!empty($data['app_password'])) {
                $where['app_password'] = $data['app_password'];
                $logsData .= "密码为：" . $data['app_password'] . " <br>";
            }
            if (!empty($data['bag_time'])) {
                $where['bag_time'] = str_replace('-', '/', $data['bag_time']);
                $logsData .= "提包日期为：" . $data['bag_time'] . " <br>";
            }
            if (!empty($data['domain_url'])) {
                $where['domain_url'] = $data['domain_url'];
                $logsData .= "域名地址为：" . $data['domain_url'] . " <br>";
            }
            if (!empty($data['package_name'])) {
                $where['package_name'] = $data['package_name'];
                $logsData .= "包名为：" . $data['package_name'] . " <br>";
            }
            if (!empty($data['status'])) {
                $where['status'] = $this->status_arr[$data['status']];
                $logsData .= "状态为：" . $data['status'] . " <br>";
            }
            if (!empty($data['app_type'])) {
                $where['app_type'] = $this->appType[$data['app_type']];
                $logsData .= "类型为：" . $data['app_type'] . " <br>";
            }
            if (!empty($data['b_project'])) {
                $where['b_project'] = $data['b_project'];
                $logsData .= "跳转项目组为：" . $data['b_project'] . " <br>";
            }

            $this->logs->create([
                'title'   => '查询数据',
                'content' => $logsData,
                'user_id' => $this->uid,
                'ip'      => $request->ip()
            ]);
        }

        $result = $appTrace->where($where)->order('id desc')->paginate($pageSize, false, [
            'page' => $page
        ]);

        $resultInfo = $result->toArray();

        // if(empty($where)){
        // 跳转项目组
        $bProject = $appTrace::where("b_project <> ''")->field('b_project')->distinct(true)->select()->toArray();
        $resultInfo['bProject'] = $bProject;

        // 类型
        $appType = $appTrace::where("app_type <> ''")->field('app_type')->order('app_type asc')->distinct(true)->select()->toArray();
        $resultInfo['appType'] = $appType;

        // 状态 
        $status = $appTrace::where("status <> ''")->field('status')->order('status asc')->distinct(true)->select()->toArray();
        $resultInfo['status'] = $status;

        // }

        // chats
        $chat = new Chat();
        $chats = $chat->order('id asc')->field('chat_id')->select()->toArray();

        foreach ($chats as $key => $value) {
            $chats[$key]['lable'] = $value['chat_id'];
            $chats[$key]['value'] = $value['chat_id'];
            unset($chats[$key]['chat_id']);
        }

        // $chats =  json_encode($chats);
        $resultInfo['chat'] = $chats;

        $admin = new Admin();
        $admins = $admin->where('status', 1)->field('id,username')->order('id asc')->select()->toArray();
        $resultInfo['admin'] = $admins;


        if ($resultInfo) {

            if (empty($resultInfo['data'])) {
                return result(['page' => $page], 'query data not found', 210);
            }

            foreach ($resultInfo['data'] as $key => $value) {
                $resultInfo['data'][$key]['update_batch'] = getAppTraceUpdate($value['id']);
            }

            return result($resultInfo, 'query data success', 200);
        } else {
            return result(null, 'query data error', 0);
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
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(ModelAppTrace $appTrace, Request $request)
    {
        $data = $request->param();

        $result = $appTrace::create($data);

        if ($result) {

            $logsData = "新建数据：" . json_encode($data, JSON_UNESCAPED_UNICODE);

            $this->logs->create([
                'title'   => '新建数据',
                'content' => $logsData,
                'user_id' => $this->uid,
                'ip'      => $request->ip()
            ]);

            return result($data, 'save data success', 200);
        } else {
            return result(null, 'save data error', 0);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read(ModelAppTrace $appTrace, Request $request, $id)
    {
        $result = $appTrace::find($id)->toArray();

        if ($result) {

            $logsData = "查看数据【id： " . $id . "】 的数据" . json_encode($result, JSON_UNESCAPED_UNICODE);

            $this->logs->create([
                'title'   => '查看数据',
                'content' => $logsData,
                'user_id' => $this->uid,
                'ip'      => $request->ip()
            ]);

            return result($result, 'for Id query data success', 200);
        } else {
            return result($result, 'for Id query data error', 0);
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
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(ModelAppTrace $appTrace, Request $request, Admin $admin, $id)
    {
        $data  = $request->param('data');
        $field = $request->param('field');

        // $field 是否存在 radio select 操作
        if (isset($field)) {

            // 布尔类型 跳转开关 域名验证 HTTPS 启用状态 热更新开关
            if (is_bool($data)) {

                if ($data == true) {
                    $msgSuccess = $field . ': open';
                } else {
                    $msgSuccess = $field . ': close';
                }
            } else {
                // 数字类型  发布人员
                if (is_numeric($data)) {

                    $username = $admin->where(['id' => $data])->value('username');

                    $msgSuccess = '发布人员:' . $username;
                } else {
                    // 类型  状态
                    $msgSuccess = $field . ': ' . $data;
                }
            }

            $logsData = "更新数据【id： " . $id . "】 " . $msgSuccess;

            $msgError   = $field . ' save error';

            $result = $appTrace::update([$field => $data], ['id' => $id]);
        } else {
            $dataAll = $request->param();
            unset($dataAll['id']);

            // 启动事务
            Db::startTrans();
            try {
                //code...
                $result = $appTrace::update($dataAll, ['id' => $id]);

                if (!empty($dataAll['update_reason'])) {
                    AppTraceUpdate::create([
                        'app_id' => $id,
                        'update_batch' => getAppTraceUpdate($id) + 1,
                        'update_reason' => $dataAll['update_reason']
                    ]);


                    $this->logs::create([
                        'title'   => '更新更新原因',
                        'content' => "更新【id： " . $id . "】 的数据为：" . json_encode([
                            '更新批次为：' => getAppTraceUpdate($id),
                            '更新原因为：' => $dataAll['update_reason']
                        ], JSON_UNESCAPED_UNICODE),
                        'user_id' => $this->uid,
                        'ip'      => $request->ip()
                    ]);
                }


                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }

            $logsData = "更新数据【id： " . $id . "】 的数据" . json_encode($dataAll, JSON_UNESCAPED_UNICODE);

            $msgSuccess = 'data save success';
            $msgError   = 'data save error';
        }

        if ($result) {

            $this->logs->create([
                'title'   => '更新数据',
                'content' => $logsData,
                'user_id' => $this->uid,
                'ip'      => $request->ip()
            ]);

            return result(null, $msgSuccess, 200);
        } else {
            return result(null, $msgError, 0);
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(ModelAppTrace $appTrace, Request $request, $id)
    {
        $image = $appTrace->where(['id' => $id])->value('image');

        if (!empty($image)) {

            $imgUrl = public_path() . "storage/" . $image;

            if (file_exists($imgUrl)) {
                unlink($imgUrl);
            }
        }

        $result = $appTrace::destroy($id);

        if ($result) {

            $logsData = "删除数据【id： " . $id . "】 的数据";

            $this->logs->create([
                'title'   => '删除数据',
                'content' => $logsData,
                'user_id' => $this->uid,
                'ip'      => $request->ip()
            ]);

            return result(null, 'delete data success', 200);
        } else {
            return result(null, 'delete data error', 0);
        }
    }

    /**
     * 上传图片 
     */
    public function uploadImage(Request $request, Admin $admin)
    {
        $file = $request->file('file');

        $id = $request->param('id');

        $user_id = $request->param('user_id');
        $menu_auth_id = $request->param('menu_auth_id');

        // 获取当前用户的权限数据
        $authGroup = $admin::find($user_id)->rules->toArray();

        $rules = $authGroup['rules'];

        if (strpos(',' . $rules . ',', ',' . $menu_auth_id . ',') !== false) {
            $savename = \think\facade\Filesystem::disk('public')->putFile('topic', $file);

            if ($savename) {
                $savename = str_replace('\\', '/', $savename);

                $imgUrl = RequestFacade::domain() . "/storage/" . $savename;

                $logsData = "上传图片【id： " . $id . "】 的图片 <img src='" . $imgUrl . "'>";

                $this->logs->create([
                    'title'   => '上传图片',
                    'content' => $logsData,
                    'user_id' => $this->uid,
                    'ip'      => $request->ip()
                ]);

                return result($savename, '上传图片成功', 200);
            } else {
                return result(null, '上传图片失败', 0);
            }
        } else {
            return result(null, 'No permission', 0);
        }
    }

    /**
     * 删除图片
     */
    public function delImage(ModelAppTrace $appTrace, Request $request)
    {
        $imgName = $request->param('name');

        $id = $request->param('id');

        $imgUrl = public_path() . "storage/" . $imgName;

        try {
            if (file_exists($imgUrl)) {
                unlink($imgUrl);

                $msg = "删除图片成功";
                $code = 200;

                $logsData = "删除图片【id： " . $id . "】 的图片";
            } else {
                $msg = "图片不存在";
                $code = 0;

                $logsData = "删除图片【id： " . $id . "】 的图片，图片不存在";
            }

            $appTrace::update(['image' => ''], ['id' => $id]);

            $this->logs->create([
                'title'   => '删除图片',
                'content' => $logsData,
                'user_id' => $this->uid,
                'ip'      => $request->ip()
            ]);

            return result(null, $msg, $code);
        } catch (\Exception $e) {

            return result(null, $e->getMessage(), 0);
        }
    }

    // 一键复制
    public function isCopy(ModelAppTrace $appTrace, Request $request)
    {

        $id = $request->param('id');

        $data = $appTrace::where(['id' => $id])->find()->toArray();

        unset($data['id']);
        $data['status'] = "待审核";

        $result = $appTrace->create($data);

        if ($result) {

            $logsData = "一键复制【id： " . $id . "】 的数据";

            $this->logs->create([
                'title'   => '一键复制',
                'content' => $logsData,
                'user_id' => $this->uid,
                'ip'      => $request->ip()
            ]);

            return result($result, '一键复制成功', 200);
        } else {
            return result(null, '一键复制失败', 0);
        }
    }

    // 根据id查看更新原因
    public function readUpdateBatchToId(AppTraceUpdate $appTraceUpdate, Request $request)
    {
        $id = $request->param('id');

        $result = $appTraceUpdate->where(['app_id' => $id])->select()->toArray();

        if ($result) {

            $logsData = "根据id查看更新原因【id： " . $id . "】 的数据";

            $this->logs->create([
                'title'   => '查看更新原因',
                'content' => $logsData,
                'user_id' => $this->uid,
                'ip'      => $request->ip()
            ]);

            return result($result, '查看更新原因成功', 200);
        } else {
            return result(null, '查看更新原因失败', 0);
        }
    }
}

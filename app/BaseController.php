<?php
declare (strict_types = 1);

namespace app;

use app\api\model\Logs;
use think\App;
use think\exception\ValidateException;
use think\facade\Cache;
use think\Validate;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
// header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With,Content-Type,token');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    // 浏览器页面ajax跨域请求会请求2次，
    // 第一次会发送OPTIONS预请求,不进行处理，直接exit返回，
    // 但因为下次发送真正的请求头部有带token，
    // 所以这里设置允许下次请求头带token否者下次请求无法成功
    header('Access-Control-Allow-Headers:x-requested-with,content-type,token');
    exit("ok");
}

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 用户id 
     */ 
    protected $uid;

    /**
     * logs 日志
     */ 
    protected $logs;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;
        $this->uid = Cache::get('uid');
        $this->logs = new Logs();

        // 控制器初始化
        $this->initialize();

    }

    // 初始化
    protected function initialize()
    {}

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

}

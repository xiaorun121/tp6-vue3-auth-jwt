import axios from 'axios';
import { Message,MessageBox } from 'element-ui';
import router from '@/router'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common["Content-Type"] = "application/json;charset=UTF-8"
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.baseURL = 'http://www.tp6.com/api/v1/';
// axios.defaults.proxy = {
//      host: 'www.tp6.com',
//      port: 443,
// };
// 添加请求拦截器
axios.interceptors.request.use(config => {
    config.headers['Authorization'] = sessionStorage.getItem('token');
    return config;
}, error => {
    return Promise.reject(error);
});
// 添加响应拦截器
axios.interceptors.response.use(res => {
    switch (res.data.ret) {
        case 201:
            sessionStorage.setItem('token', res.data.token);
            Message.success({
                message: '登录成功',
                duration: 3000,
                onClose: function () {
                    router.push('/schoolList');
                }
            });
            break;
        default:
            // Message.success(res.data.msg);
            // break;
    }
    return res;
}, error => {
    // console.log(error.response.status);
    switch(error.response.status){
        case 401:
            sessionStorage.removeItem('token');
            Message.error({
                message: error.response.data.msg,
                duration: 3000,
                onClose: function () {
                    router.push('/login');
                }
            });
            break;
        case 500:
            Message.error({
                message: error.response.data.message,
                duration: 3000,
                onClose: function () {
                    router.push('/login');
                }
            });
            break;
        default:
            Message.error('网络错误，请求失败');
            break;
    }
    return Promise.reject(error);
});



export default axios;
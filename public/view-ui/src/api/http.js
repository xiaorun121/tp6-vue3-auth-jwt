import axios from 'axios';
import router from '../router';

// 创建 axios 实例
const service = axios.create({
    baseURL: 'http://www.tp6.com/api/v1/', // 设置请求的基础 URL
    headers: {
        'Content-Type': 'application/json',
    },
    timeout: 10000 // 设置请求超时时间
});

// 请求拦截器
service.interceptors.request.use(
    config => {
        // 在发送请求之前做些什么，例如添加请求头、设置 token 等
        const token = sessionStorage.getItem('token');
        if (token) {
            config.headers['Authorization'] = token;
        } else {
            sessionStorage.clear();
            router.push('/login');
        }

        return config;
    },
    error => {
        // 处理请求错误
        console.error('请求错误:', error); // 例如输出错误信息到控制台
        return Promise.reject(error);
    }
);

// 响应拦截器
service.interceptors.response.use(
    response => {
        // 对响应数据做点什么，例如解析 JSON 数据、处理错误码等
        switch (response.data.code) {

            case 201:
                sessionStorage.setItem('token', response.data.data.token);
                sessionStorage.setItem('userinfo', JSON.stringify(response.data.data.userinfo));
                if(response.data.data.authGroup){
                    sessionStorage.setItem('rules', JSON.stringify(response.data.data.authGroup.rules));
                }
                break;
            default:
            // this.$Message.success(res.data.msg);
            // break;
        }

        return response;
    },
    error => {
        // 处理响应错误
        console.error('响应错误:', error); // 例如输出错误信息到控制台

        switch(error.response.status){
            case 401:
                sessionStorage.clear();
                router.push('/login');
                break;
            case 500:
                break;
            case 204:
                break;    
            default:

                break;
        }  

        return Promise.reject(error);
    }
);

export default service;
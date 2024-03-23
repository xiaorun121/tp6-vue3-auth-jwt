import http from './http';
let api = {
    Admin: {
        login:(param) => {
            return http.post('login',param);
        },
        user:() => {
            return http.get('user');
        }
    },
    // 菜单
    Menu: {
        // 查询数据
        index:(param) => {
            return http.get('menu',{params:param});
        },
        // 保存数据
        save:(param) => {
            return http.post('menu',param);
        }
    }
}
export default {
     install: Vue => {
         Vue.api = api;
         Vue.prototype.$api = api;
     }
}
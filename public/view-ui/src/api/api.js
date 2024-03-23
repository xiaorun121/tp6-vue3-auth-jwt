import http from './http';
let api = {
    // 管理员
    Admin: {
        login:(param) => {
            return http.post('login',param);
        },
        user:() => {
            return http.get('user');
        },
        // 查询数据
        index:(param) => {
            return http.get('admin',{params:param});
        },
        // 根据id查询详情
        read:(id) => {
            return http.get('admin/'+id);
        },
         // 保存新建的资源
        save:(param) => {
            return http.post('admin',param);
        },
        // 保存更新的资源
        update:(id,param) => {
            return http.put('admin/'+id,{data:param})
        },
        // 删除
        delete:(id) => {
            return http.delete('admin/'+id)
        },
        // 重置密码
        resetPassword:(id) => {
            return http.post('resetPassword',id);
        },
        // 修改管理员状态
        saveAdminStatus:(param) => {
            return http.post('saveAdminStatus',param);
        },
        // 修改管理员是否是超级管理员
        saveAdminIsAdmin:(param) => {
            return http.post('saveAdminIsAdmin',param);
        },
        // 验证权限
        checkAuth:(param) => {
            return http.post('checkAuth',param)
        },
        // 判断是否有按钮权限
        checkIsAuthToButton:(param) => {
            return http.post('checkIsAuthToButton',param)
        },
        // 获取用户的规则数据rules
        getUserToRules:(param) => {
            return http.post('getUserToRules',param)
        },
        // 获取发布人员
        getPublish:() => {
            return http.get('getPublish')
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
        },
        // 更新是否是菜单状态
        update:(id,param) => {
            return http.put('menu/'+id,{data:param})
        },
        // 删除菜单
        delete:(id) => {
            return http.delete('menu/'+id)
        },
        // 删除选择的菜单
        deleteMenuForIds:(ids) => {
            return http.post('deleteMenuForIds',ids);
        },
        // 查看
        read:(id) => {
            return http.get('menu/'+id);
        },
        // 获取get_tree数据
        getTree:() => {
            return http.get('getTree');
        }
    },
    // 配置管理
    Website: {
        // 查询数据
        getWebsite:() => {
            return http.get('getWebsite')
        },
        // 保存数据
        saveWebsite:(param) => {
            return http.post('saveWebsite',param)
        }
    },
    // 用户组表
    AuthGroup: {
        // 查询数据
        index:() => {
            return http.get('authGroup');
        },
        // 更新状态
        update:(id,param) => {
            return http.put('authGroup/'+id,{data:param})
        },
        // 保存新建的资源
        save:(param) => {
            return http.post('authGroup',param)
        },
        // 显示创建资源表单页
        read:(id) => {
            return http.get('authGroup/'+id)
        },
        // 删除
        delete:(id) => {
            return http.delete('authGroup/'+id)
        }
    },
    // 讨论组
    Chat: {
        // 查询数据
        index:() => {
            return http.get('chat');
        },
        // 更新状态
        update:(id,param) => {
            return http.put('chat/'+id,{data:param})
        },
        // 保存新建的资源
        save:(param) => {
            return http.post('chat',param)
        },
        // 显示创建资源表单页
        read:(id) => {
            return http.get('chat/'+id)
        },
        // 删除
        delete:(id) => {
            return http.delete('chat/'+id)
        }
    },
    // App 数据跟踪
    AppTrace: {
        // 查询数据
        index:(param) => {
            return http.get('appTrace',{params:param});
        },
        // 更新状态
        update:(id,param) => {
            return http.put('appTrace/'+id,param)
        },
        // 保存新建的资源
        save:(param) => {
            return http.post('appTrace',param)
        },
        // 显示创建资源表单页
        read:(id) => {
            return http.get('appTrace/'+id)
        },
        // 删除
        delete:(id) => {
            return http.delete('appTrace/'+id)
        },
        // 删除图片
        delImage:(param) => {
            return http.post('delImage', param)
        },
        // 一键复制
        isCopy:(id) => {
            return http.post('isCopy', {id:id})
        },
        // 根据id查看更新原因
        readUpdateBatchToId:(id) => {
            return http.post('readUpdateBatchToId',{id:id})
        }
    },
    // 日志
    Logs: {
        // 查询数据
        index:(param) => {
            return http.get('logs',{params:param});
        }
    },
    // 实时更新数据
    LiveUpdateLogs:{
        // 查询数据
        index:(param) => {
            return http.get('liveUpdateLogs',{params:param});
        }
    },
    // 项目组数据汇总
    ProjectGather:{
        // 查询数据
        index:(param) => {
            return http.get('projectGather',{params:param});
        },
        // 显示项目组数据列表数据
        showProject:(param) => {
            return http.get('showProject',{params:param});
        }
    },
    // 采集后台管理
    Backend: {
        // 查询数据
        index:(param) => {
            return http.get('backend',{params:param});
        },
        // 更新状态
        update:(id,param) => {
            return http.put('backend/'+id,{data:param})
        },
        // 保存新建的资源
        save:(param) => {
            return http.post('backend',param)
        },
        // 显示创建资源表单页
        read:(id) => {
            return http.get('backend/'+id)
        },
        // 删除
        delete:(id) => {
            return http.delete('backend/'+id)
        }
    },
    // 采集数据
    Gather: {
        // 查询数据
        index:(param) => {
            return http.get('gather',{params:param});
        },
        // 更新状态
        update:(id,param) => {
            return http.put('gather/'+id,{data:param})
        },
        // 保存新建的资源
        save:(param) => {
            return http.post('gather',param)
        },
        // 显示创建资源表单页
        read:(id) => {
            return http.get('gather/'+id)
        },
        // 删除
        delete:(id) => {
            return http.delete('gather/'+id)
        }
    }
    
}
export default api
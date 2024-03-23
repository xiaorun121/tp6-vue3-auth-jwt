<style>
.demo-split {
    height: 50px;
    /* border: 1px solid #dcdee2; */
}

.demo-split-pane {
    padding: 10px;
}

.ivu-table-sort {
    width: 18px !important;
}

.ivu-page-options-sizer {
    width: 100px;
}
</style>
<template>
    <div v-if="haveRule">
        <Space direction="vertical" class="demo-split">
            <Space wrap class="demo-split-pane">
                <Icon type="md-add-circle" size="24" color="#2db7f5" title="add" @click="saveAdmin(0, 47)" v-if="isSubstring(47, rules)"/>
            </Space>
        </Space>
        <Modal v-model="modalSaveAdmin" ref="refTaskFlowModal" title="saveAdmin" :loading="loading"
            @on-ok="AsyncSaveAuthGroup('formValidate')" @on-cancel="cancelModel('formValidate')" loading="true"
            lock-scroll="true" closable="false" :scrollable="false" v-if="formValidate">
            <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="140">
                <FormItem label="isAdmin">
                    <Switch v-model="formValidate.is_admin" size="large" @on-change="changeIsAdmin">
                        <template #open>
                            <span>On</span>
                        </template>
                        <template #close>
                            <span>Off</span>
                        </template>
                    </Switch>
                </FormItem>
                <FormItem label="username" prop="username">
                    <Input v-model="formValidate.username" placeholder="Enter something..."></Input>
                </FormItem>
                <FormItem label="fullname" prop="fullname">
                    <Input v-model="formValidate.fullname" placeholder="Enter something..."></Input>
                </FormItem>
                <FormItem label="phone" prop="phone">
                    <Input v-model="formValidate.phone" placeholder="Enter something..."></Input>
                </FormItem>
                <FormItem label="password" prop="password" v-if="modelType == 'add'">
                    <Input v-model="formValidate.password" placeholder="Enter something..."></Input>
                </FormItem>
                <FormItem label="email" prop="email">
                    <Input v-model="formValidate.email" placeholder="Enter something..."></Input>
                </FormItem>
                <FormItem label="auth_group" prop="auth_group_id" v-if="!is_admin_switch">
                    <Select v-model="formValidate.auth_group_id">
                        <Option v-for="auth in authGroup" :value="auth.id" :key="auth.id">{{ auth.title }}</Option>
                    </Select>
                </FormItem>

                <FormItem label="sex" prop="sex">
                    <RadioGroup v-model="formValidate.sex">
                        <Radio label="男"></Radio>
                        <Radio label="女"></Radio>
                    </RadioGroup>
                </FormItem>

                <FormItem label="Status">
                    <Switch v-model="formValidate.status" size="large">
                        <template #open>
                            <span>On</span>
                        </template>
                        <template #close>
                            <span>Off</span>
                        </template>
                    </Switch>
                </FormItem>
                <Input type="hidden" v-model="formValidateID"></Input>
            </Form>
        </Modal>
        <Table border max-height="700" :columns="columns" :data="data" size="small" style="margin: 10px;">
            <template #username="{ row }">
                <strong>{{ row.username }}</strong>
            </template>
            <template #is_admin="{ row }">
                <Switch :model-value="row.is_admin" size="small" :before-change="(value) => changeSwitchIsAdmin(row.id, row.is_admin, 254)">
                </Switch>
            </template>
            <template #sex="{ row }">
                <strong>{{ row.sex }}</strong>
            </template>
            <template #status="{ row }">
                <Switch :model-value="row.status" size="small" :before-change="(value) => changeSwitchStatus(row.id, row.status, 255)">
                </Switch>
            </template>
            <template #action="{ row, index }">
                <Icon type="ios-finger-print" size="24" color="#4e52cd" title="resetPwd"
                    @click="clickResetPassword(row.id, 253)" v-if="isSubstring(253, rules)"/>
                <Icon type="ios-create" size="24" color="#47cb89" title="edit" @click="saveAdmin(row.id, 48)" v-if="isSubstring(48, rules)"/>
                <Icon type="ios-trash" size="24" color="red" title="del" @click="clickDelAdmin(row.id, 49)" v-if="isSubstring(49, rules)"/>
            </template>
        </Table>
        <Page :total="total" :page-size="pageSize" @on-next="nextPage" @on-prev="prevPage" @on-change="changePage"
            @on-page-size-change="pageSizeChange" show-sizer show-total show-elevator v-show="total > 10"
            style="margin: 10px;" />
    </div>
    <div v-else>
        <NoAuth />
    </div>
</template>

<script>


export default {
    data() {
        return {

            // 是否有权限
            haveRule: true,

            // 是否是管理员
            ISAdmin: false,

            // 权限 是管理员 则显示未空  不是管理员 则获取权限数据
            rules: '',

            // table
            columns: [
                {
                    title: 'ID',
                    width: 80,
                    key: 'id',
                    fixed: 'left',
                    sortable: true,
                    align: 'center'
                },
                {
                    title: 'is_admin',
                    key: 'is_admin',
                    slot: 'is_admin',
                    sortable: true,
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'authGroup',
                    key: 'auth_group_id',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'username',
                    key: 'username',
                    slot: 'username',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'fullname',
                    key: 'fullname',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'password',
                    key: 'password',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'sex',
                    key: 'sex',
                    slot: 'sex',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'phone',
                    key: 'phone',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'email',
                    key: 'email',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'login_times',
                    key: 'login_times',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'login_ip',
                    key: 'login_ip',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'last_login_ip',
                    key: 'last_login_ip',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'login_time',
                    key: 'login_time',
                    width: 150,
                    align: 'center'
                },

                {
                    title: 'last_login_time',
                    key: 'last_login_time',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'status',
                    key: 'status',
                    slot: 'status',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'CreateTime',
                    key: 'create_time',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'UpdateTime',
                    key: 'update_time',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'Action',
                    slot: 'action',
                    width: 140,
                    align: 'center',
                    fixed: 'right'
                }
            ],
            // table data
            data: [],

            // page
            total: 0,
            pageSize: 10,
            pageSizeOpts: 10,
            page: 1,

            // model
            loading: true,
            formValidateID: 0,
            formValidate: {},
            modalSaveAdmin: false,
            authGroup: {},
            modelType: 'add',
            is_admin_switch: false,

            // validate
            ruleValidate: {
                username: [
                    { required: true, message: 'The username cannot be empty', trigger: 'blur' }
                ],
                fullname: [
                    { required: true, message: 'The fullname cannot be empty', trigger: 'blur' }
                ],
                phone: [
                    { required: true, message: 'The phone cannot be empty', trigger: 'blur' }
                ],
                password: [
                    { required: true, message: 'The password cannot be empty', trigger: 'blur' }
                ],
                access_token: [
                    { required: true, message: 'The access_token cannot be empty', trigger: 'blur' }
                ],
                email: [
                    { required: true, message: 'The email cannot be empty', trigger: 'blur' }
                ],
                auth_group_id: [
                    { required: true, message: 'The auth_group cannot be empty', trigger: 'change', type: 'number' }
                ]
            }

        }
    },
    created() {
        // 获取是否是管理员权限
        const isAdmin = JSON.parse(sessionStorage.getItem('userinfo'))['isAdmin'];
        this.ISAdmin = isAdmin;

        if (isAdmin == true) {
            const admin = JSON.parse(sessionStorage.getItem('admin'))
            if (admin) {
                this.data = admin.data;
                this.total = admin.total;
                this.pageSize = admin.per_page;

            } else {
                this.getUser();
            }
        } else {
            // 获取当前路由的参数menu_id 
            const menu_id = this.$router.currentRoute._value.query.menu_id;
            this.checkAuth(menu_id);

            // 判断是否有当前用户的规则数据  rules
            const rules = sessionStorage.getItem('rules');
            if (rules) {
                this.rules = rules;
            } else {
                // 获取用户的规则数据
                this.getUserToRules();
            }
        }

    },
    methods: {
        // 来判断一个当前点击的菜单按钮是否存在于当前用户的规则里
        isSubstring(menu_id, rules) {
            if (this.ISAdmin == true) {
                return true;
            } else {
                // 将 rules 字符串按逗号分隔为数组
                const rulesArray = rules.split(',').map(item => parseInt(item.trim()));

                // 检查 menu_id 是否包含在 rulesArray 中
                return rulesArray.includes(parseInt(menu_id));
            }
        },
        // 获取用户的规则数据rules
        async getUserToRules() {
            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            await that.$api.Admin.getUserToRules({ user_id: user_id }).then(function (response) {
                if (response.data.code == 200) {
                    that.rules = response.data.data;
                    sessionStorage.setItem('rules', response.data.data)
                }
            }).catch(function (error) {
                console.log(error);
            });
        },
        // 查询当前窗口是否有权限
        async checkAuth(menu_id) {
            const that = this;
            const userId = JSON.parse(sessionStorage.getItem('userinfo'))['user_id']
            await that.$api.Admin.checkAuth({ id: menu_id, user_id: userId }).then(function (response) {
                if (response.data.code == 200) {
                    console.log(response.data.msg);
                    that.haveRule = true;
                    that.getUser();
                } else {
                    console.log(response.data.msg);
                    that.haveRule = false;
                }
            }).catch(function (error) {
                console.log(error)
            })
        },
        // 改变是否是管理员的状态 选择了是管理员 auth_group就不显示  否 则显示 
        changeIsAdmin(is_admin) {
            if (is_admin == true) {
                this.is_admin_switch = true
            } else {
                this.is_admin_switch = false
            }
        },
        // 获取用户
        async getUser() {
            const that = this;
            const page = that.page;
            const pageSize = that.pageSize;
            await that.$api.Admin.index({ page: page, pageSize: pageSize }).then(function (response) {
                if (response.data.code == 200) {
                    that.data = response.data.data.data;
                    that.total = response.data.data.total;
                    that.pageSize = response.data.data.per_page;
                    sessionStorage.setItem('admin', JSON.stringify(response.data.data))
                } else if (response.data.code == 210) {
                    console.log(response)
                    that.page = (response.data.data.page > 1) ? (response.data.data.page - 1) : 1;
                    if (that.page == 1) {
                        that.data = [];
                    } else {
                        that.getUser()
                    }
                } else {
                    console.log(response.data.msg);
                }
            }).catch(function (err) {
                console.log(err);
            });
        },
        // add / read Admin
        async saveAdmin(id, menu_auth_id) {

            const that = this;

            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.modalSaveAdmin = true;

                        // 获取用户组的数据
                        const authGroup = JSON.parse(sessionStorage.getItem('authGroup'));

                        if (authGroup) {
                            that.authGroup = authGroup;
                        } else {
                            that.getAuthGroup();
                        }
                        // read
                        if (id > 0) {
                            that.modelType = 'read';
                            that.getAdminToId(id)
                        } else {
                            that.formValidateID = 0;
                            that.modelType = 'add';
                        }
                    }
                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error);
                });

            } else {
                that.modalSaveAdmin = true;

                // 获取用户组的数据
                const authGroup = JSON.parse(sessionStorage.getItem('authGroup'));

                if (authGroup) {
                    that.authGroup = authGroup;
                } else {
                    that.getAuthGroup();
                }
                // read
                if (id > 0) {
                    that.modelType = 'read';
                    that.getAdminToId(id)
                } else {
                    that.formValidateID = 0;
                    that.modelType = 'add';
                }
            }

        },
        // 获取用户组的数据
        async getAuthGroup() {
            const that = this;
            await that.$api.AuthGroup.index().then(function (response) {
                if (response.data.code == 200) {

                    sessionStorage.setItem('authGroup', JSON.stringify(response.data.data.data));

                    that.authGroup = JSON.parse(sessionStorage.getItem('authGroup'));
                }
            });
        },
        // 查询当前的管理员信息
        async getAdminToId(id) {
            const that = this;
            await that.$api.Admin.read(id).then(function (response) {
                if (response.data.code == 200) {
                    that.formValidate = response.data.data;
                    if (response.data.data.is_admin == true) {
                        that.is_admin_switch = true
                    }
                    that.formValidateID = id;
                } else {
                    that.$Message.error(response.data.msg);
                }
            }).catch(function (error) {
                console.log(error);
            });
        },
        // 点击删除管理员
        async clickDelAdmin(id, menu_auth_id) {
            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.delAdmin(id)
                    }
                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error);
                });

            }else{
                that.delAdmin(id);
            }

        },
        // 删除管理员
        async delAdmin(id){
            const that = this;
            await that.$Modal.confirm({
                title: 'Del',
                content: 'Are you sure you want to delete it?',
                onOk: function () {

                    that.$api.Admin.delete(id).then(function (response) {

                        that.$Message.success(response.data.msg);

                        if (response.data.code == 200) {
                            that.getUser()
                        }

                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error);
                    });
                }
            })
        },
        // 提交数据
        async saveAdminData(formData) {
            const that = this;
            const formValidateID = that.formValidateID;
            if (formValidateID > 0) {
                // edit
                await that.$api.Admin.update(formValidateID, formData).then(function (response) {

                    if (response.data.code == 200) {

                        that.$Message.success(response.data.msg);

                        setTimeout(() => {

                            that.formValidateID = 0;
                            that.formValidate = {};
                            that.$refs['formValidate'].resetFields();

                            that.getUser();

                        }, 2000)
                    } else {
                        that.$Message.error(response.data.msg);
                        that.$refs['formValidate'].resetFields();
                    }

                }).catch(function (err) {
                    that.$Message.error('Network error, request failed');
                });
            } else {
                // add
                await that.$api.Admin.save(formData).then(function (response) {
                    if (response.data.code == 200) {

                        that.$Message.success(response.data.msg);

                        setTimeout(() => {

                            that.formValidate = {};
                            that.$refs['formValidate'].resetFields();

                            that.getUser();

                        }, 1000)

                    } else {
                        that.$Message.error(response.data.msg);
                        that.$refs['formValidate'].resetFields();

                    }

                }).catch(function (err) {
                    that.$Message.error('Network error, request failed');
                });
            }
        },
        // 验证数据
        AsyncSaveAuthGroup(name) {
            const that = this;
            that.$refs[name].validate((valid) => {
                if (valid) {
                    setTimeout(() => {

                        that.modalSaveAdmin = false;

                        // 提交数据
                        that.saveAdminData(that.formValidate)

                    }, 1000)
                } else {
                    that.loading = false;
                    setTimeout(() => {
                        that.loading = true;
                    }, 10);
                }
            })
        },
        // 关掉model
        cancelModel(name) {
            this.formValidate = {};
            this.is_admin_switch = false;
            this.$refs[name].resetFields();
        },
        // page 
        // 页码改变的回调，返回改变后的页码
        changePage(page) {
            var that = this;
            that.page = page;
            that.getUser();
        },
        // 切换下一页时触发，返回切换后的页码
        nextPage(page) {
            var that = this;
            that.page = page;
            that.getUser();
        },
        // 切换上一页时触发，返回切换后的页码
        prevPage(page) {
            var that = this;
            that.page = page;
            that.getUser();
        },
        // 切换每页条数时的回调，返回切换后的每页条数 默认10
        pageSizeChange(pageSize) {
            var that = this;
            that.pageSize = pageSize;
            that.page = 1;
            that.getUser();
        },
        // 改变是否是管理员的状态
        changeSwitchIsAdmin(id, isAdmin, menu_auth_id) {

            const that = this;
            const is_admin = (isAdmin === true ? false : true);
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            that.formValidateID = id;
            return new Promise((resolve) => {
                if (that.ISAdmin == false) {
                    that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                        if (response.data.code == 0) {
                            that.$Message.error(response.data.msg);
                            return false;
                        } else {
                            that.saveAdminIsAdmin(is_admin);
                            resolve();
                        }
                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error);
                    });

                }else{
                    that.saveAdminIsAdmin(is_admin);
                    resolve();
                }
            })
            
            
        },
        // 修改状态
        async saveAdminIsAdmin(formData) {
            const that = this;
            await that.$api.Admin.saveAdminIsAdmin({ id: that.formValidateID, is_admin: formData }).then(function (response) {
                if (response.data.code == 200) {
                    that.$Message.success(response.data.msg);

                    setTimeout(() => {
                        that.getUser();
                    }, 1000);
                }
            }).catch(function (error) {
                that.$Message.error('Network error, request failed');
            })
        },
        // 改变状态
        changeSwitchStatus(id, isStatus, menu_auth_id) {
            const status = (isStatus === true ? false : true);
            const that = this;
            that.formValidateID = id;

            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];

            return new Promise((resolve) => {
                if (that.ISAdmin == false) {
                    that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                        if (response.data.code == 0) {
                            that.$Message.error(response.data.msg);
                            return false;
                        } else {
                            that.saveAdminStatus(status);
                            resolve();
                        }
                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error);
                    });

                }else{
                    that.saveAdminStatus(status);
                    resolve();
                }
            })
            

        },
        // 修改状态
        async saveAdminStatus(formData) {
            const that = this;
            await that.$api.Admin.saveAdminStatus({ id: that.formValidateID, status: formData }).then(function (response) {
                if (response.data.code == 200) {
                    that.$Message.success(response.data.msg);

                    setTimeout(() => {
                        that.getUser();
                    }, 1000);

                }
            }).catch(function (error) {
                that.$Message.error('Network error, request failed');
            })
        },
        // 点击重置密码
        async clickResetPassword(uid, menu_auth_id) {
            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.resetPassword(uid)
                    }
                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error);
                });

            }else{
                that.resetPassword(uid)
            }

        },
        //  重置密码
        async resetPassword(uid) {
            const that = this;
            await that.$Modal.confirm({
                title: 'Del',
                content: 'Are you sure you want to reset it password?',
                onOk: function () {

                    that.$api.Admin.resetPassword({ id: uid }).then(function (response) {

                        if (response.data.code == 200) {
                            that.$Message.success(response.data.msg);
                            that.getUser();
                        } else {
                            that.$Message.error(response.data.msg);
                        }

                    }).catch(function (err) {
                        that.$Message.error('Network error, request failed');
                    });
                }
            })
        },
    }
}

</script>
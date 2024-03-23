<style>
.demo-split {
    height: 50px;
    /* border: 1px solid #dcdee2; */
}

.demo-split-pane {
    padding: 10px;
}

.custom-modal {
    height: 700px;
    /* 设置模态框的高度为 400px */
    overflow-y: auto;
    /* 当内容溢出时，垂直滚动 */
    align-items: center;
    justify-content: center;
}

.custom-modal::-webkit-scrollbar {
    display: none;
    /*隐藏滚动条*/
}

.ivu-modal-header {
    position: sticky;
    top: 0;
    z-index: 999999;
    background-color: #fff;
}

.ivu-modal-footer {
    position: sticky;
    bottom: 0;
    z-index: 999999;
    background-color: #fff;
}
</style>
<template>
    <div v-if="haveRule">
        <Space direction="vertical" class="demo-split">
            <Space wrap class="demo-split-pane">
                <Icon type="md-add-circle" size="24" color="#2db7f5" title="add" @click="addAuthGroup(44)" v-if="isSubstring(44, rules)" />
            </Space>
        </Space>
        <Modal v-model="modalAddAuthGroup" ref="refTaskFlowModal" title="addAuthGroup" :loading="loading"
            @on-ok="AsyncSaveAuthGroup('formValidate')" @on-cancel="cancelModel" loading="true" lock-scroll="true"
            closable="true" :scrollable="false" v-if="formValidate" :class="'custom-modal'">
            <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="100">
                <FormItem label="Title" prop="title">
                    <Input v-model="formValidate.title" placeholder="Enter something..."></Input>
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
                <FormItem label="Rules">
                    <Tree :data="menuList" ref="tree" multiple show-checkbox @on-check-change="clickTree"
                        :check-strictly="true" :check-directly="true"></Tree>
                </FormItem>
                <Input type="hidden" v-model="formValidateID"></Input>
            </Form>
        </Modal>
        <Table border stripe max-height="700" ref="selection" :columns="columns" :data="data" size="small"
            style="margin: 10px;">
            <template #title="{ row }">
                <strong>{{ row.title }}</strong>
            </template>
            <template #status="{ row }">
                <Switch :model-value="row.status" size="small" :before-change="(value) => clickChangeSwitch(row.id, row.status, 50)"></Switch>
            </template>
            <template #action="{ row, index }">
                <Icon type="ios-create" size="24" color="#47cb89" title="edit" @click="editAuthGroup(row.id, 45)" v-if="isSubstring(45, rules)"/>
                <Icon type="ios-trash" size="24" color="red" title="del" @click="clickDelAuthGroup(row.id, 46)" v-if="isSubstring(46, rules)"/>
            </template>
        </Table>
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

            // model
            loading: true,
            modalAddAuthGroup: false,
            formValidateID: 0,
            formValidate: {
                title: '',
                status: false
            },
            ruleValidate: {
                title: [
                    { required: true, message: 'The title cannot be empty', trigger: 'blur' }
                ]
            },
            menuList: [

            ],

            // table
            columns: [
                {
                    title: 'ID',
                    width: 60,
                    key: 'id'
                },
                {
                    title: 'Title',
                    width: 250,
                    slot: 'title'
                },
                {
                    title: 'Status',
                    slot: 'status',
                    width: 76
                },
                {
                    title: 'Rules',
                    key: 'rules',
                },
                {
                    title: 'CreateTime',
                    key: 'create_time',
                    width: 150
                },
                {
                    title: 'UpdateTime',
                    key: 'update_time',
                    width: 150
                },
                {
                    title: 'Action',
                    slot: 'action',
                    width: 110,
                    align: 'center'
                }
            ],
            // table data
            data: [],
        }
    },
    created() {
        // 获取是否是管理员权限
        const isAdmin = JSON.parse(sessionStorage.getItem('userinfo'))['isAdmin'];
        this.ISAdmin = isAdmin;

        if (isAdmin == true) {

            const authGroup = sessionStorage.getItem('authGroup');

            if (authGroup) {
                this.data = JSON.parse(authGroup)
            } else {
                this.getAuthGroup();
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
                    that.haveRule = true;
                    that.getAuthGroup();
                } else {
                    that.haveRule = false;
                }
            }).catch(function (error) {
                console.log(error)
            })
        },

        // 点击选中Rules
        clickTree(arr, index) {
            // 选中
            if (index.checked === true) {
                if (index.children) {
                    for (let i = 0; i < index.children.length; i++) {
                        // 判断三级菜单是否存在
                        if (index.children[i].children) {
                            for (let j = 0; j < index.children[i].children.length; j++) {
                                index.children[i].children[j].checked = true;
                                index.children[i].checked = true
                            }
                        } else {
                            index.children[i].checked = true
                        }

                    }
                }
            } else {  // 取消选中
                if (index.children) {
                    for (let i = 0; i < index.children.length; i++) {
                        // 判断三级菜单是否存在
                        if (index.children[i].children) {
                            for (let j = 0; j < index.children[i].children.length; j++) {
                                index.children[i].children[j].checked = false;
                                index.children[i].checked = false
                            }
                        } else {
                            index.children[i].checked = false
                        }

                    }
                }
            }

        },
        // 查询数据
        async getAuthGroup() {
            const that = this;
            await that.$api.AuthGroup.index().then(function (response) {
                if (response.data.code == 200) {

                    sessionStorage.setItem('authGroup', JSON.stringify(response.data.data.data));
                    sessionStorage.setItem('menuList', JSON.stringify(response.data.data.menu));

                    that.data = JSON.parse(sessionStorage.getItem('authGroup'));
                    that.menuList = JSON.parse(sessionStorage.getItem('menuList'));
                } else {
                    that.data = [];
                }
            }).catch(function (error) {
                that.$Message.error('Network error, request failed');
                console.log(error);
            });
        },
        // 点击改变角色状态
        clickChangeSwitch(id, is_status, menu_auth_id) {

            return new Promise((resolve) => {
                // 判断是否有权限
                const that = this;
                const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
                if (that.ISAdmin == false) {
                    that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                        if (response.data.code == 0) {
                            
                            that.$Message.error(response.data.msg);
                            return false;

                        } else {
                            that.changeSwitch(id, is_status);
                            resolve();
                        }
                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error);
                    });

                } else {
                    that.changeSwitch(id, is_status);
                    resolve();
                }
            });

        },
        // 保存改变角色状态
        async changeSwitch(id, is_status){
            const status = (is_status === true ? false : true);
            const that = this;

            await that.$api.AuthGroup.update(id, { status: status }).then(function (response) {

                if (response.data.code == 200) {

                    sessionStorage.removeItem('authGroup');

                    that.getAuthGroup();

                    that.$Message.success('status: ' + (is_status === true ? 'close' : 'open'));

                } else {
                    that.$Message.success('status Enable exception');
                }

            }).catch(function (error) {
                that.$Message.error('Network error, request failed');
                console.log(error)
            });
        },
        // add
        async addAuthGroup(menu_auth_id) {
            const that = this;

            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.modalAddAuthGroup = true;
                        that.formValidateID = 0;

                        const menuList = sessionStorage.getItem('menuList');

                        if (menuList) {
                            that.menuList = JSON.parse(menuList);
                        }
                    }
                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error);
                });

            } else {
                that.modalAddAuthGroup = true;
                that.formValidateID = 0;

                const menuList = sessionStorage.getItem('menuList');

                if (menuList) {
                    that.menuList = JSON.parse(menuList);
                }
            }
            
        },
        // edit
        async editAuthGroup(id, menu_auth_id) {

            var that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.readAuthGroup(id);
                    }
                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error);
                });

            } else {
                that.readAuthGroup(id);
            }
            
        },
        // readAuthGroup
        async readAuthGroup(id){
            const that = this;
            that.$Message.loading('拼命加载中...');
            setTimeout(() => {
                that.modalAddAuthGroup = true;
            },1000)
            
            that.menuList = [];
            
            await that.$api.AuthGroup.read(id).then(function (response) {

                if (response.data.code == 200) {

                    // that.$Message.success(response.data.msg);

                    that.formValidate = response.data.data.data;

                    that.menuList = response.data.data.menu;

                    that.formValidateID = id;
                } else {
                    that.$Message.success(response.data.msg);
                }

            }).catch(function (error) {
                that.$Message.error('Network error, request failed');
                console.log(error)
            })
        },
        // click del
        async clickDelAuthGroup(id, menu_auth_id) {

            var that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.delAuthGroup(id);
                    }
                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error);
                });

            } else {
                that.delAuthGroup(id);
            }

        },
        // del
        delAuthGroup(id) {
            const that = this;
            this.$Modal.confirm({
                title: 'Del',
                content: 'Are you sure you want to delete it?',
                onOk: function () {

                    that.$api.AuthGroup.delete(id).then(function (response) {

                        if (response.data.code == 200) {
                            that.$Message.success(response.data.msg);
                            that.getAuthGroup();
                        } else {
                            that.$Message.error(response.data.msg);
                        }

                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error)
                    });

                }
            })
        },
        // 提交数据
        async AsyncSaveAuthGroup(name) {
            const that = this;
            // 验证MenuName
            await this.$refs[name].validate((valid) => {
                if (valid) {
                    setTimeout(() => {
                        // 获取被勾选的节点
                        let res = this.$refs.tree.getCheckedAndIndeterminateNodes();
                        // let res = this.$refs.tree.getCheckedNodes();
                        let menuIds = [];
                        for (let i = 0; i < res.length; i++) {
                            menuIds.push(res[i].id)
                        }

                        if (res == '') {

                            this.loading = false;
                            setTimeout(() => {
                                that.$Message.error('Rules Must be Check!');
                                this.loading = true;
                            }, 100);

                            return;
                        }

                        menuIds.join(',');
                        let params = {
                            title: that.formValidate.title,
                            status: that.formValidate.status,
                            rules: menuIds
                        };
                        // formValidateID 0 新增
                        if (that.formValidateID === 0) {

                            that.$api.AuthGroup.save(params).then(function (response) {

                                if (response.data.code == 200) {

                                    that.$Message.success(response.data.msg);

                                    that.modalAddAuthGroup = false;

                                    that.formValidate = {
                                        title: '',
                                        status: false
                                    };

                                    that.getAuthGroup();

                                } else {
                                    that.$Message.success(response.data.msg);
                                }

                            }).catch(function (error) {
                                that.$Message.error('Network error, request failed');
                                console.log(error)
                            })

                        } else {
                            // formValidateID <> 0 更新数据
                            that.$api.AuthGroup.update(that.formValidateID, params).then(function (response) {

                                if (response.data.code == 200) {

                                    that.$Message.success(response.data.msg);

                                    that.modalAddAuthGroup = false;

                                    that.formValidate = {
                                        title: '',
                                        status: false
                                    };

                                    that.getAuthGroup();

                                } else {
                                    that.$Message.success(response.data.msg);
                                }

                            }).catch(function (error) {
                                that.$Message.error('Network error, request failed');
                                console.log(error)
                            })
                        }


                    }, 1000)

                    sessionStorage.removeItem('rules');
                } else {
                    this.loading = false;
                    setTimeout(() => {
                        this.loading = true;
                    }, 10);
                }
            })
        },
        // 关掉model
        cancelModel() {
            this.formValidate = {
                title: '',
                status: false
            };
            // this.resetTreeSelection(this.menuList);
            this.menuList = [];
            this.$refs['formValidate'].resetFields();
        }
    }
}

</script>
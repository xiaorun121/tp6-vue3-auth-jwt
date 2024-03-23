<style>
.demo-split {
    height: 50px;
    /* border: 1px solid #dcdee2; */
}

.demo-split-pane {
    padding: 10px;
}
</style>

<template>
    <div v-if="haveRule">
        <Space direction="vertical" class="demo-split">
            <Space wrap class="demo-split-pane">
                <Icon type="md-add-circle" size="24" color="#2db7f5" title="add" @click="addMenu(41)" v-if="isSubstring(41, rules)" />
                <Icon type="ios-create" size="24" color="#47cb89" title="edit"
                    :style="{ margin: '0 0 0 14px', display: editButtonStatus }" @click="editMenu(selectId, 42)" v-if="isSubstring(42, rules)" />
                <Icon type="ios-trash" size="24" color="red" title="del"
                    :style="{ margin: marginLeftDel, display: delButtonStatus }"
                    @click="delMenu(selectIds, 'top', 43)" v-if="isSubstring(43, rules)"/>
            </Space>
        </Space>
        <Modal v-model="modalAddMenu" title="addMenu" :loading="loading" @on-ok="AsyncSaveMenu('formValidate')"
            @on-cancel="cancelModel" draggable="true" sticky="true" reset-drag-position v-if="formValidate">
            <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="100">
                <FormItem label="MenuTitle" prop="title">
                    <Input v-model="formValidate.title" placeholder="Enter something..."></Input>
                </FormItem>
                <FormItem label="ParentMenu" prop="parent_id">
                    <Select v-model="formValidate.parent_id" filterable="true">
                        <Option :value="0" :key="0">父级菜单</Option>
                        <Option v-for="menu in ParentMenu" :value="menu.id" :key="menu.id">{{ menu.title }}</Option>
                    </Select>
                </FormItem>
                <FormItem label="Module">
                    <Input v-model="formValidate.module_name" placeholder="Enter something..."></Input>
                </FormItem>
                <FormItem label="Controller">
                    <Input v-model="formValidate.controller_name" placeholder="Enter something..."></Input>
                </FormItem>
                <FormItem label="View">
                    <Input v-model="formValidate.view_name" placeholder="Enter something..."></Input>
                </FormItem>
                <FormItem label="IsMenu">
                    <Switch v-model="formValidate.is_menu" size="large">
                        <template #open>
                            <span>On</span>
                        </template>
                        <template #close>
                            <span>Off</span>
                        </template>
                    </Switch>
                </FormItem>
                <FormItem label="Sort">
                    <Input v-model="formValidate.sorts" placeholder="Enter something..."></Input>
                </FormItem>
                <Input type="hidden" v-model="formValidateID"></Input>
            </Form>
        </Modal>
        <Table border stripe max-height="700" ref="selection" :columns="columns" :data="data" size="small"
            @on-select="selectOne" @on-select-cancel="cancelSelectOne" @on-select-all="selectAll"
            @on-select-all-cancel="cancelSelectAll" style="margin: 10px;">
            <template #title="{ row }">
                <strong>{{ row.title }}</strong>
            </template>
            <template #is_menu="{ row }">
                <Switch :model-value="row.is_menu" size="small" :before-change="(value) => clickChangeSwitch(row.id, row.is_menu, 252)" />
            </template>
            <template #action="{ row, index }">
                <Icon type="ios-create" size="24" color="#47cb89" title="edit" @click="editMenu(row.id, 42)"
                    v-if="isSubstring(42, rules)" />
                <Icon type="ios-trash" size="24" color="red" title="del" @click="delMenu(row.id, 'right', 43)"
                    v-if="isSubstring(43, rules)" />
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

            // add page
            loading: true,
            modalAddMenu: false,
            formValidate: {
                title: '',
                parent_id: '',
                module_name: '',
                controller_name: '',
                view_name: '',
                is_menu: false,
                sorts: ''
            },
            formValidateID: 0,
            ruleValidate: {
                name: [
                    { required: true, message: 'The MenuName cannot be empty', trigger: 'blur' }
                ],
                parent_id: [
                    {
                        required: true, message: 'The ParentMenu cannot be empty', trigger: 'blur', type: 'number'
                    }
                ]
            },
            ParentMenu: [],

            // edit del button
            editButtonStatus: 'none',
            delButtonStatus: 'none',
            marginLeftDel: '0 0 0 14px',

            // table menu
            columns: [
                {
                    type: 'selection',
                    width: 60,
                    align: 'center'
                },
                {
                    title: 'Title',
                    width: 250,
                    slot: 'title'
                },
                {
                    title: 'IsMenu',
                    key: 'is_menu',
                    slot: 'is_menu',
                },
                {
                    title: 'ModuleName',
                    key: 'module_name'
                },
                {
                    title: 'ControllerName',
                    key: 'controller_name'
                },
                {
                    title: 'ViewName',
                    key: 'view_name'
                },
                {
                    title: 'CreateTime',
                    key: 'create_time'
                },
                {
                    title: 'UpdateTime',
                    key: 'update_time'
                },
                {
                    title: 'Sort',
                    key: 'sorts'
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
            // select
            selectId: 0,
            selectIds: [],

            // table select
            defaultSelected: []
        }
    },
    created() {
        // 获取是否是管理员权限
        let isAdmin = JSON.parse(sessionStorage.getItem('userinfo'))['isAdmin'];
        this.ISAdmin = isAdmin;

        if (isAdmin == true) {

            let getData = sessionStorage.getItem('getData')

            if (getData) {
                let session_name = JSON.parse(getData).name;
                if (session_name != 'menu') {
                    this.getMenu();
                }
            } else {
                this.getMenu();
            }
        } else {
            // 获取当前路由的参数menu_id 
            let menu_id = this.$router.currentRoute._value.query.menu_id;
            this.checkAuth(menu_id);

            // 判断是否有当前用户的规则数据  rules
            let rules = sessionStorage.getItem('rules');
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
                let rulesArray = rules.split(',').map(item => parseInt(item.trim()));

                // 检查 menu_id 是否包含在 rulesArray 中
                return rulesArray.includes(parseInt(menu_id));
            }
        },
        // 获取用户的规则数据rules
        async getUserToRules() {
            let that = this;
            let user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
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
            let that = this;
            let userId = JSON.parse(sessionStorage.getItem('userinfo'))['user_id']
            await that.$api.Admin.checkAuth({ id: menu_id, user_id: userId }).then(function (response) {
                if (response.data.code == 200) {
                    that.haveRule = true;
                    that.getMenu();
                } else {
                    that.haveRule = false;
                }
            }).catch(function (error) {
                console.log(error)
            })
        },

        // 查询menu
        async getMenu(is_menu = 0) {
            var that = this;

            let menuData = JSON.parse(sessionStorage.getItem('menu'));

            if (menuData) {

                that.data = menuData;

            } else {
                sessionStorage.setItem('getData', JSON.stringify({ name: 'menu' }));

                if (is_menu == 1) {
                    let params = {
                        is_menu: is_menu,
                        user_id: JSON.parse(sessionStorage.getItem('userinfo'))['user_id']
                    }
                    await that.$api.Menu.index(params).then(function (response) {

                        if (response.data.code == 200) {

                            // sessionStorage.setItem('isMenu', JSON.stringify(response.data.data.data))

                            // 使用 Vuex 的 commit 方法将 response.data.data_left 数据提交到名为 'menuListLeft' 的 mutation 中。
                            // 在包含布局的路由中layout 可以使用这个数据进行更新 this.$store.state.menu
                            that.$store.commit('menuListLeft', response.data.data.data_left);

                        } else {
                            that.$store.commit('menuListLeft', '');
                        }

                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error)
                    });
                } else {
                    let params = {
                        is_menu: is_menu
                    }

                    await that.$api.Menu.index(params).then(function (response) {

                        if (response.data.code == 200) {

                            sessionStorage.setItem('menu', JSON.stringify(response.data.data.data));

                            that.data = JSON.parse(sessionStorage.getItem('menu'));

                        }

                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error)
                    });
                }
            }

        },
        // 多选模式下,选中某一项
        selectOne(res, row) {
            // console.log(res,row)
            if (res.length === 1) {
                this.editButtonStatus = 'inline-block';
                this.delButtonStatus = 'inline-block';
                this.selectId = row.id;
                this.selectIds = [res[0].id];
            } else {
                this.editButtonStatus = 'none';
                this.delButtonStatus = 'inline-block';
                this.marginLeftDel = '0 0 0 18px';
                this.selectId = 0;
                this.selectIds = [];
                for (var i = 0; i < res.length; i++) {
                    this.selectIds.push(res[i].id)
                }
            }
            //判断是否有子节点
            if (row.children) {
                row.children.forEach((item, index) => {
                    item._checked = true;
                })
            }
        },
        // 多选模式下,取消选中某一项
        cancelSelectOne(res, row) {
            if (res.length === 0) {
                this.editButtonStatus = 'none';
                this.delButtonStatus = 'none';
                this.selectId = 0;
                this.selectIds = [];
            } else if (res.length === 1) {
                this.editButtonStatus = 'inline-block';
                this.marginLeftDel = '0 0 0 14px';
                this.selectId = res[0].id;
                this.selectIds = [res[0].id];
            } else {
                this.editButtonStatus = 'none';
                this.marginLeftDel = '0 0 0 18px';
                this.selectId = 0;
                this.selectIds = [];
                for (var i = 0; i < res.length; i++) {
                    this.selectIds.push(res[i].id)
                }
            }
        },
        // 多选模式下,点击全选时
        selectAll(res) {
            this.editButtonStatus = 'none';
            this.delButtonStatus = 'inline-block';
            this.marginLeftDel = '0 0 0 18px';
            this.selectId = 0;
            for (var i = 0; i < res.length; i++) {
                this.selectIds.push(res[i].id)
            }
        },
        // 多选模式下,点击取消全选时
        cancelSelectAll() {
            this.editButtonStatus = 'none';
            this.delButtonStatus = 'none';
            this.selectId = 0;
            this.selectIds = [];
        },
        // 点击切换是否是菜单状态
        clickChangeSwitch(id, is_menu_status, menu_auth_id) {

            return new Promise((resolve) => {
                // 判断是否有权限
                let that = this;
                let user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
                if (that.ISAdmin == false) {
                    that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                        if (response.data.code == 0) {
                            
                            that.$Message.error(response.data.msg);
                            return false;

                        } else {
                            that.changeSwitch(id, is_menu_status);
                            resolve();
                        }
                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error)
                    });

                } else {
                    that.changeSwitch(id, is_menu_status);
                    resolve();
                }
            });

        },
        // 保存是否是菜单状态
        async changeSwitch(id, is_menu_status){
            let is_menu = (is_menu_status === true ? false : true);
            let that = this;
            await this.$api.Menu.update(id, { is_menu: is_menu }).then(function (response) {

                if (response.data.code == 200) {

                    sessionStorage.removeItem('menu');

                    that.getMenu();
                    that.getMenu(1);

                    that.$Message.success('IsMenu: ' + (is_menu_status === true ? 'close' : 'open'));

                } else {
                    that.$Message.success('IsMenu Enable exception');
                }

            }).catch(function (error) {

                that.$Message.error('Network error, request failed');
                console.log(error)
            });
        },
        // 添加菜单
        addMenu(menu_auth_id) {

            let that = this;
            let isTree = JSON.parse(sessionStorage.getItem('isTree'))
            let user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.modalAddMenu = true;

                        if (isTree) {
                            that.ParentMenu = isTree
                        } else {
                            that.getTree();
                        }
                    }
                }).catch(function (error) {
                    console.log(error);
                });

            } else {
                that.modalAddMenu = true;

                if (isTree) {
                    that.ParentMenu = isTree
                } else {
                    that.getTree();
                }
            }

            // this.ParentMenu = JSON.parse(sessionStorage.getItem('isMenu'));
        },
        // 获取getTree数据
        async getTree() {
            var that = this;
            await that.$api.Menu.getTree().then(function (response) {
                if (response.data.code == 200) {

                    // that.formValidate = response.data.data;
                    // that.formValidateID = id;
                    sessionStorage.setItem('isTree', JSON.stringify(response.data.data))
                    that.ParentMenu = JSON.parse(sessionStorage.getItem('isTree'))

                } else {
                    console.log(response.data.msg);
                }

            }).catch(function (error) {
                console.log(error);
            });
        },
        // 编辑菜单
        async editMenu(id, menu_auth_id) {
            var that = this;
            let user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.readMenu(id);
                    }
                }).catch(function (error) {
                    that.$Message.error(response.data.msg);
                });

            } else {
                that.readMenu(id);
            }
        },
        // 查看菜单
        async readMenu(id) {
            var that = this;
            that.modalAddMenu = true;
            that.formValidate = {};
            let isTree = JSON.parse(sessionStorage.getItem('isTree'))
            if (isTree) {
                that.ParentMenu = isTree
            } else {
                that.getTree();
            }
            await that.$api.Menu.read(id).then(function (response) {

                if (response.data.code == 200) {

                    that.formValidate = response.data.data;
                    that.formValidateID = id;

                } else {
                    that.$Message.error(response.data.msg);
                }

            }).catch(function (error) {
                that.$Message.error('Network error, request failed');
                console.log(error);
            });
        },
        // 点击删除菜单
        async delMenu(id, type, menu_auth_id) {

            var that = this;
            let user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.delsMenu(id, type);
                    }
                }).catch(function (error) {
                    that.$Message.error(response.data.msg);
                });

            } else {
                that.delsMenu(id, type);
            }


        },
        // 删除菜单
        delsMenu(id, type) {

            var selectIds = this.selectIds;

            var that = this;
            that.$Modal.confirm({
                title: 'Del',
                content: 'Are you sure you want to delete it?',
                onOk: function () {

                    if (type === 'top') {
                        that.$api.Menu.deleteMenuForIds({ ids: selectIds }).then(function (response) {

                            if (response.data.code == 200) {
                                that.$Message.success(response.data.msg);
                                that.editButtonStatus = "none";
                                that.delButtonStatus = "none";
                                sessionStorage.removeItem('menu');
                                that.getMenu();
                                that.getMenu(1);
                                that.getTree();

                            } else {
                                that.$Message.error(response.data.msg);
                            }

                        }).catch(function (error) {
                            that.$Message.error('Network error, request failed');
                            console.log(error);
                        });

                    } else {
                        that.$api.Menu.delete(id).then(function (response) {

                            if (response.data.code == 200) {
                                that.$Message.success(response.data.msg);
                                that.editButtonStatus = "none";
                                that.delButtonStatus = "none";
                                sessionStorage.removeItem('menu');
                                that.getMenu();
                                that.getMenu(1);
                                that.getTree();

                            } else {
                                that.$Message.error(response.data.msg);
                            }

                        }).catch(function (error) {
                            that.$Message.error('Network error, request failed');
                            console.log(error);
                        });
                    }

                }
            })
        },
        // 异步更新菜单
        AsyncSaveMenu(name) {
            var that = this;
            // 验证MenuName
            this.$refs[name].validate((valid) => {
                if (valid) {
                    setTimeout(() => {

                        // formValidateID 0 添加数据
                        if (that.formValidateID === 0) {
                            this.$api.Menu.save(this.formValidate).then(function (response) {

                                if (response.data.code == 200) {
                                    that.$Message.success('Nenu Save Success!');
                                    that.modalAddMenu = false;
                                    that.formValidate = {};
                                    that.editButtonStatus = "none";
                                    that.delButtonStatus = "none";

                                    sessionStorage.removeItem('menu');
                                    that.getMenu();
                                    that.getMenu(1);
                                    that.getTree();

                                }

                            }).catch(function (error) {
                                that.$Message.error('Network error, request failed');
                                console.log(error)
                            });
                        } else {
                            // formValidateID <> 0 更新数据
                            this.$api.Menu.update(that.formValidateID, this.formValidate).then(function (response) {

                                if (response.data.code == 200) {
                                    that.$Message.success('Nenu Save Success!');
                                    that.modalAddMenu = false;
                                    that.formValidate = {};
                                    that.formValidateID = 0;
                                    that.editButtonStatus = "none";
                                    that.delButtonStatus = "none";

                                    sessionStorage.removeItem('menu');
                                    that.getMenu();
                                    that.getMenu(1);
                                    that.getTree();

                                }

                            }).catch(function (error) {
                                that.$Message.error('Network error, request failed');
                                console.log(error)
                            });
                        }

                    }, 1000);

                } else {
                    this.$Message.error('Fail!');
                    this.loading = false;
                    setTimeout(() => {
                        this.loading = true;
                    }, 10);
                }
            })

        },
        // 取消菜单时候
        cancelModel() {
            this.modalAddMenu = false;
            this.formValidate = {};
        }
    }
}
</script>
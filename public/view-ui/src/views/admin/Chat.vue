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
                <Icon type="md-add-circle" size="24" color="#2db7f5" title="add" @click="saveChat(0, 194)" v-if="isSubstring(194, rules)"/>
            </Space>
        </Space>
        <Modal v-model="modalSaveChat" ref="refTaskFlowModal" title="saveChat" :loading="loading"
            @on-ok="AsyncSaveChat('formValidate')" @on-cancel="cancelModel('formValidate')" loading="true"
            lock-scroll="true" closable="false" :scrollable="false" v-if="formValidate">
            <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="140">
                <FormItem label="chat_id" prop="chat_id">
                    <Input v-model="formValidate.chat_id" placeholder="Enter something..."></Input>
                </FormItem>
                <FormItem label="detail">
                    <Input v-model="formValidate.detail" placeholder="Enter something..."></Input>
                </FormItem>
                <Input type="hidden" v-model="formValidateID"></Input>
            </Form>
        </Modal>
        <Table border max-height="700" :columns="columns" :data="data" size="small" style="margin: 10px;">
            <template #action="{ row, index }">
                <Icon type="ios-create" size="24" color="#47cb89" title="edit" @click="saveChat(row.id, 256)" v-if="isSubstring(256, rules)"/>
                <Icon type="ios-trash" size="24" color="red" title="del" @click="clickDelChat(row.id, 195)" v-if="isSubstring(195, rules)"/>
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

            // table
            columns: [
                {
                    title: 'ID',
                    key: 'id',
                    width: 60,
                    sortable: true,
                    align: 'center'
                },
                {
                    title: 'chat_id',
                    key: 'chat_id',
                    sortable: true,
                    align: 'center'
                },
                {
                    title: 'detail',
                    key: 'detail',
                    align: 'center'
                },
                {
                    title: 'Action',
                    width: 110,
                    slot: 'action',
                    align: 'center',
                }
            ],
            // table data
            data: [],

            // model
            loading: true,
            formValidateID: 0,
            formValidate: {},
            modalSaveChat: false,
            modelType: 'add',

            // validate
            ruleValidate: {
                chat_id: [
                    { required: true, message: 'The chat_id cannot be empty', trigger: 'blur' }
                ]
            }

        }
    },
    created() {


        // 获取是否是管理员权限
        let isAdmin = JSON.parse(sessionStorage.getItem('userinfo'))['isAdmin'];
        this.ISAdmin = isAdmin;

        if (isAdmin == true) {
            let chat = JSON.parse(sessionStorage.getItem('chat'))
            if (chat) {
                this.data = chat;
            } else {
                this.getChat();
            }
        } else {
            // // 获取当前路由的参数menu_id 
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
                    console.log(response.data.msg);
                    that.haveRule = true;
                    that.getChat();
                } else {
                    console.log(response.data.msg);
                    that.haveRule = false;
                }
            }).catch(function (error) {
                console.log(error)
            })
        },
        // 获取讨论组
        async getChat() {
            let that = this;
            await that.$api.Chat.index().then(function (response) {
                if (response.data.code == 200) {
                    that.data = response.data.data;
                    sessionStorage.setItem('chat', JSON.stringify(response.data.data))
                } else {
                    console.log(response.data.msg);
                }
            }).catch(function (err) {
                that.$Message.error('Network error, request failed');
            });
        },
        // add / read Chat
        async saveChat(id, menu_auth_id) {
            let that = this;

            let user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.modalSaveChat = true;

                        // read
                        if (id > 0) {
                            that.modelType = 'read';
                            that.getChatToId(id)
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
                that.modalSaveChat = true;

                // read
                if (id > 0) {
                    that.modelType = 'read';
                    that.getChatToId(id)
                } else {
                    that.formValidateID = 0;
                    that.modelType = 'add';
                }
            }

        },
        // 查询当前的讨论组信息
        async getChatToId(id) {
            let that = this;
            await that.$api.Chat.read(id).then(function (response) {
                if (response.data.code == 200) {
                    that.formValidate = response.data.data;
                    that.formValidateID = id;
                } else {
                    that.$Message.error(response.data.msg);
                }
            }).catch(function (err) {
                that.$Message.error('Network error, request failed');
            });
        },
        // 点击删除管理员
        async clickDelChat(id, menu_auth_id) {
            let that = this;
            let user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.delChat(id)
                    }
                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error);
                });
            } else {
                that.delChat(id)
            }

        },
        // 删除管理员
        async delChat(id) {
            let that = this;
            await that.$Modal.confirm({
                title: 'Del',
                content: 'Are you sure you want to delete it?',
                onOk: function () {

                    that.$api.Chat.delete(id).then(function (response) {

                        that.$Message.success(response.data.msg);

                        if (response.data.code == 200) {
                            that.getChat()
                        }

                    }).catch(function (err) {
                        that.$Message.error('Network error, request failed');
                    });
                }
            })
        },
        // 提交数据
        async saveChatData(formData) {
            let that = this;
            let formValidateID = that.formValidateID;
            if (formValidateID > 0) {
                // edit
                await that.$api.Chat.update(formValidateID, formData).then(function (response) {

                    if (response.data.code == 200) {

                        that.$Message.success(response.data.msg);

                        setTimeout(() => {

                            that.formValidateID = 0;
                            that.formValidate = {};
                            that.$refs['formValidate'].resetFields();

                            that.getChat();

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
                await that.$api.Chat.save(formData).then(function (response) {
                    if (response.data.code == 200) {

                        that.$Message.success(response.data.msg);

                        setTimeout(() => {

                            that.formValidate = {};
                            that.$refs['formValidate'].resetFields();

                            that.getChat();

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
        AsyncSaveChat(name) {
            let that = this;
            that.$refs[name].validate((valid) => {
                if (valid) {
                    setTimeout(() => {

                        that.modalSaveChat = false;

                        // 提交数据
                        that.saveChatData(that.formValidate)

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
            this.$refs[name].resetFields();
        }
    }
}

</script>
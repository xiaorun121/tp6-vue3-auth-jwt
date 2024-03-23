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
.ivu-page-options-sizer{
    width: 100px;
}
</style>
<template>
    <div v-if="haveRule">
        <Space direction="vertical" class="demo-split">
            <Space wrap class="demo-split-pane">
                <Icon type="md-add-circle" size="24" color="#2db7f5" title="add" @click="saveBackend(0, 185)" v-if="isSubstring(185, rules)"/>
            </Space>
        </Space>
        <Modal v-model="modalSaveBackend" ref="refTaskFlowModal" title="saveBackend" :loading="loading"
            @on-ok="AsyncSaveBackend('formValidate')" @on-cancel="cancelModel('formValidate')" loading="true" lock-scroll="true"
            closable="false" :scrollable="false" v-if="formValidate" >
            <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="90">
                <FormItem label="url" prop="url">
                    <Input v-model="formValidate.url" placeholder="Enter something..."></Input>
                </FormItem>
                <FormItem label="keywords" prop="keywords">
                    <Input v-model="formValidate.keywords" placeholder="Enter something..."></Input>
                </FormItem>
                <Input type="hidden" v-model="formValidateID"></Input>
            </Form>
        </Modal>
        <Table border max-height="700" :columns="columns" :data="data" size="small" style="margin: 10px;">
            <template #action="{ row, index }">
                <Icon type="ios-create" size="24" color="#47cb89" title="edit" @click="saveBackend(row.id, 186)" v-if="isSubstring(186, rules)"/>
                <Icon type="ios-trash" size="24" color="red" title="del" @click="clickDelBackend(row.id, 187)" v-if="isSubstring(187, rules)"/>
            </template>
        </Table>
        <Page :total="total" :page-size="pageSize" @on-next="nextPage" @on-prev="prevPage" @on-change="changePage" @on-page-size-change="pageSizeChange" show-sizer show-total show-elevator v-show="total > 10" style="margin: 10px;"/>
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
                    width: 80,
                    sortable: true,
                    align: 'center'
                },
                {
                    title: '链接',
                    key: 'url',
                    align: 'center'
                },
                {
                    title: '关键字',
                    key: 'keywords',
                    align: 'center'
                },
                {
                    title: '创建时间',
                    key: 'create_time',
                    align: 'center'
                },
                {
                    title: '更新时间',
                    key: 'update_time',
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

            // page
            total: 0,
            pageSize: 10,
            pageSizeOpts:10,
            page:1,

            // model
            loading: true,
            formValidateID: 0,
            formValidate: {},
            modalSaveBackend: false,
            modelType:'add',

            // validate
            ruleValidate: {
                url: [
                    { required: true, message: 'The url cannot be empty', trigger: 'blur' }
                ],
                keywords: [
                    { required: true, message: 'The keywords cannot be empty', trigger: 'blur' }
                ]
            },

            // button
            saveButton: true

        }
    },
    created() {

        // 获取是否是管理员权限
        let isAdmin = JSON.parse(sessionStorage.getItem('userinfo'))['isAdmin'];
        this.ISAdmin = isAdmin;

        if(isAdmin == true){
            this.getBackend();
        }else{
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
        async checkAuth(menu_id){
            let that = this;
            let userId = JSON.parse(sessionStorage.getItem('userinfo'))['user_id']
            await that.$api.Admin.checkAuth({id:menu_id,user_id:userId}).then(function(response) {
                if(response.data.code == 200){
                    console.log(response.data.msg);
                    that.haveRule = true;
                    that.getBackend();
                }else{
                    console.log(response.data.msg);
                    that.haveRule = false;
                }
            }).catch(function (error) {
                console.log(error)
            })
        },
        // 获取数据
        async getBackend() {
            let that = this;
            let param = {
                page:that.page,
                pageSize:that.pageSize
            };
            await that.$api.Backend.index(param).then(function (response) {
                if (response.data.code == 200) {
                    that.data = response.data.data.data;
                    that.total = response.data.data.total;
                    that.pageSize = response.data.data.per_page;
                }else {
                    console.log(response.data.msg);
                }
            }).catch(function (err) {
                that.$Message.error('Network error, request failed');
            });
        },
        // add / read Backend
        async saveBackend(id, menu_auth_id) {
            let that = this;

            let user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.modalSaveBackend = true;

                        // read
                        if (id > 0) {
                            that.modelType = 'read';
                            that.getBackendToId(id)
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
                that.modalSaveBackend = true;

                // read
                if (id > 0) {
                    that.modelType = 'read';
                    that.getBackendToId(id)
                } else {
                    that.formValidateID = 0;
                    that.modelType = 'add';
                }
            }

        },
        // 查询当前的数据信息
        async getBackendToId(id) {
            let that = this;
            await that.$api.Backend.read(id).then(function (response) {
                if (response.data.code == 200) {
                    that.formValidate = response.data.data;
                    that.formValidateID = id;
                } else {
                    that.$Message.error(response.data.msg);
                }
            }).catch(function (err) {
                that.$Message.success(err);
            });
        },
        // 点击删除
        async clickDelBackend(id, menu_auth_id) {
            let that = this;

            let user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.delBackend(id)
                    }
                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error);
                });
            } else {
                that.delBackend(id)
            }

        },
        // 删除
        async delBackend(id) {
            let that = this;
            await that.$Modal.confirm({
                title: 'Del',
                content: 'Are you sure you want to delete it?',
                onOk: function () {

                    that.$api.Backend.delete(id).then(function (response) {

                        if (response.data.code == 200) {
                            that.$Message.success(response.data.msg);

                            setTimeout(() => {
                                that.getBackend()
                            }, 1500);
                        }

                    }).catch(function (err) {
                        that.$Message.success(err);
                    });
                }
            })
        },
        // 提交数据
        async saveBackendData(formData) {
            let that = this;
            let formValidateID = that.formValidateID;
            if (formValidateID > 0) {
                // edit
                await that.$api.Backend.update(formValidateID, formData).then(function (response) {

                    if (response.data.code == 200) {

                        that.$Message.success(response.data.msg);

                        setTimeout(() => {
                            
                            that.formValidateID = 0;
                            that.formValidate = {};
                            that.$refs['formValidate'].resetFields();

                            that.getBackend();

                        },2000)
                    }else{
                        that.$Message.error(response.data.msg);
                        that.$refs['formValidate'].resetFields();
                    }

                }).catch(function (err) {
                    that.$Message.success(err);
                });
            } else {
                // add
                await that.$api.Backend.save(formData).then(function (response) {
                    if (response.data.code == 200) {

                        that.$Message.success(response.data.msg);

                        setTimeout(() => {
                            
                            that.formValidate = {};
                            that.$refs['formValidate'].resetFields();

                            that.getBackend();

                        },1000)
                        
                    }else{
                        that.$Message.error(response.data.msg);
                        that.$refs['formValidate'].resetFields();

                    }

                }).catch(function (err) {
                    that.$Message.success(err);
                });
            }
        },
        // 验证数据
        AsyncSaveBackend(name) {
            let that = this;
            that.$refs[name].validate((valid) => {
                if (valid) {
                    setTimeout(() => {

                        that.modalSaveBackend = false;

                        // 提交数据
                        that.saveBackendData(that.formValidate)

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
        },
        // page 
        // 页码改变的回调，返回改变后的页码
        changePage(page){
            var that = this;
            that.page = page;
            that.getBackend();
        },
        // 切换下一页时触发，返回切换后的页码
        nextPage(page){
            var that = this;
            that.page = page;
            that.getBackend();
        },
        // 切换上一页时触发，返回切换后的页码
        prevPage(page){
            var that = this;
            that.page = page;
            that.getBackend();
        },
        // 切换每页条数时的回调，返回切换后的每页条数 默认10
        pageSizeChange(pageSize){
            var that = this;
            that.pageSize = pageSize;
            that.page = 1;
            that.getBackend();
        },
    }
}

</script>
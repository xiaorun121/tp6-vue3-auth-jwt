<template>
    <div v-if="haveRule">
        <Form ref="formCustom" :model="formCustom" :rules="ruleCustom" :label-width="150" :style="{ width: '99%' }">
            <FormItem label="Website Name" prop="name">
                <Input v-model="formCustom.name" type="text" placeholder="Enter your Website Name"></Input>
            </FormItem>
            <FormItem label="Website Domain" prop="url">
                <Input v-model="formCustom.url" type="text" placeholder="Website Domain"></Input>
            </FormItem>
            <FormItem label="Website Title" prop="title">
                <Input v-model="formCustom.title" type="textarea" placeholder="Enter Website Title..."></Input>
            </FormItem>
            <FormItem label="Website Keyword" prop="keywords">
                <Input v-model="formCustom.keywords" type="textarea" placeholder="Enter Website Keyword..."></Input>
            </FormItem>
            <FormItem label="Website Description" prop="description">
                <Input v-model="formCustom.description" type="textarea"
                    placeholder="Enter Website Description..."></Input>
            </FormItem>
            <FormItem label="Website Copyright" prop="copyright">
                <Input v-model="formCustom.copyright" type="textarea" placeholder="Enter Website Copyright..."></Input>
            </FormItem>
            <FormItem>
                <Button type="primary" size="default" @click="handleSubmit('formCustom', 247)"
                    v-if="isSubstring(247, rules)">Submit</Button>
                <Button @click="handleReset('formCustom')" size="default" style="margin-left: 8px">Reset</Button>
            </FormItem>
        </Form>
    </div>
    <div v-else>
        <NoAuth />
    </div>
</template>
<script>
export default {
    data() {
        let validateName = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('The name cannot be empty'));
            } else {
                callback();
            }
        };
        return {
            // 是否有权限
            haveRule: true,

            // 是否是管理员
            ISAdmin: false,

            formCustom: [],
            ruleCustom: {
                name: [
                    { validator: validateName, trigger: 'blur' }
                ],
            },

            // 权限 是管理员 则显示未空  不是管理员 则获取权限数据
            rules: ''
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
                if (session_name != 'admin') {
                    this.getWebsite();
                }
            } else {
                this.getWebsite();
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
                    that.getWebsite();
                } else {
                    that.haveRule = false;
                }
            }).catch(function (error) {
                console.log(error)
            })
        },

        // 获取website 数据
        async getWebsite() {

            // 查询缓存中是否有website数据
            let websiteData = JSON.parse(sessionStorage.getItem('website'));

            if (websiteData) {

                this.formCustom = websiteData;

            } else {

                sessionStorage.setItem('getData', JSON.stringify({ name: 'admin' }));

                let that = this;

                that.$api.Website.getWebsite().then(function (response) {

                    if (response.data.code == 200) {

                        sessionStorage.setItem('website', JSON.stringify(response.data.data));

                        that.formCustom = JSON.parse(sessionStorage.getItem('website'));

                    }

                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error)
                });
            }

        },
        // 提交验证
        handleSubmit(name, menu_auth_id) {
            let that = this;
            that.$refs[name].validate((valid) => {
                if (valid) {

                    // 判断是否有按钮权限
                    that.checkIsAuthToButton(menu_auth_id);

                } else {
                    that.$Message.error('Fail!');
                }
            })
        },
        // 保存数据
        async saveData() {
            let that = this;
            // 提交数据
            await that.$api.Website.saveWebsite(that.formCustom).then(function (response) {

                if (response.data.code == 200) {

                    sessionStorage.removeItem('website')
                    that.$Message.success(response.data.msg);
                    that.getWebsite();
                }

            }).catch(function (error) {
                that.$Message.error('Network error, request failed');
                console.log(error)
            });
        },
        // 判断是否有按钮权限
        async checkIsAuthToButton(menu_auth_id) {
            let that = this;
            let user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.saveData()
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            }else{
                that.saveData()
            }
        },
        // 重置
        handleReset(name) {
            this.$refs[name].resetFields();
        }
    }
}
</script>
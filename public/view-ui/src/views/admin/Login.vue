<template>
    <div class="demo-login">
        <Login @on-submit="handleSubmit">
            <UserName name="username" />
            <Password name="password" />
            <Captcha class="demo-login-captcha" name="captcha" :count-down="0" @on-get-captcha="handleGetCaptcha">
                <template #text>
                  <img :src="captcha">
                </template>
            </Captcha>
            <Submit />
        </Login>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                captcha: 'http://www.tp6.com/api/v1/verify'
            }
        },
        created() {

            // 刷新验证码
            this.handleGetCaptcha();

        },
        methods: {
            // 数据验证
            handleSubmit (valid, { username, password, captcha }) {
                if (valid) {
                    this.$Message.loading({
                        content: '登录中...',
                        top: 100
                    });
                    this.login({username:username,password:password,captcha:captcha});
                }
            },
            // 点击切换验证码
            handleGetCaptcha () {
                const that = this;
                const newSrc = 'http://www.tp6.com/api/v1/verify' + '?' + Math.random();

                // 更新验证码图片的 src 属性
                that.captcha = newSrc;
            },
            // 提交数据
            async login(data){
                const that = this;
                await that.$api.Admin.login(data).then( function (response) {
                    if (response.data.code == 201) {
                        that.$Message.success(response.data.msg);
                        setTimeout(() => {
                            that.$router.push('/');
                        }, 1000);
                    } else {
                        that.$Message.error(response.data.msg);
                        that.handleGetCaptcha();
                    }
                }).catch( function(error) {
                    that.$Message.success(error);
                });
            }
        }
    }
</script>
<style>
    .demo-login{
        width: 400px !important;
        height: 500px;
        margin-top: 10% !important;
        margin: 0 auto;
    }
    .demo-login-captcha .ivu-btn{
        padding: 0;
    }
    .demo-login-captcha .ivu-btn img{
        height: 35px;
        width: 120px;
        position: relative;
        top: 2px;
    }
</style>
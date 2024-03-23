<style>
iframe {
  border: none;
}
</style>
<template>
    <div v-if="haveRule" style="height: 86%;margin: 5px;">
        <iframe  src="https://0lzt7ot9yn.apifox.cn/api-143720345" width="100%" height="100%"></iframe>
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

        }
    },
    created() {
        

        // 获取是否是管理员权限
        let isAdmin = JSON.parse(sessionStorage.getItem('userinfo'))['isAdmin'];

        if(isAdmin == true){
            this.haveRule = true;
        }else{
            // // 获取当前路由的参数menu_id 
            let menu_id = this.$router.currentRoute._value.query.menu_id;
            this.checkAuth(menu_id);
        }

    },
    methods: {
        // 查询当前窗口是否有权限
        async checkAuth(menu_id){
            let that = this;
            let userId = JSON.parse(sessionStorage.getItem('userinfo'))['user_id']
            await that.$api.Admin.checkAuth({id:menu_id,user_id:userId}).then(function(response) {
                if(response.data.code == 200){
                    console.log(response.data.msg);
                    that.haveRule = true;
                }else{
                    console.log(response.data.msg);
                    that.haveRule = false;
                }
            }).catch(function (error) {
                console.log(error)
            })
        },
    }
}

</script>
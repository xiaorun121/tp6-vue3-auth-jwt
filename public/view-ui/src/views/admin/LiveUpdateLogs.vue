<style>
.demo-split {
    height: 50px;
}
.demo-split-pane {
    padding: 10px;
}
.ivu-table-sort{
    width: 20px;
}
.ivu-page-options-sizer{
    width: 100px;
}
</style>
<template>
    <div v-if="haveRule">
        <Table size="small" :columns="columns" :data="data" border style="margin: 10px;"></Table>
        <Page :total="total" :page-size="pageSize" v-model="current_page" @on-next="nextPage" @on-prev="prevPage"
            @on-change="changePage" @on-page-size-change="pageSizeChange" show-sizer show-total show-elevator
            v-show="total > 10" style="margin: 10px;" />
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

            // query
            publish: {},

            // page
            total: 0,
            pageSize: 10,
            pageSizeOpts: 10,
            page: 1,
            current_page: 1,

            columns: [
                {
                    title: 'Title',
                    key: 'title'
                },
                {
                    title: 'Content',
                    key: 'content'
                },
                {
                    title: 'Date',
                    key: 'create_time',
                    sortable: true
                }
            ],

            data: []
        }
    },
    created() {

        // 获取是否是管理员权限
        let isAdmin = JSON.parse(sessionStorage.getItem('userinfo'))['isAdmin'];

        if (isAdmin == true) {
            this.getLiveUpdateLogs();
        } else {
            // 获取当前路由的参数menu_id 
            let menu_id = this.$router.currentRoute._value.query.menu_id;
            this.checkAuth(menu_id);
        }

    },
    methods: {
        // 查询当前窗口是否有权限
        async checkAuth(menu_id) {
            let that = this;
            let userId = JSON.parse(sessionStorage.getItem('userinfo'))['user_id']
            await that.$api.Admin.checkAuth({ id: menu_id, user_id: userId }).then(function (response) {
                if (response.data.code == 200) {
                    console.log(response.data.msg);
                    that.haveRule = true;
                    that.getLiveUpdateLogs();
                } else {
                    console.log(response.data.msg);
                    that.haveRule = false;
                }
            }).catch(function (error) {
                console.log(error)
            })
        },

        // 获取日志数据
        async getLiveUpdateLogs() {
            console.log('getLiveUpdateLogs')
            let that = this;
            let param = {
                page: that.page,
                pageSize: that.pageSize
            }
            await that.$api.LiveUpdateLogs.index(param).then(function (response) {

                if (response.data.code == 200) {
                    that.data = response.data.data.data;
                    that.total = response.data.data.total;
                    that.pageSize = response.data.data.per_page;
                    that.current_page = response.data.data.current_page;

                    // sessionStorage.setItem('AppTrace', JSON.stringify(response.data.data))
                } else if (response.data.code == 210) {
                    that.page = (response.data.data.page > 1) ? (response.data.data.page - 1) : 1;
                    if (that.page == 1) {
                        that.data = [];
                    } else {
                        that.getLiveUpdateLogs()
                    }
                } else {
                    console.log(response.data.msg);
                }
            }).catch(function (err) {
                console.log(err);
            });
        },


        // 点击查询
        clickSearch() {
            let that = this;

            let param = {
                page: 1,
                pageSize: 10
            }
            that.queryLiveUpdateLogs(param);
        },

        // 根据查询条件查询数据
        async queryLiveUpdateLogs(param) {
            let that = this;
            that.$Message.loading('拼命加载中...');
            await that.$api.LiveUpdateLogs.index(param).then(function (response) {
                if (response.data.code == 200) {
                    setTimeout(() => {
                        that.data = response.data.data.data;
                        that.total = response.data.data.total;
                        that.pageSize = response.data.data.per_page;
                        that.current_page = response.data.data.current_page;
                    }, 1000);
                } else {
                    console.log(response.data.msg);
                    that.data = [];
                    that.total = 0;
                    that.pageSize = 10;
                    that.current_page = 1;
                }
            }).catch(function (error) {
                console.log(error);
            });
        },

        // 刷新数据
        onRefresh() {
            this.page = 1;
            this.pageSize = 10;
            this.$Message.loading('拼命加载中...');
            this.user_id = '';
            setTimeout(() => {
                this.getLiveUpdateLogs();
            }, 2000)
        },
        // page 
        // 页码改变的回调，返回改变后的页码
        changePage(page) {
            var that = this;
            that.page = page;
            that.getLiveUpdateLogs();
        },
        // 切换下一页时触发，返回切换后的页码
        nextPage(page) {
            var that = this;
            that.page = page;
            that.getLiveUpdateLogs();
        },
        // 切换上一页时触发，返回切换后的页码
        prevPage(page) {
            var that = this;
            that.page = page;
            that.getLiveUpdateLogs();
        },
        // 切换每页条数时的回调，返回切换后的每页条数 默认10
        pageSizeChange(pageSize) {
            var that = this;
            that.pageSize = pageSize;
            that.page = 1;
            that.getLiveUpdateLogs();
        },
    }
}
</script>
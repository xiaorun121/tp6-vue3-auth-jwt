<style>
.ivu-page-options-sizer {
    width: 100px;
}

.demo-split {
    height: 50px;
}

.demo-split-pane {
    padding: 10px;
}

.ivu-divider-vertical {
    margin: 0 !important;
}
</style>
<template>
    <div v-if="haveRule">
        <div class="demo-split">
            <div class="demo-split-pane">
                <Space align="center" size="default">
                    <RadioGroup v-model="time">
                        <Radio label="year" size="default"></Radio>
                        <Radio label="month" size="default"></Radio>
                        <Radio label="week" size="default"></Radio>
                    </RadioGroup>
                    <Button size="default" @click="clickSearch">
                        <Icon type="ios-search" size="18" />
                    </Button>
                    <Button size="default" @click="onRefresh">
                        <Icon type="md-refresh" size="18" />
                    </Button>
                </Space>
            </div>
        </div>
        <Table size="small" :columns="columns" :data="data" border style="margin: 10px;">
            <template #yishangjia="{ row }">
                <div v-if="row.yishangjia > 0">
                    <strong style="color: #19be6b;" @click="read(row.b_project, 1)"> {{ row.yishangjia }} </strong>
                </div>
                <div v-else>
                    {{ row.yishangjia }}
                </div>
            </template>
            <template #shenhezhong="{ row }">
                <div v-if="row.shenhezhong > 0">
                    <strong style="color: #19be6b;" @click="read(row.b_project, 2)"> {{ row.shenhezhong }} </strong>
                </div>
                <div v-else>
                    {{ row.shenhezhong }}
                </div>
            </template>
            <template #daishenhe="{ row }">
                <div v-if="row.daishenhe > 0">
                    <strong style="color: #19be6b;" @click="read(row.b_project, 3)"> {{ row.daishenhe }} </strong>
                </div>
                <div v-else>
                    {{ row.daishenhe }}
                </div>
            </template>
            <template #zhanghaojinyong="{ row }">
                <div v-if="row.zhanghaojinyong > 0">
                    <strong style="color: #19be6b;" @click="read(row.b_project, 4)"> {{ row.zhanghaojinyong }} </strong>
                </div>
                <div v-else>
                    {{ row.zhanghaojinyong }}
                </div>
            </template>
            <template #fenpeizhong="{ row }">
                <div v-if="row.fenpeizhong > 0">
                    <strong style="color: #19be6b;" @click="read(row.b_project, 5)"> {{ row.fenpeizhong }} </strong>
                </div>
                <div v-else>
                    {{ row.fenpeizhong }}
                </div>
            </template>
            <template #yizanting="{ row }">
                <div v-if="row.yizanting > 0">
                    <strong style="color: #19be6b;" @click="read(row.b_project, 6)"> {{ row.yizanting }} </strong>
                </div>
                <div v-else>
                    {{ row.yizanting }}
                </div>
            </template>
            <template #daiyanzheng="{ row }">
                <div v-if="row.daiyanzheng > 0">
                    <strong style="color: #19be6b;" @click="read(row.b_project, 7)"> {{ row.daiyanzheng }} </strong>
                </div>
                <div v-else>
                    {{ row.daiyanzheng }}
                </div>
            </template>
            <template #yixiajia="{ row }">
                <div v-if="row.yixiajia > 0">
                    <strong style="color: #19be6b;" @click="read(row.b_project, 8)"> {{ row.yixiajia }} </strong>
                </div>
                <div v-else>
                    {{ row.yixiajia }}
                </div>
            </template>
            <template #zhanghaoguanlian="{ row }">
                <div v-if="row.zhanghaoguanlian > 0">
                    <strong style="color: #19be6b;" @click="read(row.b_project, 9)"> {{ row.zhanghaoguanlian }} </strong>
                </div>
                <div v-else>
                    {{ row.zhanghaoguanlian }}
                </div>
            </template>
            <template #gengxinzhong="{ row }">
                <div v-if="row.gengxinzhong > 0">
                    <strong style="color: #19be6b;" @click="read(row.b_project, 10)"> {{ row.gengxinzhong }} </strong>
                </div>
                <div v-else>
                    {{ row.gengxinzhong }}
                </div>
            </template>
            <template #qita="{ row }">
                <div v-if="row.qita > 0">
                   <strong style="color: #19be6b;" @click="read(row.b_project, 11)"> {{ row.qita }} </strong>
                </div>
                <div v-else>
                    {{ row.qita }}
                </div>
            </template>
        </Table>
        <Modal :title="modelTitle" v-model="modelProject" closable draggable sticky reset-drag-position footer-hide width="83%">
            <Table size="small" :columns="projectColumns" :data="projectData"  border style="margin: 10px;"></Table>
        </Modal>
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
            time: '',

            // data
            columns: [
                {
                    title: '名称',
                    key: 'b_project'
                },
                {
                    title: '已上架',
                    key: 'yishangjia',
                    slot: 'yishangjia'
                },
                {
                    title: '审核中',
                    key: 'shenhezhong',
                    slot: 'shenhezhong'
                },
                {
                    title: '待审核',
                    key: 'daishenhe',
                    slot: 'daishenhe'
                },
                {
                    title: '账号禁用',
                    key: 'zhanghaojinyong',
                    slot: 'zhanghaojinyong'
                },
                {
                    title: '分配中',
                    key: 'fenpeizhong',
                    slot: 'fenpeizhong'
                },
                {
                    title: '已暂停',
                    key: 'yizanting',
                    slot: 'yizanting'
                },
                {
                    title: '待验证',
                    key: 'daiyanzheng',
                    slot: 'daiyanzheng'
                },
                {
                    title: '已下架',
                    key: 'yixiajia',
                    slot: 'yixiajia'
                },
                {
                    title: '账号关联',
                    key: 'zhanghaoguanlian',
                    slot: 'zhanghaoguanlian'
                },
                {
                    title: '更新中',
                    key: 'gengxinzhong',
                    slot: 'gengxinzhong'
                },
                {
                    title: '其他',
                    key: 'qita',
                    slot: 'qita'
                }
            ],
            data: [],

            // model
            modelProject: false,
            modelTitle: '',
            projectColumns:[
                {
                    title: 'Id',
                    key: 'id'
                },
                {
                    title: '应用名',
                    key: 'app_name',
                    width: 200
                },
                {
                    title: '包名',
                    key: 'package_name',
                    width: 200
                },
                {
                    title: '主类名',
                    key: 'main_name',
                    width: 200
                },
                {
                    title: '类名',
                    key: 'class_name',
                    width: 200
                },
                {
                    title: 'k3',
                    key: 'k3',
                    width: 120
                },
                {
                    title: 'k4',
                    key: 'k4',
                    width: 120
                },
                {
                    title: '状态',
                    key: 'status',
                    width: 88
                },
                {
                    title: '项目组',
                    key: 'b_project',
                    width: 74
                }
            ],
            projectData:[]
        }
    },
    created() {

        // 获取是否是管理员权限
        const isAdmin = JSON.parse(sessionStorage.getItem('userinfo'))['isAdmin'];

        if (isAdmin == true) {
            this.getProjectGather();
        } else {
            // 获取当前路由的参数menu_id 
            const menu_id = this.$router.currentRoute._value.query.menu_id;
            this.checkAuth(menu_id);
        }

    },
    methods: {
        // 查询当前窗口是否有权限
        async checkAuth(menu_id) {
            const that = this;
            const userId = JSON.parse(sessionStorage.getItem('userinfo'))['user_id']
            await that.$api.Admin.checkAuth({ id: menu_id, user_id: userId }).then(function (response) {
                if (response.data.code == 200) {
                    console.log(response.data.msg);
                    that.haveRule = true;
                    that.getProjectGather();
                } else {
                    console.log(response.data.msg);
                    that.haveRule = false;
                }
            }).catch(function (error) {
                console.log(error)
            })
        },

        // 获取日志数据
        async getProjectGather() {
            const that = this;
            const param = {
                time: that.time
            }
            await that.$api.ProjectGather.index(param).then(function (response) {
                if (response.data.code == 200) {
                    that.data = response.data.data;

                    // sessionStorage.setItem('AppTrace', JSON.stringify(response.data.data))
                } else {
                    console.log(response.data.msg);
                }
            }).catch(function (err) {
                console.log(err);
            });
        },


        // 点击查询
        clickSearch() {
            const that = this;

            const param = {
                time: that.time
            }
            that.queryProjectGather(param);
        },

        // 根据查询条件查询数据
        async queryProjectGather(param) {
            const that = this;
            that.$Message.loading('拼命加载中...');
            await that.$api.ProjectGather.index(param).then(function (response) {
                if (response.data.code == 200) {
                    setTimeout(() => {
                        that.data = response.data.data;
                    }, 1000);
                } else {
                    console.log(response.data.msg);
                    that.data = [];
                }
            }).catch(function (error) {
                console.log(error);
            });
        },

        // 刷新数据
        onRefresh() {
            this.time = '';
            this.$Message.loading('拼命加载中...');
            setTimeout(() => {
                this.getProjectGather();
            }, 2000)
        },
        // 查看项目组数据列表信息
        async read(bProject, status) {
            const that = this;

            that.$Message.loading('拼命加载中...');
            const param = {
                b_project: bProject,
                status: status
            }
            await that.$api.ProjectGather.showProject(param).then(function (response) {
                if (response.data.code == 200) {
                    setTimeout(() => {
                        that.modelProject = true;
                        that.modelTitle = '数据组数据: ' + bProject
                        that.projectData = response.data.data;
                    }, 1000);
                } else {
                    that.$Message.error(response.data.msg);
                    console.log(response.data.msg);
                    that.data = [];
                }
            }).catch(function (error) {
                console.log(error);
            });

        }
    }
}
</script>
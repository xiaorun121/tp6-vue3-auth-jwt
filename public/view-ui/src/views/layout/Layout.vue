<style scoped>
.layout {
    border: 1px solid #d7dde4;
    background: #f5f7f9;
    position: relative;
    border-radius: 4px;
    overflow: hidden;
}

.layout .ivu-menu {
    z-index: 0
}

.layout-header-bar {
    background: #fff;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
}

.layout-logo-left {
    width: 90%;
    height: 30px;
    background: #5b6270;
    border-radius: 3px;
    margin: 15px auto;
}

.menu-icon {
    transition: all .3s;
}

.rotate-icon {
    transform: rotate(-90deg);
}

.menu-item span {
    display: inline-block;
    overflow: hidden;
    width: 69px;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: bottom;
    transition: width .2s ease .2s;
}

.menu-item i {
    transform: translateX(0px);
    transition: font-size .2s ease, transform .2s ease;
    vertical-align: middle;
    font-size: 16px;
}

.collapsed-menu span {
    width: 0px;
    transition: width .2s ease;
}

.collapsed-menu i {
    transform: translateX(5px);
    transition: font-size .2s ease .2s, transform .2s ease .2s;
    vertical-align: middle;
    font-size: 22px;
}

.ivu-global-footer {
    height: 30px !important;
    margin: 0;
    padding: 0;
}
</style>
<template>
    <div class="layout">
        <Layout>

            <Sider ref="side1" :style="{ position: 'relative', height: '99vh', left: 0, overflow: 'auto' }" collapsible
                v-model="isCollapsed">
                <p style="margin-left: 15px">
                    <Icon :type="adminIcon" class="ios-woman-outline" />{{ userinfo.username }}
                </p>
                <Divider />
                <Menu ref="menus" :active-name="activeName" theme="dark" width="auto" :open-names="openName"
                    :class="menuitemClasses" accordion @on-open-change="openChange">
                    <Submenu :name="(index + 1)" v-for="(menu, index) in menuListLeft" style="font-size:13px">
                        <template #title>
                            <Icon :type="iconTypes[index]"></Icon>
                            <span class="menu">{{ menu.title }}</span>
                        </template>
                        <MenuItem style="font-size:12px" v-for="menu_ch, ind in menu.children"
                            :name="(index + 1) + '-' + (ind + 1)"
                            @click="selectMenu(menu_ch.title, menu_ch.controller_name, menu_ch.id)">
                        {{ menu_ch.title }}</MenuItem>
                    </Submenu>

                </Menu>
            </Sider>
            <Layout>
                <Header :style="{ padding: 0 }" class="layout-header-bar">
                    <!-- 头部menu 图标按钮 -->
                    <Icon @click="collapsedSider" :class="rotateIcon" :style="{ margin: '0 20px' }" type="md-menu"
                        size="24">
                    </Icon>

                    <Space size="large">
                        <Notification :count="5" style="margin-right: 10px;" />
                        <Space size="large" wrap>
                            <Avatar shape="square" :src="imgUrl" />
                        </Space>
                        <Poptip v-model="visible">
                            <a>
                                <Icon :type="iconType" @click="onIcon" />
                            </a>
                            <template #title>
                                <a @click="updatePassword">update password</a>
                            </template>
                            <template #content>
                                <a @click="logout">logout</a>
                            </template>
                        </Poptip>
                    </Space>
                    <Modal v-model="modalUpdatePassword" title="update password" :loading="loading"
                        @on-ok="asyncUpdatePassword('formUpdatePwd')" @on-cancel="cancelModel('formUpdatePwd')"
                        draggable="true" sticky="true">
                        <Form ref="formUpdatePwd" :model="formUpdatePwd" :rules="ruleValidate" :label-width="180">
                            <FormItem label="Old Password" prop="old_password">
                                <Input v-model="formUpdatePwd.old_password" placeholder="Enter Old Password..."></Input>
                            </FormItem>
                            <FormItem label="New Password" prop="new_password">
                                <Input v-model="formUpdatePwd.new_password" placeholder="Enter New Password..."></Input>
                            </FormItem>
                            <FormItem label="Confirm New Password" prop="confirm_new_password">
                                <Input v-model="formUpdatePwd.confirm_new_password"
                                    placeholder="Enter Confirm New Password..."></Input>
                            </FormItem>
                        </Form>
                    </Modal>

                </Header>
                <Content :style="{ margin: '8px', background: '#fff', minHeight: '260px' }">

                    <Tabs type="card" ref="tabs" v-model="tabName" closable @on-tab-remove="handleTabRemove"
                        @on-click="clickTab">

                        <TabPane label="首页" name="#" closable="false">
                            <Index />
                        </TabPane>

                        <template v-for="(tab, index, key) in tabsList">

                            <TabPane :label="tab.label" :name="tab.name">

                                <router-view v-if="tab.name == tabName"></router-view>
                                <!-- <keep-alive :include="tabsList.map(i => i.name)">
                                    <router-view v-if="tab.name == tabName"></router-view>
                                </keep-alive> -->

                            </TabPane>

                        </template>

                    </Tabs>
                </Content>
                <GlobalFooter :copyright="copyright" />
            </Layout>

        </Layout>
    </div>
</template>

<script>
import imgUrl from "@/assets/img.gif"

export default {
    data() {
        return {
            iconTypes: {
                0: 'md-settings',
                1: 'ios-albums',
                2: 'logo-designernews',
                3: 'ios-send'
            },
            tabs: [],
            tabId: 0,
            imgUrl: imgUrl,
            iconType: 'ios-arrow-down',
            isCollapsed: false,
            visible: false,
            modalUpdatePassword: false,
            loading: true,
            activeName: '1-1',//选中菜单项
            openName: ['1'],//子菜单展开
            collapsedSiderStatus: false,
            menuListLeft: [],
            tabsList: [],
            tabName: '',
            formUpdatePwd: {
                old_password: '',
                new_password: '',
                confirm_new_password: ''
            },
            ruleValidate: {
                old_password: [
                    { required: true, message: 'The old password cannot be empty', trigger: 'blur' }
                ],
                new_password: [
                    { required: true, message: 'The new password cannot be empty', trigger: 'blur' }
                ],
                confirm_new_password: [
                    { required: true, message: 'The confirm new password cannot be empty', trigger: 'blur' }
                ]
            },
            copyright: 'Copyright © 2022 View Design All Rights Reserved',

            userinfo: {},
            adminIcon: 'ios-man-outline'
        }
    },
    created() {

        let token = sessionStorage.getItem('token');
        if (token == null || token == '') {
            this.$router.push('/login');

        } else {
            this.$router.push('/');

            let userinfo = JSON.parse(sessionStorage.getItem('userinfo'));
            if (userinfo) {
                this.userinfo = userinfo;
                if (userinfo.sex == '男') {
                    this.adminIcon = "ios-man-outline";
                } else {
                    this.adminIcon = "ios-woman-outline";
                }
            }


            // 获取菜单
            let isMenu = sessionStorage.getItem('isMenu');
            if (!isMenu) {
                this.getMenus();
            } else {
                this.menuListLeft = JSON.parse(isMenu)
            }
        }

    },
    updated() {

        if (sessionStorage.getItem('getData')) {

            sessionStorage.removeItem('getData');

            let sessionName = this.$route.name;

            sessionStorage.setItem('getData', JSON.stringify({ name: sessionName }));
        }

        // this.getMenus();

    },
    // 只有在其依赖的属性发生更改时才会重新计算。
    computed: {
        // menu.vue 更新了menu数据的时候同时更新包含布局layout的数据信息  通过 that.$store.commit('menuListLeft',response.data.data_left); 传递
        // this.$store.state.menu 是main.ts 里面定义的数据 23行
        // menuListLeft 则是 data里面的menuListLeft
        menuListLeft() {
            if (this.$store.state.menu === '') {   // menu.vue 没有更新  输出this.menuListLeft data里面的menuListLeft 数据
                sessionStorage.removeItem('isMenu');
                return this.menuListLeft
            } else {  // menu.vue 更新数据 则输出 this.$store.state.menu
                return this.$store.state.menu
            }
        },
        rotateIcon() {
            return [
                'menu-icon',
                this.isCollapsed ? 'rotate-icon' : ''
            ];
        },
        menuitemClasses() {
            return [
                'menu-item',
                this.isCollapsed ? 'collapsed-menu' : ''
            ]
        }

    },
    methods: {
        // 获取菜单
        async getMenus() {
            let that = this;
            let userId = JSON.parse(sessionStorage.getItem('userinfo'))['user_id']
            await this.$api.Menu.index({ is_menu: 1, user_id: userId }).then(function (response) {
                if (response.data.code == 200) {
                    that.menuListLeft = response.data.data.data_left;
                    sessionStorage.setItem('isMenu', JSON.stringify(response.data.data.data_left))
                }

            }).catch(function (error) {
                console.log(error)
            });
        },
        // 选择菜单
        selectMenu(menuName, cName, id) {

            // 定义跳转的链接，带上栏目的id 进行权限的判定
            let sName = cName + '?menu_id=' + id;

            // 判断菜单项是否存在于tabs
            if (sName in this.tabs) {
                console.log('the tab is exist');
            } else {

                // tabs 当前cName 属性值为ture 时 才会显示
                this.tabs[sName] = true;

                // 将选择的菜单插入到tabsList中
                this.tabsList.push({
                    label: menuName,
                    name: sName
                });
            }

            // 当前激活 tab 面板的 name
            this.tabName = sName;

            this.$router.push('/' + sName);

            // this.$router.push({path: cName})

            // this.$refs.tabs.activeKey = cName;

        },
        // 点击tab
        clickTab(name) {
            console.log(name)
            this.$router.push('/' + name);

        },
        // 点击删除当前tab
        handleTabRemove(tName) {
            // 在 tabsList 数组中查找待删除的选项卡对象
            let index = this.tabsList.findIndex(tab => tab.name === tName);
            if (index === -1) {
                return;
            }

            // 如果待删除的选项卡对象是当前活动选项卡，则需要切换到其他选项卡或首页
            if (this.tabName === tName) {
                if (this.tabsList.length > 1) {
                    let nextIndex = index === this.tabsList.length - 1 ? index - 1 : index + 1;
                    this.tabName = this.tabsList[nextIndex].name;
                } else {
                    this.tabName = "#"; // 切换回首页
                }
            }

            // 从 tabsList 数组中删除选项卡对象
            this.tabsList.splice(index, 1);

            // 如果该选项卡有对应的路由数据，则需要从路由对象中删除该数据
            let route = this.tabs[tName];
            console.log(this.tabName)
            if (route && route.fullPath) {
                delete this.$router.matched[this.$router.matched.indexOf(route)];
            }

            // 从 tabs 对象中删除对应的属性
            delete this.tabs[tName];

            // 删除当前的tabPane里面的数据没有删除掉，则使用this.clickTab(this.tabName) 默认跳转的路由并且刷新当前路由数据
            this.clickTab(this.tabName)

        },
        // 展开/收起 子菜单
        openChange() {
            this.activeName = '0-0';
            if (this.collapsedSiderStatus == true) {
                this.$refs.side1.toggleCollapse();
                let submenu_icon = document.getElementsByClassName('ivu-menu-submenu-title-icon');
                let menu = document.getElementsByClassName('menu');
                for (let index = 0; index < submenu_icon.length; index++) {

                    if (this.collapsedSiderStatus == true) {
                        submenu_icon[index].style.display = "block";
                        menu[index].style.display = "contents";
                    } else {
                        submenu_icon[index].style.display = "none";
                        menu[index].style.display = "none";
                    }
                }

                this.collapsedSiderStatus = (this.collapsedSiderStatus == true ? false : true);
            }
        },
        // 点击头部的 menu 按钮 展开/收起
        collapsedSider() {
            this.openName = ['0'];
            this.activeName = '0-0';
            this.$nextTick(() => {
                this.$refs.menus.updateOpened();
                this.$refs.menus.updateActiveName();
            })

            this.$refs.side1.toggleCollapse();

            let submenu_icon = document.getElementsByClassName('ivu-menu-submenu-title-icon');
            let menu = document.getElementsByClassName('menu');
            for (let index = 0; index < submenu_icon.length; index++) {

                if (this.collapsedSiderStatus == true) {
                    submenu_icon[index].style.display = "block";
                    menu[index].style.display = "contents";
                } else {
                    submenu_icon[index].style.display = "none";
                    menu[index].style.display = "none";
                }
            }

            this.collapsedSiderStatus = (this.collapsedSiderStatus == true ? false : true);

        },
        // Poptip 气泡提示 弹出下拉菜单
        onIcon() {
            this.iconType = (this.iconType == 'ios-arrow-down' ? 'ios-arrow-up' : 'ios-arrow-down');
            // this.visible = true;
        },
        // 修改密码弹窗
        updatePassword() {

            this.modalUpdatePassword = true;

            this.visible = false;
        },
        // 异步修改密码
        asyncUpdatePassword(name) {

            console.log(this.loading)
            this.$refs[name].validate((valid) => {
                if (valid) {
                    setTimeout(() => {

                        // 验证旧密码 新密码 确认密码
                        this.$Message.success('Success!');
                        this.modalUpdatePassword = false;

                    }, 1000);

                } else {
                    // this.$Message.error('Fail!');
                    this.loading = false;
                    setTimeout(() => {
                        this.loading = true;
                    }, 10);
                }
            })

        },
        // 关掉model
        cancelModel(name) {
            this.formValidate = {};
            this.$refs[name].resetFields();
        },
        // logout
        logout() {
            var that = this;
            sessionStorage.clear();
            this.$Message.success('logout success!');

            setTimeout(() => {

                that.$router.push('/login')

            }, 1000);
        }
    }
}
</script>
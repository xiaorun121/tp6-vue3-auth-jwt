<style>
.ivu-split-trigger {
    display: none;
}

.ivu-table-sort {
    width: 18px !important;
}

.ivu-page-options-sizer {
    width: 100px;
}

.demo-split {
    height: 50px;
    /* border: 1px solid #dcdee2; */
}

.demo-split-pane {
    padding: 10px;
}

.ivu-divider-vertical {
    margin: 0 !important;
}

.demo-upload-list {
    display: inline-block;
    width: 60px;
    height: 60px;
    text-align: center;
    line-height: 60px;
    border: 1px solid transparent;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
    position: relative;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .2);
    margin-right: 4px;
}

.demo-upload-list img {
    width: 100%;
    height: 100%;
}

.demo-upload-list-cover {
    display: none;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, .6);
}

.demo-upload-list:hover .demo-upload-list-cover {
    display: block;
}

.demo-upload-list-cover i {
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    margin: 0 2px;
}
</style>
<template>
    <div v-if="haveRule">
        <div class="demo-split">
            <Split v-model="split">
                <template #left>
                    <div class="demo-split-pane">
                        <Icon type="md-add-circle" size="24" color="#2db7f5" title="add" @click="saveAppTrace(0, 157)"
                            v-if="isSubstring(157, rules)" />
                    </div>
                </template>
                <template #right>
                    <div class="demo-split-pane">
                        <Space wrap align="start" split>
                            <Select v-model="queryModel.b_project" @on-open-change="onOpenProject" placeholder="跳转项目组"
                                style="width:120px" size="default" clearable filterable>
                                <Option v-for="item in bProject" :value="item.b_project" :key="item.b_project">{{
        item.b_project }}</Option>
                            </Select>
                            <Select v-model="queryModel.app_type" @on-open-change="onOpenAppType" placeholder="类型"
                                style="width:100px" size="default" clearable filterable>
                                <Option v-for="item in appType" :value="item.app_type" :key="item.app_type">{{
        item.app_type
    }}</Option>
                            </Select>
                            <Select v-model="queryModel.status" @on-open-change="onOpenStatus" placeholder="状态"
                                style="width:100px" size="default" clearable filterable>
                                <Option v-for="item in status" :value="item.status" :key="item.status">{{ item.status }}
                                </Option>
                            </Select>
                            <Input v-model="queryModel.app_password" placeholder="密码" style="width: 150px"
                                size="default" />
                            <Input v-model="queryModel.domain_url" placeholder="域名地址" style="width: 150px"
                                size="default" />
                            <Input v-model="queryModel.app_name" placeholder="应用名" style="width: 150px"
                                size="default" />
                            <Input v-model="queryModel.package_name" placeholder="包名" style="width: 150px"
                                size="default" />
                            <Input v-model="queryModel.api_vps" placeholder="接口服务器" style="width: 150px"
                                size="default" />
                            <DatePicker size="default" type="date" @on-change="changeDate" v-model="queryModel.bag_time"
                                placeholder="提包日期" style="width: 120px;" />
                            <Button size="default" @click="clickSearch">
                                <Icon type="ios-search" size="18" />
                            </Button>
                            <Button size="default" @click="onRefresh">
                                <Icon type="md-refresh" size="18" />
                            </Button>
                        </Space>
                    </div>
                </template>
            </Split>
        </div>
        <Modal v-model="modalSaveAppTrace" ref="refTaskFlowModal" title="saveAppTrace" :loading="loading"
            @on-ok="AsyncSaveAppTrace('formValidate')" @on-cancel="cancelModel('formValidate')" loading="true"
            lock-scroll="true" closable="false" :scrollable="false" v-if="formValidate" width="78%">
            <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" inline>
                <FormItem prop="app_name">
                    <Input v-model="formValidate.app_name" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        应用名
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="app_package">
                    <Input v-model="formValidate.app_package" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        包
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="package_name">
                    <Input v-model="formValidate.package_name" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        包 名
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="main_name">
                    <Input v-model="formValidate.main_name" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        主类名
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="app_function">
                    <Input v-model="formValidate.app_function" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        功能
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="app_type">
                    <Select v-model="formValidate.app_type" size="default" style="width: 282px">
                        <template #prefix>
                            类 型 |
                        </template>
                        <Option v-for="menu in appType" :value="menu.app_type" :key="menu.app_type">{{ menu.app_type }}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem prop="bag_vps">
                    <Input v-model="formValidate.bag_vps" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        提包服务器
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="api_vps">
                    <Input v-model="formValidate.api_vps" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        接口服务器
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="vps_username">
                    <Input v-model="formValidate.vps_username" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        服务器用户名
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="vps_password">
                    <Input v-model="formValidate.vps_password" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        服务器密码
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="afdev">
                    <Input v-model="formValidate.afdev" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        AFdev
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="afaccount">
                    <Input v-model="formValidate.afaccount" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        AF账号
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="app_gmail">
                    <Input v-model="formValidate.app_gmail" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        谷歌邮箱
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="google_email_password">
                    <Input v-model="formValidate.google_email_password" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        谷歌邮箱密码
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="google_assist_email">
                    <Input v-model="formValidate.google_assist_email" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        谷歌辅助邮箱
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="google_play_link">
                    <Input v-model="formValidate.google_play_link" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        Google Play链接
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="spare_code">
                    <Input v-model="formValidate.spare_code" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        IPTOKEN
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="google_auth">
                    <Input v-model="formValidate.google_auth" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        身份验证
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="signature">
                    <Input v-model="formValidate.signature" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        数字签名
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="bag_time">
                    <Input type="date" v-model="formValidate.bag_time" size="default" style="width: 282px">
                    <template #prepend>
                        提包日期
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="blocked_cause">
                    <Input v-model="formValidate.blocked_cause" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        被封原因
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="domain_url">
                    <Input v-model="formValidate.domain_url" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        域名地址
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="privacy_agreement_url">
                    <Input v-model="formValidate.privacy_agreement_url" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        隐私协议地址
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="jump_url">
                    <Input v-model="formValidate.jump_url" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        跳转链接
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="b_project">
                    <Input v-model="formValidate.b_project" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        跳转项目组
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="status">
                    <Select v-model="formValidate.status" size="default" style="width: 282px">
                        <template #prefix>
                            状 态 |
                        </template>
                        <Option v-for="menu in status" :value="menu.status" :key="menu.status">{{ menu.status }}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="跳转开关" :label-width="70" style="width: 282px">
                    <Switch v-model="formValidate.jump_switch" size="large">
                        <template #open>
                            <span>On</span>
                        </template>
                        <template #close>
                            <span>Off</span>
                        </template>
                    </Switch>
                </FormItem>
                <FormItem label="域名验证" :label-width="70" style="width: 282px">
                    <Switch v-model="formValidate.google_sharch_status" size="large">
                        <template #open>
                            <span>On</span>
                        </template>
                        <template #close>
                            <span>Off</span>
                        </template>
                    </Switch>
                </FormItem>
                <FormItem label="HTTPS 启用状态" :label-width="120" style="width: 282px">
                    <Switch v-model="formValidate.https_status" size="large">
                        <template #open>
                            <span>On</span>
                        </template>
                        <template #close>
                            <span>Off</span>
                        </template>
                    </Switch>
                </FormItem>
                <FormItem prop="k3">
                    <Input v-model="formValidate.k3" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        k3/Adtoken
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="k4">
                    <Input v-model="formValidate.k4" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        K4/AdRegToken
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="sha1">
                    <Input v-model="formValidate.sha1" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        Sha1
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="up_time">
                    <Input type="date" v-model="formValidate.up_time" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        上架日期
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="update_reason">
                    <Input v-model="formValidate.update_reason" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        更新原因
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="down_time">
                    <Input v-model="formValidate.down_time" type="date" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        下架日期
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="down_reason">
                    <Input v-model="formValidate.down_reason" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        下架原因
                    </template>
                    </Input>
                </FormItem>
                <FormItem label="热更新开关" :label-width="85" style="width: 282px">
                    <Switch v-model="formValidate.update_switch" size="large">
                        <template #open>
                            <span>On</span>
                        </template>
                        <template #close>
                            <span>Off</span>
                        </template>
                    </Switch>
                </FormItem>
                <FormItem prop="update_url">
                    <Input v-model="formValidate.update_url" placeholder="Enter something..." size="default"
                        style="width: 282px">
                    <template #prepend>
                        热更新地址
                    </template>
                    </Input>
                </FormItem>
                <FormItem prop="publish_id">
                    <Select v-model="formValidate.publish_id" size="default" style="width: 282px">
                        <template #prefix>
                            发布人员 |
                        </template>
                        <Option v-for="menu in publish" :value="menu.id" :key="menu.id">{{ menu.username }}</Option>
                    </Select>
                </FormItem>
                <br>
                <FormItem prop="tele_chatid">
                    <Select v-model="formValidate.tele_chatid" multiple size="default">
                        <template #prefix>
                            讨论组id |
                        </template>
                        <Option v-for="menu in chat" :value="menu.value" :key="menu.value">{{ menu.lable }}</Option>
                    </Select>
                </FormItem>
                <br>
                <FormItem style="width: 400px">
                    <div class="demo-upload-list" v-if="uploadVisible">
                        <template v-if="uploadList.status === 'finished'">
                            <Image :src="uploadList.url" fit="cover" width="100%" height="100%" />
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-eye-outline" @click="handleView(uploadList.name)"></Icon>
                                <Icon type="ios-trash-outline" @click="handleRemove(uploadList, formValidateID, 258)">
                                </Icon>
                            </div>
                        </template>
                    </div>
                    <Upload ref="upload" :show-upload-list="false" :action="uploadAction + formValidateID" type="drag"
                        style="display: inline-block;width:58px;" :format="['jpg', 'jpeg', 'png']"
                        :before-upload="beforeUpload" :on-progress="handleProgress" :on-success="handleSuccess"
                        :on-error="handleError" :on-format-error="formatError">
                        <div style="width: 58px;height:58px;line-height: 58px;">
                            <Icon type="md-cloud-upload" size="20" />
                        </div>
                    </Upload>
                    <ImagePreview v-model="visible" :preview-list="['http://www.tp6.com/storage/' + imgName]" />
                </FormItem>
                <Input type="hidden" v-model="formValidateID"></Input>
            </Form>
        </Modal>
        <Table border max-height="700" :columns="columns" :data="data" size="small" style="margin: 10px;">
            <template #isCopy>
                <Icon type="ios-brush-outline" size="25" color="#FFB800" />
            </template>
            <template #status="{ row }">
                <Select v-model="row.status" style="width:120px" size="small"
                    @on-change="(value) => onChangeStatus(row.id, row.status, 160)">
                    <Option v-for="item in status" :value="item.status" :key="item.status">{{ item.status }}</Option>
                </Select>
            </template>
            <template #appType="{ row }">
                <Select v-model="row.app_type" style="width:120px" size="small"
                    @on-change="(value) => onChangeAppType(row.id, row.app_type, 164)">
                    <Option v-for="item in appType" :value="item.app_type" :key="item.app_type">{{ item.app_type }}
                    </Option>
                </Select>
            </template>
            <template #jumpSwitch="{ row }">
                <Switch :model-value="row.jump_switch" size="small"
                    :before-change="(value) => changeJumpSwitch(row.id, row.jump_switch, 161)">
                </Switch>
            </template>
            <template #googleSharchStatus="{ row }">
                <Switch :model-value="row.google_sharch_status" size="small"
                    :before-change="(value) => changeGoogleSharchStatus(row.id, row.google_sharch_status, 165)">
                </Switch>
            </template>
            <template #https_Status="{ row }">
                <Switch :model-value="row.https_status" size="small"
                    :before-change="(value) => changeHttpsStatus(row.id, row.https_status, 166)"></Switch>
            </template>
            <template #updateSwitch="{ row }">
                <Switch :model-value="row.update_switch" size="small"
                    :before-change="(value) => changeUpdateSwitch(row.id, row.update_switch, 196)"></Switch>
            </template>
            <template #image="{ row }">
                <Image :src="['http://www.tp6.com/storage/' + row.image]" fit="contain" width="120px" height="80px"
                    preview :preview-list="['http://www.tp6.com/storage/' + row.image]" v-if="row.image" />
            </template>
            <template #updateBatch="{ row }">
                <strong v-if="row.update_batch > 0" style="color:#0bd50b;font-size: 20px;font-weight: bold;"
                    @click="clickRead(row.app_name, row.id, 169)">{{ row.update_batch }}</strong><text v-else>{{
        row.update_batch
    }}</text>
            </template>
            <template #publishId="{ row }">
                <Select v-model="row.publish_id" style="width:120px" size="small"
                    @on-change="(value) => onChangePublishId(row.id, row.publish_id, 197)">
                    <Option v-for="item in publish" :value="item.id" :key="item.id">{{ item.username }}</Option>
                </Select>
            </template>
            <template #action="{ row, index }">
                <Icon type="ios-brush-outline" size="24" color="#FFB800" title="isCopy"
                    @click="clickIsCopy(row.id, 257)" v-if="isSubstring(257, rules)" />
                <Icon type="ios-create" size="24" color="#47cb89" title="edit" @click="saveAppTrace(row.id, 158)"
                    v-if="isSubstring(158, rules)" />
                <Icon type="ios-trash" size="24" color="red" title="del" @click="clickDelAppTrace(row.id, 159)"
                    v-if="isSubstring(159, rules)" />
            </template>
        </Table>
        <br />
        <Page :total="total" :page-size="pageSize" v-model="current_page" @on-next="nextPage" @on-prev="prevPage"
            @on-change="changePage" @on-page-size-change="pageSizeChange" show-sizer show-total show-elevator
            v-show="total > 10" style="margin: 10px;" />
        <Modal :title="modelTitle" v-model="modelProject" draggable sticky reset-drag-position footer-hide width="65%">
            <Table size="small" :columns="projectColumns" :data="projectData" border style="margin: 10px;"></Table>
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

            // 是否是管理员
            ISAdmin: false,

            // 权限 是管理员 则显示未空  不是管理员 则获取权限数据
            rules: '',

            // query
            split: 0.2,
            queryModel: {},
            bProject: {},
            appType: {},
            status: {},

            // table
            columns: [
                {
                    title: 'ID',
                    width: 70,
                    key: 'id',
                    fixed: 'left',
                    sortable: true,
                    align: 'center'
                },
                {
                    title: '应用名',
                    key: 'app_name',
                    fixed: 'left',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '状态',
                    key: 'status',
                    slot: 'status',
                    width: 150,
                    align: 'center'
                },
                {
                    title: '包名',
                    key: 'package_name',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '域名地址',
                    key: 'domain_url',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '跳转开关',
                    key: 'jump_switch',
                    slot: 'jumpSwitch',
                    width: 100,
                    align: 'center'
                },
                {
                    title: '跳转地址',
                    key: 'jump_url',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '跳转项目组',
                    key: 'b_project',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '跳转次数',
                    key: 'jump_count',
                    width: 100,
                    align: 'center'
                },
                {
                    title: '访问次数',
                    key: 'request_count',
                    width: 100,
                    align: 'center'
                },
                {
                    title: 'Sha1',
                    key: 'sha1',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '功能',
                    key: 'app_function',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '类型',
                    key: 'app_type',
                    slot: 'appType',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '域名验证',
                    key: 'google_sharch_status',
                    slot: 'googleSharchStatus',
                    width: 200,
                    align: 'center'
                },
                {
                    title: 'HTTPS 启用状态',
                    key: 'https_status',
                    slot: 'https_Status',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '图片',
                    key: 'image',
                    slot: 'image',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '讨论组id',
                    key: 'tele_chatid',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '热更新开关',
                    key: 'update_switch',
                    slot: 'updateSwitch',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '热更新地址',
                    key: 'update_url',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '包',
                    key: 'app_package',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '谷歌邮箱',
                    key: 'app_gmail',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '谷歌邮箱密码',
                    key: 'google_email_password',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '谷歌辅助邮箱',
                    key: 'google_assist_email',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '接口服务器',
                    key: 'api_vps',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '服务器用户名',
                    key: 'vps_username',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '服务器密码',
                    key: 'vps_password',
                    width: 200,
                    align: 'center'
                },
                {
                    title: 'AFdev',
                    key: 'afdev',
                    width: 200,
                    align: 'center'
                },
                {
                    title: 'AF账号',
                    key: 'afaccount',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '身份验证',
                    key: 'google_auth',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '提包日期',
                    key: 'bag_time',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '被封原因',
                    key: 'blocked_cause',
                    width: 200,
                    align: 'center'
                },
                {
                    title: 'Google Play链接',
                    key: 'google_play_link',
                    width: 200,
                    align: 'center'
                },
                {
                    title: 'IP查询TOKEN',
                    key: 'spare_code',
                    width: 200,
                    align: 'center'
                },
                {
                    title: 'k3/Adtoken',
                    key: 'k3',
                    width: 200,
                    align: 'center'
                },
                {
                    title: 'K4/AdRegToken',
                    key: 'k4',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '数字签名',
                    key: 'signature',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '主类名',
                    key: 'main_name',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '隐私协议地址',
                    key: 'privacy_agreement_url',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '上架日期',
                    key: 'up_time',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '更新批次',
                    key: 'update_batch',
                    slot: 'updateBatch',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '下架日期',
                    key: 'down_time',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '下架原因',
                    key: 'down_reason',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '录入时间',
                    key: 'create_time',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '发布时间',
                    key: 'create_time',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '更新时间',
                    key: 'update_time',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '发布人员',
                    key: 'publish_id',
                    slot: 'publishId',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '测试人员',
                    key: 'test_id',
                    width: 200,
                    align: 'center'
                },
                {
                    title: '测试时间',
                    key: 'test_time',
                    width: 200,
                    align: 'center'
                },
                {
                    title: 'Action',
                    slot: 'action',
                    width: 140,
                    align: 'center',
                    fixed: 'right'
                }
            ],
            // table data
            data: [],

            // page
            total: 0,
            pageSize: 10,
            pageSizeOpts: 10,
            page: 1,
            current_page: 1,

            // model
            loading: true,
            formValidateID: 0,
            formValidate: {},
            modalSaveAppTrace: false,
            modelType: 'add',
            chat: [],
            publish: [],

            // model 更新原因
            modelProject: false,
            modelTitle: '',
            projectColumns: [
                {
                    title: 'Id',
                    key: 'id',
                    width: 60
                },
                {
                    title: 'app_id',
                    key: 'app_id',
                    width: 80
                },
                {
                    title: 'update_batch',
                    key: 'update_batch',
                    width: 116
                },
                {
                    title: 'update_reason',
                    key: 'update_reason'
                },
                {
                    title: 'update_time',
                    key: 'update_time'
                }
            ],
            projectData: [],

            // validate
            ruleValidate: {
                app_name: [
                    { required: true, message: 'The app_name cannot be empty', trigger: 'blur' }
                ],
                app_type: [
                    { required: true, message: 'The app_type cannot be empty', trigger: 'blur' }
                ],
                status: [
                    { required: true, message: 'The status cannot be empty', trigger: 'blur' }
                ]
            },

            // upload
            imgName: '',
            visible: false,
            uploadList: {},
            uploadVisible: false,
            uploadAction: ''

        }
    },
    created() {

        // 获取是否是管理员权限
        const isAdmin = JSON.parse(sessionStorage.getItem('userinfo'))['isAdmin'];
        this.ISAdmin = isAdmin;

        // 获取用户id
        const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
        this.uploadAction = "http://www.tp6.com/api/v1/uploadImage?user_id=" + user_id + "&menu_auth_id=170&id=";

        if (isAdmin == true) {
            this.getAppTrace();
        } else {
            // // 获取当前路由的参数menu_id 
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
    // 当执行上传图片的时候，获取图片的数据，并将图片数据保存到uploadList中
    mounted() {
        this.uploadList = this.$refs.upload.fileList;
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
                    console.log(response.data.msg);
                    that.haveRule = true;
                    that.getAppTrace();
                } else {
                    console.log(response.data.msg);
                    that.haveRule = false;
                }
            }).catch(function (error) {
                console.log(error)
            })
        },
        /**
         * 查询条件 start
         */

        // 日期发生变化时触发
        changeDate(date) {
            this.queryModel.bag_time = date;
        },
        // 点击查询
        clickSearch() {
            const that = this;
            const queryModel = that.queryModel;

            const param = {
                page: 1,
                pageSize: 10,
                api_vps: queryModel.api_vps ? queryModel.api_vps : '',
                app_name: queryModel.app_name ? queryModel.app_name : '',
                app_password: queryModel.app_password ? queryModel.app_password : '',
                app_type: queryModel.app_type ? queryModel.app_type : '',
                b_project: queryModel.b_project ? queryModel.b_project : '',
                bag_time: queryModel.bag_time ? queryModel.bag_time : '',
                domain_url: queryModel.domain_url ? queryModel.domain_url : '',
                package_name: queryModel.package_name ? queryModel.package_name : '',
                status: queryModel.status ? queryModel.status : ''
            }
            that.queryAppTrace(param);

        },
        // 根据查询条件查询数据
        async queryAppTrace(param) {
            const that = this;
            that.$Message.loading('拼命加载中...');
            await that.$api.AppTrace.index(param).then(function (response) {
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
                that.$Message.error('Network error, request failed');
            });
        },
        // 刷新数据
        onRefresh() {
            this.page = 1;
            this.pageSize = 10;
            this.$Message.loading('拼命加载中...');
            this.queryModel = {};
            setTimeout(() => {
                this.getAppTrace();
            }, 2000)

        },
        /**
         * 查询条件 end
         */

        /**
         * tabs start
         */
        // 改变跳转开关状态
        changeJumpSwitch(id, jump_switch, menu_auth_id) {
            const JumpSwitch = (jump_switch == true) ? false : true;
            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            return new Promise((resolve) => {
                if (that.ISAdmin == false) {
                    that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                        if (response.data.code == 0) {
                            that.$Message.error(response.data.msg);
                            return false;
                        } else {
                            that.saveSwitchOrSelect(id, JumpSwitch, 'jump_switch');
                            resolve();
                        }
                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error);
                    });
                } else {
                    that.saveSwitchOrSelect(id, JumpSwitch, 'jump_switch');
                    resolve();
                }
            })

        },
        // 改变热更新开关状态
        changeUpdateSwitch(id, update_switch, menu_auth_id) {
            const UpdateSwitch = (update_switch == true) ? false : true;
            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            return new Promise((resolve) => {
                if (that.ISAdmin == false) {
                    that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                        if (response.data.code == 0) {
                            that.$Message.error(response.data.msg);
                            return false;
                        } else {
                            that.saveSwitchOrSelect(id, UpdateSwitch, 'update_switch');
                            resolve();
                        }
                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error);
                    });
                } else {
                    that.saveSwitchOrSelect(id, UpdateSwitch, 'update_switch');
                    resolve();
                }
            })
        },
        // 改变域名验证开关状态
        changeGoogleSharchStatus(id, google_sharch_status, menu_auth_id) {
            const GoogleSharchStatus = (google_sharch_status == true) ? false : true;
            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            return new Promise((resolve) => {
                if (that.ISAdmin == false) {
                    that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                        if (response.data.code == 0) {
                            that.$Message.error(response.data.msg);
                            return false;
                        } else {
                            that.saveSwitchOrSelect(id, GoogleSharchStatus, 'google_sharch_status');
                            resolve();
                        }
                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error);
                    });
                } else {
                    that.saveSwitchOrSelect(id, GoogleSharchStatus, 'google_sharch_status');
                    resolve();
                }
            })
        },
        // 改变HTTPS 启用状态
        changeHttpsStatus(id, https_status, menu_auth_id) {
            const HttpsStatus = (https_status == true) ? false : true;
            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            return new Promise((resolve) => {
                if (that.ISAdmin == false) {
                    that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                        if (response.data.code == 0) {
                            that.$Message.error(response.data.msg);
                            return false;
                        } else {
                            that.saveSwitchOrSelect(id, HttpsStatus, 'https_status');
                            resolve();
                        }
                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error);
                    });
                } else {
                    that.saveSwitchOrSelect(id, HttpsStatus, 'https_status');
                    resolve();
                }
            })
        },
        // 状态下拉选择
        onChangeStatus(id, status, menu_auth_id) {

            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            return new Promise((resolve) => {
                if (that.ISAdmin == false) {
                    that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                        if (response.data.code == 0) {
                            that.$Message.error(response.data.msg);
                            that.getAppTrace();
                            return false;
                        } else {
                            that.saveSwitchOrSelect(id, status, 'status');
                            resolve();
                        }
                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error);
                    });
                } else {
                    that.saveSwitchOrSelect(id, status, 'status');
                    resolve();
                }
            })

        },
        // 类型下拉选择
        onChangeAppType(id, appType, menu_auth_id) {
            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            return new Promise((resolve) => {
                if (that.ISAdmin == false) {
                    that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                        if (response.data.code == 0) {
                            that.$Message.error(response.data.msg);
                            that.getAppTrace();
                            return false;
                        } else {
                            that.saveSwitchOrSelect(id, appType, 'app_type');
                            resolve();
                        }
                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error);
                    });
                } else {
                    that.saveSwitchOrSelect(id, appType, 'app_type');
                    resolve();
                }
            })
        },
        // 发布人员下拉选择
        onChangePublishId(id, publishId, menu_auth_id) {
            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            return new Promise((resolve) => {
                if (that.ISAdmin == false) {
                    that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                        if (response.data.code == 0) {
                            that.$Message.error(response.data.msg);
                            that.getAppTrace();
                            return false;
                        } else {
                            that.saveSwitchOrSelect(id, publishId, 'publish_id');
                            resolve();
                        }
                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                        console.log(error);
                    });
                } else {
                    that.saveSwitchOrSelect(id, publishId, 'publish_id');
                    resolve();
                }
            })
        },
        /**
         * tabs end
         */


        // 保存更新的资源文件
        async saveSwitchOrSelect(id, data, field) {
            const that = this;
            await that.$api.AppTrace.update(id, { data: data, field: field }).then(function (response) {
                if (response.data.code == 200) {
                    that.$Message.success(response.data.msg);
                    setTimeout(() => {
                        that.getAppTrace()
                    }, 1000);
                } else {
                    that.$Message.error(response.data.msg);
                }
            }).catch(function (error) {
                that.$Message.error('Network error, request failed');
            });
        },
        /**
         * tabs end
         */


        // 获取App 跟踪数据
        async getAppTrace() {
            const that = this;
            const queryModel = that.queryModel;
            const param = {
                page: that.page,
                pageSize: that.pageSize,
                api_vps: queryModel.api_vps ? queryModel.api_vps : '',
                app_name: queryModel.app_name ? queryModel.app_name : '',
                app_password: queryModel.app_password ? queryModel.app_password : '',
                app_type: queryModel.app_type ? queryModel.app_type : '',
                b_project: queryModel.b_project ? queryModel.b_project : '',
                bag_time: queryModel.bag_time ? queryModel.bag_time : '',
                domain_url: queryModel.domain_url ? queryModel.domain_url : '',
                package_name: queryModel.package_name ? queryModel.package_name : '',
                status: queryModel.status ? queryModel.status : ''
            }
            await that.$api.AppTrace.index(param).then(function (response) {

                if (response.data.code == 200) {
                    that.data = response.data.data.data;
                    that.total = response.data.data.total;
                    that.pageSize = response.data.data.per_page;
                    that.current_page = response.data.data.current_page;

                    sessionStorage.setItem('AppTracebProject', JSON.stringify(response.data.data.bProject));
                    sessionStorage.setItem('AppTracebappType', JSON.stringify(response.data.data.appType));
                    sessionStorage.setItem('AppTracestatus', JSON.stringify(response.data.data.status));
                    sessionStorage.setItem('Chat', JSON.stringify(response.data.data.chat));
                    sessionStorage.setItem('Publish', JSON.stringify(response.data.data.admin));

                    that.bProject = JSON.parse(sessionStorage.getItem('AppTracebProject'));
                    that.appType = JSON.parse(sessionStorage.getItem('AppTracebappType'));
                    that.status = JSON.parse(sessionStorage.getItem('AppTracestatus'));
                    that.chat = JSON.parse(sessionStorage.getItem('Chat'));
                    that.publish = JSON.parse(sessionStorage.getItem('Publish'));

                    // sessionStorage.setItem('AppTrace', JSON.stringify(response.data.data))
                } else if (response.data.code == 210) {
                    that.page = (response.data.data.page > 1) ? (response.data.data.page - 1) : 1;
                    if (that.page == 1) {
                        that.data = [];
                    } else {
                        that.getAppTrace()
                    }
                } else {
                    console.log(response.data.msg);
                }
            }).catch(function (err) {
                that.$Message.error('Network error, request failed');
            });
        },
        // add / read Admin
        async saveAppTrace(id, menu_auth_id) {
            const that = this;

            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.modalSaveAppTrace = true;

                        // read
                        if (id > 0) {
                            that.modelType = 'read';
                            that.getAppTraceToId(id)
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
                that.modalSaveAppTrace = true;

                // read
                if (id > 0) {
                    that.modelType = 'read';
                    that.getAppTraceToId(id)
                } else {
                    that.formValidateID = 0;
                    that.modelType = 'add';
                }
            }

        },
        // 根据id查询当前的数据信息
        async getAppTraceToId(id) {
            const that = this;
            await that.$api.AppTrace.read(id).then(function (response) {
                if (response.data.code == 200) {
                    that.formValidate = response.data.data;
                    that.formValidateID = id;

                    if (response.data.data.image) {
                        console.log(response.data.data.image)
                        that.uploadList = {};
                        that.uploadVisible = true;
                        that.uploadList =
                        {
                            name: response.data.data.image,
                            status: "finished",
                            url: "http://www.tp6.com/storage/" + response.data.data.image,
                            showProgress: false
                        }
                        console.log(that.uploadList)
                    }
                } else {
                    that.$Message.error(response.data.msg);
                }
            }).catch(function (err) {
                that.$Message.error('Network error, request failed');
            });
        },
        // 提交数据
        async saveAppTraceData(formData) {
            const that = this;
            const formValidateID = that.formValidateID;
            if (formValidateID > 0) {
                // edit
                await that.$api.AppTrace.update(formValidateID, formData).then(function (response) {

                    if (response.data.code == 200) {

                        that.$Message.success(response.data.msg);

                        setTimeout(() => {

                            that.formValidateID = 0;

                            that.modalSaveAppTrace = false;

                            that.getAppTrace();

                            that.cancelModel('formValidate');

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
                await that.$api.AppTrace.save(formData).then(function (response) {
                    if (response.data.code == 200) {

                        that.$Message.success(response.data.msg);

                        setTimeout(() => {

                            that.modalSaveAppTrace = false;

                            that.getAppTrace();

                            that.cancelModel('formValidate');

                        }, 1000)

                    } else {
                        that.$Message.error(response.data.msg);
                        // that.$refs['formValidate'].resetFields();
                        that.cancelModel('formValidate');

                    }

                }).catch(function (err) {
                    that.$Message.error('Network error, request failed');
                });
            }
        },
        // 验证数据
        AsyncSaveAppTrace(name) {
            const that = this;
            that.$refs[name].validate((valid) => {
                if (valid) {
                    setTimeout(() => {

                        if (that.uploadVisible) {
                            that.formValidate.image = that.uploadList.name;
                        }

                        // 提交数据
                        that.saveAppTraceData(that.formValidate);
                        console.log(that.formValidate);

                    }, 1000)
                } else {
                    that.loading = false;
                    setTimeout(() => {
                        that.loading = true;
                    }, 10);
                }
            })
        },
        // 点击删除数据
        async clickDelAppTrace(id, menu_auth_id) {

            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.delAppTrace(id)
                    }
                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error);
                });
            } else {
                that.delAppTrace(id)
            }

        },
        // 删除数据
        async delAppTrace(id) {
            var that = this;
            await that.$Modal.confirm({
                title: 'delete',
                content: 'Are you sure you want to delete it?',
                onOk: function () {

                    that.$api.AppTrace.delete(id).then(function (response) {

                        if (response.data.code == 200) {
                            that.$Message.success(response.data.msg);

                            setTimeout(() => {
                                that.getAppTrace()
                            }, 1500);

                        }

                    }).catch(function (err) {
                        that.$Message.error('Network error, request failed');
                    });
                }
            })
        },
        // 关掉model
        cancelModel(name) {
            this.formValidate = {};
            this.$refs[name].resetFields();

            this.uploadVisible = false;
            this.uploadList = {};
        },
        // page 
        // 页码改变的回调，返回改变后的页码
        changePage(page) {
            var that = this;
            that.page = page;
            that.getAppTrace();
        },
        // 切换下一页时触发，返回切换后的页码
        nextPage(page) {
            var that = this;
            that.page = page;
            that.getAppTrace();
        },
        // 切换上一页时触发，返回切换后的页码
        prevPage(page) {
            var that = this;
            that.page = page;
            that.getAppTrace();
        },
        // 切换每页条数时的回调，返回切换后的每页条数 默认10
        pageSizeChange(pageSize) {
            var that = this;
            that.pageSize = pageSize;
            that.page = 1;
            that.getAppTrace();
        },
        // 点击查看图片
        handleView(name) {
            this.imgName = name;
            this.visible = true;
        },
        // 移除图片
        async handleRemove(file, id, menu_auth_id) {

            const name = file.name;

            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        // 删除服务器上的图片
                        this.delImage(name, id);
                        this.uploadList = {};
                        this.uploadVisible = false;
                    }
                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error);
                });
            } else {
                // 删除服务器上的图片
                this.delImage(name, id);
                this.uploadList = {};
                this.uploadVisible = false;
            }

            // const fileList = this.$refs.upload.fileList;
            // this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
        },
        // 删除图片
        async delImage(name, id) {
            const that = this;

            await that.$Modal.confirm({
                title: 'isDeleteImage',
                content: 'Are you sure you want to delete this image?',
                onOk: function () {
                    that.$api.AppTrace.delImage({ name: name, id: id }).then(function (response) {
                        if (response.data.code == 200) {
                            console.log(response.data.msg)
                            that.$Message.success(response.data.msg);
                        }
                    }).catch(function (error) {
                        that.$Message.error('Network error, request failed');
                    });
                }
            })            
        },
        // 上传图片成功
        handleSuccess(response, file, fileList) {
            if (response.code == 200) {
                this.uploadList = {};
                this.uploadVisible = true;
                this.uploadList =
                {
                    name: response.data,
                    status: "finished",
                    url: "http://www.tp6.com/storage/" + response.data,
                    showProgress: false
                }
            } else {
                this.$Message.error(response.msg);
            }

        },
        // 上传图片失败
        handleError(error, file, fileList) {
            this.$Message.error('上传失败');
        },
        // 上传图片格式验证失败
        formatError() {
            this.$Message.error('文件格式错误');
        },
        // 点击一键复制
        async clickIsCopy(id, menu_auth_id) {
            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.isCopy(id)
                    }
                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error);
                });
            } else {
                that.isCopy(id)
            }
        },
        // 一键复制
        async isCopy(id) {
            const that = this;
            await that.$Modal.confirm({
                title: 'isCopy',
                content: 'Are you sure you want to isCopy it?',
                onOk: function () {

                    that.$api.AppTrace.isCopy(id).then(function (response) {

                        if (response.data.code == 200) {
                            that.$Message.success(response.data.msg);

                            setTimeout(() => {
                                that.getAppTrace()
                            }, 1500);

                        }

                    }).catch(function (err) {
                        that.$Message.error('Network error, request failed');
                    });
                }
            })
        },
        // 点击查看更新原因数据列表信息
        async clickRead(title, id, menu_auth_id) {

            const that = this;
            const user_id = JSON.parse(sessionStorage.getItem('userinfo'))['user_id'];
            if (that.ISAdmin == false) {
                await that.$api.Admin.checkIsAuthToButton({ user_id: user_id, menu_auth_id: menu_auth_id }).then(function (response) {
                    if (response.data.code == 0) {
                        that.$Message.error(response.data.msg);
                    } else {
                        that.read(title, id)
                    }
                }).catch(function (error) {
                    that.$Message.error('Network error, request failed');
                    console.log(error);
                });
            } else {
                that.read(title, id)
            }

        },
        // 查看更新原因数据列表信息
        async read(title, id) {
            const that = this;

            that.$Message.loading('拼命加载中...');
            await that.$api.AppTrace.readUpdateBatchToId(id).then(function (response) {
                if (response.data.code == 200) {
                    setTimeout(() => {
                        that.modelProject = true;
                        that.modelTitle = '更新原因数据: ' + title
                        that.projectData = response.data.data;
                    }, 1000);
                } else {
                    that.$Message.error(response.data.msg);
                    console.log(response.data.msg);
                    that.data = [];
                }
            }).catch(function (error) {
                that.$Message.error('Network error, request failed');
            });

        }
    }
}

</script>
<template>
  <el-container>
    <el-aside>
      <el-row class="tac">
        <el-col :span="12">
          <el-menu default-active="2" class="el-menu-vertical-demo" background-color="#545c64" text-color="#fff"
            active-text-color="#ffd04b" :collapse="isCollapse">
            <el-submenu :index="index + ''" v-for="menu_left, index in menuListLeft">
              <template slot="title">
                <i class="el-icon-location"></i>
                <span>{{ menu_left.name }}</span>
              </template>
              <el-menu-item-group v-for="(children, c_index) in menu_left.children" :key="c_index">
                <el-menu-item :index="index + '-' + c_index + ''" @click="onCheck(children.name, editableTabsValue)">{{
                  children.name }}</el-menu-item>
              </el-menu-item-group>
            </el-submenu>
          </el-menu>
        </el-col>
      </el-row>
    </el-aside>
    <el-container>
      <el-header>

        <i class="el-icon-menu" @click="Collapse(isCollapse)"></i>
        <i class="el-icon-lock" @click="updatePassword">修改密码</i>

        <el-tabs v-model="editableTabsValue" type="card" closable @tab-remove="removeTab">
          <el-tab-pane :key="item.name" v-for="(item, index) in editableTabs" :label="item.title" :name="item.name">
            {{ item.content }}
          </el-tab-pane>
        </el-tabs>
      </el-header>
      <el-main>
        <menuList></menuList>
        <el-dialog title="Menu" :visible.sync="addMenu">
          <el-form :model="form" :rules="rules" ref="form">
            <el-form-item label="菜单名称" :label-width="formLabelWidth" prop="name">
              <el-input v-model="form.name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="父级菜单" :label-width="formLabelWidth" prop="parent_id">
              <el-select v-model="form.parent_id" placeholder="请选择活动区域" filterable>
                <el-option label="父级菜单" value="0"></el-option>
                <el-option v-for="menu in menulist" :key="menu.id" :label="menu.name" :value="menu.id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="模块名称" :label-width="formLabelWidth">
              <el-input v-model="form.module_name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="控制器名称" :label-width="formLabelWidth">
              <el-input v-model="form.controller_name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="方法名称" :label-width="formLabelWidth">
              <el-input v-model="form.view_name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="是否菜单" :label-width="formLabelWidth">
              <el-radio v-model="form.is_menu" label="1">是</el-radio>
              <el-radio v-model="form.is_menu" label="2">否</el-radio>
            </el-form-item>
            <el-form-item label="排序" :label-width="formLabelWidth" prop="sort">
              <el-input v-model="form.sort" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item class="dialog-footer">
              <el-button type="primary" @click="submitForm('form')">立即创建</el-button>
              <el-button @click="resetForm('form')">重置</el-button>
            </el-form-item>
          </el-form>

        </el-dialog>
      </el-main>

      <el-footer>版权所有 : 畅恒互娱</el-footer>
    </el-container>
  </el-container>
</template>

<script>
import { Message } from 'element-ui';
import menuList from '@/views/main/menuList.vue';
export default {
  components: {
    menuList
  },
  data() {
    return {
      editableTabsValue: '1',
      editableTabs: [{
        title: '首页',
        name: '1',
        content: 'Tab 1 content'
      }],
      tabIndex: 1,
      isCollapse: false,
      activeIndex: '1',
      addMenu: false,
      formLabelWidth: '120px',
      menulist: '',
      menuListLeft: '',
      form: {
        name: '',
        parent_id: '',
        is_menu: '1',
        module_name: '',
        controller_name: '',
        view_name: '',
        sort: ''
      },
      rules: {
        name: [
          { required: true, message: '请输入菜单名称', trigger: 'blur' }
        ],
        parent_id: [
          { required: true, message: '请选择父级分类', trigger: 'change' }
        ],
        sort: [
          { required: true, message: '请输入排序', trigger: 'change' }
        ]
      },
      tableData: []
    };
  },
  created() {
    // 首次进入获取菜单数据
    this.getMenu();
  },
  methods: {
    // 点击添加数据
    addMenus: function () {
      this.addMenu = true;
      this.getMenu(1);
    },
    // 点击二级菜单
    onCheck(menuName, targetName) {
      let newTabName = ++this.tabIndex + '';
      this.editableTabs.push({
        title: menuName,
        name: newTabName,
        content: ''
      });
      this.editableTabsValue = newTabName;
    },
    removeTab(targetName) {
      let tabs = this.editableTabs;
      let activeName = this.editableTabsValue;
      if (activeName === targetName) {
        tabs.forEach((tab, index) => {
          if (tab.name === targetName) {
            let nextTab = tabs[index + 1] || tabs[index - 1];
            if (nextTab) {
              activeName = nextTab.name;
            }
          }
        });
      }

      this.editableTabsValue = activeName;
      this.editableTabs = tabs.filter(tab => tab.name !== targetName);
    },
    handleSelect(key, keyPath) {
      console.log(key, keyPath);
    },
    Collapse: function (res) {
      if (this.isCollapse == false) {
        this.isCollapse = true
      } else {
        this.isCollapse = false
      }
    },
    updatePassword: function (res) {
      console.log('updatePassword')
    },
    tableRowClassName({ row, rowIndex }) {
      if (rowIndex % 2 === 0) {
        return 'default-row';
      } else {
        return 'success-row';
      }
    },
    // 获取menu菜单
    getMenu(is_menu = 0) {

      let that = this;
      this.$api.Menu.index({ is_menu: is_menu }).then(function (response) {

        if (response.data.code == 200) {
          if (is_menu == 1) {
            that.menulist = response.data.data;
          } else {
            that.tableData = response.data.data;
            that.menuListLeft = response.data.data_left;
          }
        }
      }).catch(function (error) {
        console.log(error)
      });
    },
    // 更新数据
    submitForm(form) {

      this.$refs[form].validate((valid) => {
        if (valid) {
          let that = this;
          this.$api.Menu.save(this.form).then(function (response) {
            if (response.data.code == 201) {
              Message.success(response.data.msg);
              that.addMenu = false;
              that.getMenu();
              that.form = '';
            }

          }).catch(function (error) {
            console.log(error)
          });

        } else {
          console.log('error submit');
          return false;
        }
      })
    },
    // 重置数据
    resetForm(form) {
      this.$refs[form].resetFields();
      this.form.module_name = '';
      this.form.controller_name = '';
      this.form.view_name = '';
      this.form.is_menu = 1;
    }
  }
}
</script>
<style>
html::-webkit-scrollbar {
  width: 0;
}

.el-main::-webkit-scrollbar {
  width: 0;
}

::-ms-scrollbar {
  width: 0;
}


.el-main {
  height: 92vh !important;
}

.el-menu-demo {
  z-index: 999;
}

.el-menu-vertical-demo:not(.el-menu--collapse) {
  width: 200px;
  min-height: 400px;
}

.el-aside {
  width: auto !important;
  overflow: hidden !important;
}

.el-header {
  padding: 0 !important;
}

.el-icon-menu {
  font-size: larger;
  color: #61e50e;
  margin-left: 10px;
  position: relative;
}

.el-icon-lock {
  float: right;
  font-size: small;
  color: #61e50e;
  margin-left: 10px;
  position: relative;
}

.el-footer {
  height: 20px !important;
  font-size: small;
  width: 100%;
  background-color: #fff;
  position: fixed;
  bottom: 0;
  text-align: right;
  right: 20px;
}

.el-table {
  height: auto;
  overflow: auto !important;
  font-size: small;
}

.el-table .default-row {
  background: #fff;
}

.el-table .success-row {
  background: #f0f9eb;
}

.button {
  margin: 10px;
}

.el-dialog {
  width: 22% !important;
}

.el-input {
  width: 200px;
}

.el-form-item {
  margin-bottom: 17px !important;
}

.dialog-footer>.el-form-item__content {
  float: right;
  width: 69%;
}
</style>
<template>
    <div>
      <a href="/#/schoolAdd"><el-button type="button" size="small" style="float: left;">数据添加</el-button></a>
      <!--  展示表格 start https://element.eleme.cn/#/zh-CN/component/table -->
      <el-table :data="tableData" stripe style="width: 100%">
        <el-table-column prop="id" label="ID" width="150"></el-table-column>
        <el-table-column prop="name" label="学校名" width="150"></el-table-column>
        <el-table-column prop="city" label="所在城市" width="150"></el-table-column>
        <el-table-column prop="num" label="人数" width="150"></el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button @click.native.prevent="deleteRow(scope.row)" type="text" size="small">删除</el-button>
            <el-button @click.native.prevent="editRow(scope.row)" type="text" size="small">编辑</el-button>
          </template>
        </el-table-column>
      </el-table>
      <!--  展示表格 end -->
      <!-- 分页控件 start https://element.eleme.cn/#/zh-CN/component/pagination -->
      <el-pagination background layout="prev, pager, next" :total="total" :page-size="pageSize" @current-change="getCurrentPage"></el-pagination>
      <!-- 分页控件 end -->
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        tableData: [],
        currentPage: 1,
        total: 0,
        pageSize: 3
      };
    },
    created() {
      var _self = this;
      _self.getPage();
    },
    methods: {
      // 调用接口、获取分页数据
      getPage: function() {
        this.$api.Admin.user();
        // var _self = this;
        // // 使用 ajax 请求后台提供的展示接口
        // _self.$ajax
        //   .get('http://0608.cc/school', {
        //     params: {
        //       page: _self.currentPage
        //     }
        //   })
        //   .then(function(response) {
        //     _self.tableData = response.data.res.data;
        //     _self.total = response.data.res.total;
        //   })
        //   .catch(function(error) {
        //     console.log(error);
        //   });
      },
      //当前页改变事件
      getCurrentPage: function(page) {
        var _self = this;
        // 改变当前页码
        _self.currentPage = page;
        // 获取当前页数据
        _self.getPage();
      },
  
      // 删除一条数据
      deleteRow(data) {
        var _self = this;
        var id = data.id;
        _self.$ajax
          .delete('http://0608.cc/school/' + id)
          .then(function(response) {
            alert(response.data.msg);
            if (response.data.code == 0) {
              _self.getPage();
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      },
      // 编辑
      editRow(row) {
        this.$router.push({ name: 'schoolEdit', params: row });
      }
    }
  };
  </script>
  
  <style></style>
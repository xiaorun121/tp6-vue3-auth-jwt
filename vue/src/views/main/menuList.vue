<el-row class="button"><el-button type="primary" @click="addMenus">添加</el-button></el-row>
        <el-table :data="tableData" style="width: 100%" border :row-class-name="tableRowClassName">
          <el-table-column prop="name" label="名称">
          </el-table-column>
          <el-table-column prop="is_menu" label="是否菜单">
          </el-table-column>
          <el-table-column prop="module_name" label="模块名称">
          </el-table-column>
          <el-table-column prop="controller_name" label="控制器名称">
          </el-table-column>
          <el-table-column prop="view_name" label="试图名称">
          </el-table-column>
          <el-table-column prop="create_time" label="创建时间">
          </el-table-column>
          <el-table-column prop="update_time" label="修改时间">
          </el-table-column>
          <el-table-column prop="sort" label="排序">
          </el-table-column>
          <el-table-column prop="operate" label="操作">
            <el-row>
              <el-button type="primary" icon="el-icon-edit"></el-button>
              <el-button type="danger" icon="el-icon-delete"></el-button>
            </el-row>
          </el-table-column>
        </el-table>
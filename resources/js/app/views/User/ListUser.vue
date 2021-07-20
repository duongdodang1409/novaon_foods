<template>
  <div class="app-container">
    <el-row class="toolbar" style="margin-bottom: 20px;">
      <el-col :span="12">
        <el-button type="primary" @click="create()" icon="el-icon-plus"
                   plain size="small" style="float: right;">{{ $t('my_lang.create') }}
        </el-button>
      </el-col>
    </el-row>
    <el-table v-loading="listLoading" :data="user" element-loading-text="Loading" border fit highlight-current-row>
      <el-table-column align="center" :label="$t('my_lang.id')" prop="id" width="65">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('my_lang.name')" prop="name">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('my_lang.email')" prop="email">
        <template slot-scope="scope">
          <span>{{ scope.row.email }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('my_lang.actived')" prop="actived" width="95">
        <template slot-scope="scope">
                    <span v-if="scope.row.actived == 1">
                        {{ $t('my_lang.activated') }}
                    </span>
          <span v-if="scope.row.actived == 0">
                        {{ $t('my_lang.deactivated') }}
                    </span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Action">
        <template slot-scope="scope">
          <el-button @click="drawer_edit = true; editData(scope.row.id)" type="primary"
                     icon="el-icon-edit">
          </el-button>
          <el-button @click="deleteData(scope.row.id)" type="danger" icon="el-icon-delete">
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-drawer
        title="EDIT"
        :visible.sync="drawer_edit"
        :direction="direction"
        size="40%">
      <el-form :model="model_edit" status-icon :rules="rules" ref="form" label-width="120px" class="demo-ruleForm">
        <el-form-item :label="$t('my_lang.name')" prop="name">
          <el-input v-model="model_edit.name"></el-input>
        </el-form-item>
        <el-form-item label="Mật Khẩu Cũ" prop="oldpass">
          <el-input type="password" v-model="model_edit.old_password" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item>
          <p id="error-message" style="color: #ff4d51 !important;"></p>
        </el-form-item>
        <el-form-item label="Mật Khẩu Mới" prop="password">
          <el-input type="password" v-model="model_edit.new_password" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="Xác nhận " prop="checkPass">
          <el-input type="password" v-model="model_edit.checkPass" autocomplete="off"></el-input>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="validate('form')">Submit</el-button>
          <el-button @click="resetForm('form')">Reset</el-button>
        </el-form-item>
      </el-form>
    </el-drawer>

  </div>


</template>

<script>
import Resource from '../../api/resource'

const userResource = new Resource('v1/users')

export default {
  data() {
    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('Please input the password again'));
      } else if (value !== this.model_edit.new_password) {
        callback(new Error('Confirm Password is not correct!'));
      } else {
        callback();
      }
    };
    return {
      drawer_edit: false,
      drawer_delete: false,
      direction: 'rtl',
      list: null,
      listLoading: true,
      user: [{
        id: '',
        name: '',
        email: '',
        actived: '',
        password:'',
      }],
      model_edit: {
        id: '',
        name: '',
        email: '',
        new_password: '',
        password: '',
        actived: '',
        old_password:'',
        checkPass: '',
      },
      query: {
        total: 0,
        page: 1,
        limit: 20,
      },


    };
  },
  created() {
    this.fetchData();
    if (this.drawer_edit == true) this.editData();
  },
  methods: {

    validate(formName) {
      this.$refs[formName].validate(valid => {
        console.log(valid)
        if (valid) this.save()
      });
    },

    async fetchData() {
      this.listLoading = true;
      const res = await userResource.list(this.query);
      this.user = res.data.data;
      this.query.total = res.data.total;
      this.query.perPage = res.data.per_page;
      this.listLoading = false;
    },
    create() {
      this.$router.push({name: 'CreateAdmin'});
    },
    async editData(id) {
      const res = await userResource.get(id);
      this.model_edit = res.data.item;

      this.model_edit.id = res.data.item.id;
      this.model_edit.name = res.data.item.name;
    },
    async save() {

      this.model_edit.password = this.model_edit.new_password;
      const res = await userResource.update(this.model_edit.id, this.model_edit);
      if(res.data.message){
        document.getElementById('error-message').textContent = res.data.message;
      }else{
        this.drawer_edit = false;
        this.fetchData();
        toast.fire({
          icon: 'success',
          title: 'Admin được update',
        })
      }
    },
    async deleteData(id) {
      if (confirm("Do you really want to delete?")) {
        const res = await userResource.destroy(id);
        this.fetchData();
        toast.fire({
          icon: 'success',
          title: 'User has been deleted',
        })
      }
    },

  },
};
</script>

<style>
.el-dropdown {
  vertical-align: top;
}

.el-dropdown + .el-dropdown {
  margin-left: 15px;
}

.el-icon-arrow-down {
  font-size: 12px;
}
</style>

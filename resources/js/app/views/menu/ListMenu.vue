<template>
  <div class="app-container">
    <el-row class="toolbar">
      <el-col :span="24">
        <el-col :span="24" style="padding-bottom: 30px">
          <el-button type="primary" @click="create()" icon="el-icon-plus"
                     plain size="small" style="float: right;">{{ $t('my_lang.create') }}
          </el-button>
        </el-col>
      </el-col>
    </el-row>
    <el-table v-loading="listLoading" :data="menu" element-loading-text="Loading" border fit highlight-current-row>

      <el-table-column label="Menu" prop="name" width="250">
        <template slot-scope="scope">
          <span v-if="(scope.row.level==1)">{{ scope.row.name }}</span>
          <span v-if="(scope.row.level==2)">___{{ scope.row.name }}</span>
          <span v-if="(scope.row.level==3)">______{{ scope.row.name }}</span>

        </template>
      </el-table-column>

      <el-table-column align="center" label="Level" prop="level" width="95">
        <template slot-scope="scope">
          <span>{{ scope.row.level }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Link" prop="link" width="300">
        <template slot-scope="scope">
          <span>{{ scope.row.link }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Slug" prop="slug" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.slug }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('my_lang.status')" prop="status" width="95">
        <template slot-scope="scope">
                    <span v-if="(scope.row.status)==1">
                            {{ $t('my_lang.activated') }}

                    </span>
          <span v-else>
                            {{ $t('my_lang.deactivated') }}
                    </span>

        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('my_lang.action')">
        <template slot-scope="scope">
          <el-button @click="drawer_edit = true; editData(scope.row.id)" type="primary"
                     style="margin-left: 16px;">
            <i class="el-icon-edit"></i>
          </el-button>
          <el-button @click="drawer_delete = true; deleteData(scope.row.id)" type="danger"
                     style="margin-left: 16px;">
            <i class="el-icon-delete"></i>
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-drawer
      title="EDIT MENU"
      :visible.sync="drawer_edit"
      :direction="direction"
      :before-close="handleClose"
      size="40%">
      <el-form ref="form" :model="menu_id" label-width="120px">
        <el-form-item label="Name" prop="name">
          <el-input v-model="menu_id.name" clearable/>
        </el-form-item>
        <el-form-item label="Menu Parent" prop="parent_id">
          <el-select v-model="menu_id.parent_id">
            <el-option
              v-for="item in menus"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
              <template slot-scope="scope">
                <div @click="getLevel(item.level)" v-if="(item.level==1)"><span>{{ item.name }}</span>
                </div>
                <div v-if="(item.level==2)" @click="getLevel(item.level)"><span>|---{{
                    item.name
                  }}</span></div>
                <div v-if="(item.level==3)" @click="getLevel(item.level)"><span>|---|---{{
                    item.name
                  }}</span></div>
              </template>

            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <h4 v-if="message" style="color: red;padding: 0px 0px;margin: 0px 0px">{{ message }} !</h4>
        </el-form-item>

        <el-form-item label="Slug" prop="slug">
          <el-input v-model="menu_id.slug" clearable/>
        </el-form-item>
        <el-form-item label="Link" prop="link">
          <el-input v-model="menu_id.link" clearable/>
        </el-form-item>
        <el-form-item label="Status" prop="is_activated">
          <el-switch v-model="menu_id.is_activated"></el-switch>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="validate('form')">Save</el-button>
        </el-form-item>
      </el-form>
    </el-drawer>


  </div>


</template>


<script>
import {getList} from '@/api/table';
import Resource from '../../api/resource'

const menuResource = new Resource('v1/menus')

export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'gray',
        deleted: 'danger',
      };
      return statusMap[status];
    },
  },
  data() {
    return {
      message: "",
      drawer_edit: false,
      drawer_delete: false,
      direction: 'rtl',
      list: null,
      listLoading: true,
      menus: [],
      menu: [{
        id: '',
        name: '',
        level: '',
        parent_id: '',
        slug: '',
        status: '',
        link: '',
        location: '',
      }],
      menu_id: {
        id: '',
        name: '',
        level: '',
        parent_id: '',
        slug: '',
        status: '',
        link: '',
        is_activated: '',
      },
      query: {
        location: 'top',
      },
    };
  },
  created() {
    this.fetchData();
    if (this.drawer_edit == true) this.editData();
    if (this.drawer_delete == true) this.deleteData();
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
      const res = await menuResource.list(this.query);
      this.menu = res.data.values;
      this.listLoading = false;
      this.total = res.data.values.length;
      this.menus = res.data.values;

    },
    create() {
      this.$router.push({path: '/menu/create'});
    },
    async editData(id) {
      const res = await menuResource.get(id);
      if (res.data.item.status == 1) {
        this.menu_id.is_activated = true;
      } else {
        this.menu_id.is_activated = false;
      }
      this.menu_id.name = res.data.item.name;
      this.menu_id.level = res.data.item.level;
      this.menu_id.slug = res.data.item.slug;
      this.menu_id.parent_id = res.data.item.parent_id;
      this.menu_id.id = res.data.item.id;
      this.menu_id.status = res.data.item.status;
      this.menu_id.link = res.data.item.link;
    },
    getLevel(level) {
      console.log('Level', level);
      this.menu_id.level = level + 1;
    },
    async save() {
      if (this.menu_id.is_activated == true) {
        this.menu_id.status = 1;
      } else {
        this.menu_id.status = 0;
      }
      if (this.menu_id.level <= 3) {
        const res = await menuResource.update(this.menu_id.id, this.menu_id);
        this.drawer_edit = false;
        this.fetchData();
        toast.fire({
          icon: 'success',
          title: 'Menu has been Updated',
        })
      } else {
        this.message = "Level Should be less than 4"
      }

    },
    handleClose(done) {
      this.$confirm('Are you sure you want to close this?')
        .then(_ => {
          done();
        })
        .catch(_ => {
        });
    },
    async deleteData(id) {
      if (confirm("Do you really want to delete")) {
        const res = await menuResource.destroy(id);
        this.fetchData();
        toast.fire({
          icon: 'danger',
          title: 'Menu has been deleted',
        })
      }

    }
  },
};
</script>

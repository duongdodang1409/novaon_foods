<template>
  <div class="app-container">
    <el-form ref="form" :model="menu" :rules="rules" label-width="120px">
      <el-form-item label="Menu name" prop="name">
        <el-input v-model="menu.name"/>
      </el-form-item>
      <el-form-item label="Slug" prop="slug">
        <el-input v-model="menu.slug" clearable @blur="slug()"/>
      </el-form-item>
      <el-form-item label="Link" prop="link">
        <el-input v-model="menu.link" clearable/>
      </el-form-item>

      <el-form-item label="Menu Parent" prop="id">
        <el-select v-model="menu.parent_id" clearable placeholder="Choose Parent Menu">
          <el-option
            v-for="item in menus"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          >
            <template slot-scope="scope">
              <div @click="getLevel(item.level)" v-if="(item.level==1)"><span>{{ item.name }}</span></div>
              <div v-if="(item.level==2)" @click="getLevel(item.level)"><span>|---{{ item.name }}</span>
              </div>
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
      <el-form-item>
        <el-button type="primary" @click="validate('form')">Save</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {getList} from '@/api/table';
import Resource from '../../api/resource'
import {slugtify} from "../../utils/slugtify";


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
      list: null,
      listLoading: true,
      menus: [],
      menu: {
        level: 1,
        name: '',
        parent_id: '',
        slug: '',
        link: '',
        location: 'top',
      },
      query:{
        location: 'top'
      },
      rules: {
        name: [
          {required: true, message: "Vui lòng nhập tên Footer", trigger: 'blur'}
        ],
      },
    };
  },
  created() {
    this.fetchData();
  },
  watch: {
    'menu.name': function (value) {
      if (!this.menu.id) {
        this.slug();
      }
    }
  },
  methods: {
    async fetchData() {
      this.listLoading = true;
      const res = await menuResource.list(this.query);
     // console.log('DATA', res.data.values);
      this.menus = res.data.values;
      this.listLoading = false;
    },
    validate(formName) {
      this.$refs[formName].validate(valid => {
        //console.log(valid)
        if (valid) this.save()
      });
    },
    async save() {
      if (this.menu.parent_id == '') {
        this.menu.parent_id = 0;
      }
      if (this.menu.link == '') {
        this.menu.link = '/';
      }
      if (this.menu.level <= 3) {
        const res = await menuResource.store(this.menu)
        this.$router.push({name: 'ListMenu'});
        toast.fire({
          icon: 'success',
          title: 'Menu has been Created',
        })
      } else {
        this.message = "Level Should be less than 4"
      }
    },
    slug() {
      this.menu.slug = slugtify(this.menu.name);
    },
    getLevel(level) {
     // console.log('Level', level);
      this.menu.level = level + 1;
    }

  },
};
</script>




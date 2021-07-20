<template>
  <div class="app-container">
    <el-form ref="form" :model="menu"  label-width="120px">
      <el-form-item prop="list_food">
        <el-transfer
            filterable
            v-model="array"
            :right-default-checked="array"
            :data="data"
            :titles="['Tất cả các món', 'Menu Thứ 4']">
        </el-transfer>
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


const foodResource = new Resource('v1/foods')
const weekdayResource = new Resource('v1/weekdays')


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
      menu_id:'',
      list: null,
      listLoading: true,
      data: [],
      array:[],
      list_foods:[],
      query: {
        menu_id:3,
      },
      menu: {
        id:'',
        foods:[],
      },
    };
  },
  created() {
    this.menu.id = this.query.menu_id;
    console.log('this menu id',this.menu.id);
    this.fetchData();
  },
  methods: {
    async fetchData() {
      this.listLoading = true;
      const food_list= await foodResource.list();
      const menu_foods =await weekdayResource.list(this.query);
      console.log('menu_food',menu_foods);
      for (var i = 0; i < menu_foods.data.items.length; i++) {
        this.array = this.array.concat(Object.values(menu_foods.data.items[i]));
      }
      this.list_foods=food_list.data.items;
      for (var i = 0; i < this.list_foods.length; i++) {
        this.data.push({
          label: this.list_foods[i].name,
          key: this.list_foods[i].id,
        });
      }
      this.listLoading = false;
    },
    validate(formName) {
      this.$refs[formName].validate(valid => {
        console.log(valid)
        if (valid) this.save()
      });
    },
    async save() {
      this.menu.foods = this.array;
      console.log('menu food',this.menu.foods);
      const res = await weekdayResource.update(this.menu.id, this.menu);
      toast.fire({
        icon: 'success',
        title: 'Cập nhật menu thành công!',
      })
    },


  },
};
</script>




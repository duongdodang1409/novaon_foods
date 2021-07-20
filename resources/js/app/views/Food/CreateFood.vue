<template>
  <div class="app-container">
    <el-form ref="form" :model="food" :rules="rules" label-width="120px">
      <el-form-item label="Tên Món Ăn" prop="name">
        <el-input v-model="food.name"/>
      </el-form-item>
      <el-form-item label="Giá" prop="price">
        <el-input v-model="food.price" clearable/>
      </el-form-item>
      <el-form-item label="Mô tả ngắn" prop="description">
        <el-input v-model="food.description" clearable/>
      </el-form-item>
      <el-form-item label="Ảnh" prop="image">
        <el-button  @click="upload()">Upload</el-button>
      </el-form-item>
      <el-form-item>
        <div v-if="imagepreview">
          <img v-bind:src="imagepreview" alt="" style="max-height: 100px;"/>
        </div>
      </el-form-item>

      <el-form-item label="Cửa Hàng" prop="restaurant_id">
        <el-select v-model="food.restaurant_id" clearable placeholder="Chọn Nhà Hàng">
          <el-option
              v-for="item in restaurants"
              :key="item.id"
              :label="item.brand"
              :value="item.id"
          >
            <template slot-scope="scope">
              <span>{{ item.brand }}</span>
            </template>
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Ngày phục vụ" prop="menu_id">
        <el-checkbox-group v-model="food.menu_id">
          <el-checkbox v-for="data in weekdays"
                       :key="data.id"
                       :label="data.id"
          >{{ data.weekday }}
          </el-checkbox>
        </el-checkbox-group>
      </el-form-item>

      <el-button type="primary" @click="validate('form')">Save</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {getList} from '@/api/table';
import Resource from '../../api/resource'


const foodResource = new Resource('v1/foods')
const restaurantResource = new Resource('v1/restaurants')
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
      imagepreview:null,
      url: process.env.MIX_APP_URL,
      list: null,
      listLoading: true,
      weekdays:[],
      restaurants: [],
      food: {
        id: '',
        name: '',
        restaurant_id: '',
        price: '',
        description:'',
        image:'',
        menu_id: [],
      },
      rules: {
        name: [
          {required: true, message: "Vui lòng nhập tên món", trigger: 'blur'}
        ],
        price: [
          {required: true, message: "Vui lòng nhập giá", trigger: 'blur'}
        ],
        restaurant_id: [
          {required: true, message: "Vui lòng chọn nhà hàng", trigger: 'blur'}
        ],

      },
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    upload(){
      // xu ly xac thuc token
      var token = this.$cookies.get('Admin-Token');
      //console.log('token',token);
      window.open(`/laravel-filemanager?token=` + token, '_blank', 'width=900,height=600')
      var self = this

      window.SetUrl = function (items) {
        console.log(items);
        console.log(items[0].name);
        var len = self.url.length;
        var length = items[0].url.length;
        self.food.image = items[0].url.slice(len, length);
        self.imagepreview = self.url + self.food.image;
      }
      return false
    },
    async fetchData() {
      this.listLoading = true;
      const res = await restaurantResource.list();
      const menu_day= await weekdayResource.list();
      this.weekdays = menu_day.data.items;
      console.log('day', this.weekdays);

      this.restaurants = res.data.items;
      console.log('ress', this.restaurants);
      this.listLoading = false;
    },
    validate(formName) {
      this.$refs[formName].validate(valid => {

        if (valid) this.save()
      });
    },
    async save() {
      console.log('this foood', this.food);
      const res = await foodResource.store(this.food)
      this.$router.push({name: 'ListFood'})
      toast.fire({
        icon: 'success',
        title: 'Thêm món ăn thành công!',
      })
    },


  },
};
</script>




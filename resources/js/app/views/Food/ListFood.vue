<template>
  <div class="app-container">
    <el-row class="toolbar" style="margin-bottom: 20px;">
      <el-col :span="12">
        <el-button type="primary" @click="create()" icon="el-icon-plus"
                   plain size="small" style="float: right;">{{ $t('my_lang.create') }}
        </el-button>
      </el-col>
    </el-row>
    <el-table v-loading="listLoading" :data="food" element-loading-text="Loading" border fit highlight-current-row>

      <el-table-column align="left" label="Tên Món Ăn" prop="name"width="250px">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Ảnh" prop="image" width="200px">
        <template slot-scope="scope">
          <img v-bind:src="url + scope.row.image" alt="" style="max-height: 120px;"/>
        </template>
      </el-table-column>
      <el-table-column align="left" label="Mô tả" prop="description" width="300px">
        <template slot-scope="scope">
          <span>{{ scope.row.description }}</span>
        </template>
      </el-table-column>

      <el-table-column align="left" label="Giá Món" prop="price" width="140px">
        <template slot-scope="scope">
          <span>{{formatPrice(scope.row.price )}} VND</span>
        </template>
      </el-table-column>
      <el-table-column align="left" label="Cửa Hàng" width="200px" prop="restaurant_id">
        <template slot-scope="scope">
          <span>{{ scope.row.brand }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('my_lang.action')">
        <template slot-scope="scope">
          <el-button @click="drawer_edit = true; editData(scope.row.id)" type="primary"
                     style="margin-left: 16px;">
            <i class="el-icon-edit"></i>
          </el-button>
          <el-button @click="deleteData(scope.row.id)" type="danger" style="margin-left: 16px;">
            <i class="el-icon-delete"></i>
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-drawer
      title="EDIT BANNER"
      :visible.sync="drawer_edit"
      :direction="direction"
      size="50%">
      <el-form ref="form" :model="food_edit"  label-width="120px">
        <el-form-item label="Tên Món Ăn" prop="name">
          <el-input v-model="food_edit.name"/>
        </el-form-item>
        <el-form-item label="Giá" prop="price">
          <el-input v-model="food_edit.price" clearable/>
        </el-form-item>
        <el-form-item label="Mô tả ngắn" prop="description">
          <el-input v-model="food_edit.description" clearable/>
        </el-form-item>
        <el-form-item label="Ảnh" prop="image">
          <el-button  @click="upload()">Upload</el-button>
        </el-form-item>
        <el-form-item>
          <div v-if="food_edit.image">
            <img v-bind:src="url + food_edit.image" alt="" style="max-height: 100px;"/>
          </div>
        </el-form-item>

        <el-form-item label="Cửa Hàng" prop="restaurant_id">
          <el-select v-model="food_edit.restaurant_id" clearable placeholder="Chọn Nhà Hàng">
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
          <el-checkbox-group v-model="array">
            <el-checkbox v-for="data in weekdays"
                         :key="data.id"
                         :label="data.id"
            >{{ data.weekday }}
            </el-checkbox>
          </el-checkbox-group>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="validate('form')">Save</el-button>
        </el-form-item>
      </el-form>
    </el-drawer>

  </div>


</template>

<script>
import Resource from '../../api/resource'
const restaurantResource = new Resource('v1/restaurants')
const weekdayResource = new Resource('v1/weekdays')
const foodResource = new Resource('v1/foods')


export default {
data(){
  return {
    url: process.env.MIX_APP_URL,
    array: [],
    restaurants: [],
    weekdays:[],
    drawer_edit: false,
    drawer_delete: false,
    direction: 'rtl',
    list: null,
    listLoading: true,
    food: [{
      id: '',
      name: '',
      price: '',
      image:'',
      description:'',
      restaurant_id:'',
      brand:'',

    }],
    food_edit:{
      name:'',
      price:'',
      image:'',
      description:'',
      restaurant_id:'',
      menu_id:[],
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
        if (valid) this.save()
      });
    },
    formatPrice(value) {
      let val = (value/1).toFixed(0).replace('.', ',')
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    },

    async fetchData() {
      this.listLoading = true;
      const res = await restaurantResource.list();
      const foods = await foodResource.list();
      const menu_day= await weekdayResource.list();


      this.weekdays = menu_day.data.items;
      console.log('weekday',this.weekdays);
      this.food = foods.data.items;
      this.restaurants = res.data.items;
//       const  restaurant = await restaurantResource.get(2);
console.log('all food', foods );
      this.listLoading = false;
    },
    create() {
      this.$router.push({path: '/food/create'});
    },
    async editData(id) {

      const res = await foodResource.get(id);
      this.food_edit.id=res.data.item.id;
      this.food_edit.name = res.data.item.name;
      this.food_edit.price = res.data.item.price;
      this.food_edit.image = res.data.item.image;
      this.food_edit.description = res.data.item.description;
      this.food_edit.restaurant_id = res.data.item.restaurant_id;
      console.log('Kien',res);
      const list_day = await foodResource.list({
        food_id: id,
      });
      this.array = [];
      for (var i = 0; i < list_day.data.items.length; i++) {
        this.array = this.array.concat(Object.values(list_day.data.items[i]))
      }
      console.log('List Day', this.array);

     // console.log('list_day',list_day);
    },
    upload(){
      var token = this.$cookies.get('Admin-Token');
      window.open(`/laravel-filemanager?token=` + token, '_blank', 'width=900,height=600')
      var self = this
      window.SetUrl = function (items) {
        var len = self.url.length;
        var length = items[0].url.length;
        self.food_edit.image = items[0].url.slice(len, length);
      }
      return false
    },
    async save() {
    this.food_edit.menu_id = this.array;
    //console.log('list menu',this.food_edit.menu_id);
      const res = await foodResource.update(this.food_edit.id, this.food_edit);
      this.drawer_edit = false;
      toast.fire({
        icon: 'success',
        title: 'Cập nhật món ăn thành công',
      })
      this.fetchData();

    },
    async deleteData(id) {
      if (confirm("Bạn có chắc chắn muốn xóa")) {
        const res = await foodResource.destroy(id);
        this.fetchData();
        toast.fire({
          icon: 'success',
          title: 'Món ăn đã được xóa',
        })
      }
    },


  },
};
</script>

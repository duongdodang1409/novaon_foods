<template>
  <div class="app-container">
    <el-row class="toolbar" style="margin-bottom: 20px;">
      <el-col :span="12">
        <el-button type="primary" @click="create()" icon="el-icon-plus"
                   plain size="small" style="float: right;">{{ $t('my_lang.create') }}
        </el-button>
      </el-col>
    </el-row>
    <el-table v-loading="listLoading" :data="restaurants" element-loading-text="Loading" border fit highlight-current-row>

      <el-table-column align="left" label="Tên quán ăn" prop="brand">
        <template slot-scope="scope">
          <span>{{ scope.row.brand }}</span>
        </template>
      </el-table-column>
      <el-table-column align="left" label="Địa chỉ" prop="address">
        <template slot-scope="scope">
          <span>{{ scope.row.address }}</span>
        </template>
      </el-table-column>
      <el-table-column align="left" label="Số Điện Thoại" prop="phone">
        <template slot-scope="scope">
          <span>{{ scope.row.phone }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('my_lang.action')">
        <template slot-scope="scope">
          <el-button @click="drawer_edit = true; editData(scope.row.id)" type="primary"
                     style="margin-left: 16px;">
            Edit
          </el-button>
          <el-button @click="deleteData(scope.row.id)" type="danger" style="margin-left: 16px;">
            Delete
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-drawer
        title="EDIT BANNER"
        :visible.sync="drawer_edit"
        :direction="direction"
        size="50%">
      <el-form ref="form" :model="restaurant_edit"  label-width="120px">
        <el-form-item label="Tên quán ăn" prop="name">
          <el-input v-model="restaurant_edit.brand"/>
        </el-form-item>
        <el-form-item label="Địa Chỉ" prop="address">
          <el-input v-model="restaurant_edit.address" clearable/>
        </el-form-item>
        <el-form-item label="Số Điện Thoại" prop="phone">
          <el-input v-model="restaurant_edit.phone" clearable/>
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

      drawer_edit: false,
      drawer_delete: false,
      direction: 'rtl',
      list: null,
      listLoading: true,
      restaurants: [{
        id: '',
        brand: '',
        address: '',
        phone:'',

      }],
      restaurant_edit:{
        id: '',
        brand: '',
        address: '',
        phone:'',
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
    async fetchData() {
      this.listLoading = true;
      const res = await restaurantResource.list();
      this.restaurants = res.data.items;
      this.listLoading = false;
    },
    create() {
      this.$router.push({path: '/restaurant/create'});
    },
    async editData(id) {

      const res = await restaurantResource.get(id);
      this.restaurant_edit.id=res.data.item.id;
      this.restaurant_edit.brand = res.data.item.brand;
      this.restaurant_edit.address = res.data.item.address;
      this.restaurant_edit.phone = res.data.item.phone;
      console.log('Kien',res);
    },
    async save() {

      const res = await restaurantResource.update(this.restaurant_edit.id, this.restaurant_edit);
      this.drawer_edit = false;
      toast.fire({
        icon: 'success',
        title: 'Cập nhật nhà hàng thành công',
      })
      this.fetchData();

    },
    async deleteData(id) {
      if (confirm("Bạn có chắc chắn muốn xóa")) {
        const res = await restaurantResource.destroy(id);
        this.fetchData();
        toast.fire({
          icon: 'success',
          title: 'Nhà Hàng đã được xóa',
        })
      }
    },


  },
};
</script>

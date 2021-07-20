<template>
  <div class="app-container">
    <el-form ref="form" :model="restaurant" :rules="rules" label-width="120px">
      <el-form-item label="Tên quán ăn" prop="name">
        <el-input v-model="restaurant.brand"/>
      </el-form-item>
      <el-form-item label="Địa Chỉ" prop="address">
        <el-input v-model="restaurant.address" clearable/>
      </el-form-item>
      <el-form-item label="Số Điện Thoại" prop="phone">
        <el-input v-model="restaurant.phone" clearable/>
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


const restaurantResource = new Resource('v1/restaurants')

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

      list: null,
      listLoading: true,

      restaurant: {
        id: '',
        brand: '',
        address: '',
        phone: '',
      },
      rules: {
        brand: [
          {required: true, message: "Vui lòng nhập tên quán", trigger: 'blur'}
        ],
        address: [
          {required: true, message: "Vui lòng nhập địa chỉ quán", trigger: 'blur'}
        ],
        phone: [
          {required: true, message: "Vui lòng nhập số điện thoại", trigger: 'blur'}
        ],

      },
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    validate(formName) {
      this.$refs[formName].validate(valid => {

        if (valid) this.save()
      });
    },
    async save() {
      const res = await restaurantResource.store(this.restaurant);
      this.$router.push({name: 'ListRestaurant'})

      toast.fire({
        icon: 'success',
        title: 'Tạo nhà hàng thành công!',
      })
    },


  },
};
</script>




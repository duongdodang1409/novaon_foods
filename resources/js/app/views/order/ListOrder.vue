<template>
  <div class="app-container">
    <h3>Tổng Kết Thứ {{this.query.day + 1}} có: {{total}} đơn hàng</h3>
    <el-table v-loading="listLoading" :data="statistical" element-loading-text="Loading" border fit highlight-current-row>
      <el-table-column align="center"  label="Tên Món" prop="name">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Số Lượng" prop="quantity">
        <template slot-scope="scope">
          <span>{{ scope.row.quantity }}</span>
        </template>
      </el-table-column>
    </el-table>
    <h3>Chi Tiết Đơn/Tuần</h3>
    <el-table v-loading="listLoading" :data="customer" element-loading-text="Loading" border fit highlight-current-row>
      <el-table-column align="center" :label="$t('my_lang.name')" prop="name">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Đơn/Tuần" prop="quantity">
        <template slot-scope="scope">
          <span>{{scope.row.quantity}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Số Tiền" prop="price">
        <template slot-scope="scope">
          <span>{{formatPrice(scope.row.price )}} VND</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Lấy cơm" prop="status">
        <template slot-scope="scope" style="color:blue">
          <span v-if="scope.row.status == 1"  >
            <i class="el-icon-circle-check"></i>
                Đã Lấy
          </span>
          <span v-if="scope.row.status == 0" style="color: red">
            <i class="el-icon-warning-outline"></i>
                Chưa lấy
          </span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Chi Tiết">
        <template slot-scope="scope">
          <el-button @click="drawer_edit = true; editData(scope.row.id)" type="primary"
                     icon="el-icon-edit">
          </el-button>
          <el-button @click="viewData(scope.row.id)" type="primary"
                     icon="el-icon-view">
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
        <el-form-item label="Tên" prop="name">
          <el-input v-model="model_edit.name"></el-input>
        </el-form-item>
        <el-form-item label="Lấy cơm" >
          <el-switch v-model="is_activated"></el-switch>
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

const customerResource = new Resource('v1/customers')
const historyResource = new Resource('v1/history')
const orderResource = new Resource('v1/orders')

export default {
  data() {
    return {
      is_activated:true,
      drawer_edit: false,
      direction: 'rtl',
      list: null,
      total:0,
      listLoading: true,
      customer: [{
        name: '',
        property:'',
        quantity: '',
        status:''
      }],
      statistical:[{
        name:'',
        quantity:'',
      }],
      query: {
        first: '',
        last:'',
        day:'',
      },
      model_edit: {
        id: '',
        name: '',
        email: '',
        property:'',
        status:'',

      },

    };
  },
  created() {
    var d = new Date();
    var n = d.getDay();
    this.query.day = n;
    var curr = new Date; // get current date
    var first = curr.getDate() - curr.getDay() ; // First day is the day of the month - the day of the week
    var last = first + 7; // last day is the first day + 6
    var firstday = new Date(curr.setDate(first)).toISOString().slice(0,10);
    var lastday = new Date(curr.setDate(last)).toISOString().slice(0,10);
    this.query.first = firstday;
    this.query.last = lastday;
    this.query.day = n;
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
      const res = await customerResource.list(this.query);
      const order = await orderResource.list(this.query);
      this.statistical = order.data.items;
      this.statistical.forEach(item =>{
        this.total += parseInt(item.quantity);
      } );
      console.log('cútodfhjáhdj',res);
      this.customer = res.data.items;
      this.listLoading = false;
    },
    formatPrice(value) {
      let val = (value/1).toFixed(0).replace('.', ',')
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    },
    async viewData(id){
      this.$router.push({name: 'History', params: {id: id}});
    },
    async editData(id) {

      const res = await customerResource.get(id);
      if (res.data.item.status == 1) {
        this.is_activated = true;
      } else {
        this.is_activated = false;
      }
      this.model_edit = res.data.item;
      this.model_edit.id = res.data.item.id;
      this.model_edit.name = res.data.item.name;
      this.model_edit.email = res.data.item.email;
      this.model_edit.property = res.data.item.property;
      this.model_edit.status = res.data.item.status;

    },
    async save() {
      if (this.is_activated == true) {
        this.model_edit.status = 1;
      } else {
        this.model_edit.status = 0;
      }
      const res = await customerResource.update(this.model_edit.id, this.model_edit);
      this.drawer_edit = false;

      this.fetchData();
      toast.fire({
        icon: 'success',
        title: 'Đã chọn người nhận cơm!',
      })
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

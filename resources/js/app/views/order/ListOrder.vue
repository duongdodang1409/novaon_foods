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
      <el-table-column align="center" label="Chi Tiết">
        <template slot-scope="scope">
          <el-button @click="viewData(scope.row.id)" type="primary"
                     icon="el-icon-view">

          </el-button>
        </template>
      </el-table-column>

    </el-table>



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
      list: null,
      total:0,
      listLoading: true,
      customer: [{
        name: '',
        property:'',
        quantity: '',
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
    if (this.drawer_view == true) this.viewData();
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
      console.log('order',order);
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

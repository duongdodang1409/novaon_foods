<template>
    <div class="app-container">
        <div style="padding-bottom: 30px">
            <h3>Họ và Tên:</h3> {{customer.name}}
            <h3>Số tiền còn lại : </h3>{{formatPrice(customer.property)}} VND
        </div>
        <div style="padding-bottom: 30px">
            <h3>Nạp Thêm Tiền</h3>
            <el-form ref="form" :model="history"  label-width="120px">
                <el-form-item label="Số tiền " prop="money">
                    <el-input v-model="history.money" clearable/>
                </el-form-item>
                <el-button type="primary" @click="validate('form')">Nạp Tiền</el-button>
                </el-form-item>
            </el-form>
        </div>
      <div >
        <h3>Đơn Đặt / Tuần</h3>
        <el-table  :data="order" element-loading-text="Loading" border fit highlight-current-row>
          <el-table-column align="center" label="Tên Món" prop="name">
            <template slot-scope="scope">
              <span>{{ scope.row.name }}</span>
            </template>
          </el-table-column>
          <el-table-column align="center" label="Số Lượng" prop="quantity">
            <template slot-scope="scope">
              <span>{{ scope.row.quantity }}</span>
            </template>
          </el-table-column>
          <el-table-column align="center" label="Số tiền" prop="price">
            <template slot-scope="scope">
              <span>{{formatPrice(scope.row.price )}} VND</span>
            </template>
          </el-table-column>
          <el-table-column align="center" label="Ngày Đặt" prop="created_at">
            <template slot-scope="scope">
              <span>{{ scope.row.created_at }}</span>
            </template>
          </el-table-column>

        </el-table>
      </div>
      <div >
        <h3>Đơn Hủy / Tuần</h3>
        <el-table  :data="cancel" element-loading-text="Loading" border fit highlight-current-row>
          <el-table-column align="center" label="Tên Món" prop="name">
            <template slot-scope="scope">
              <span>{{ scope.row.name }}</span>
            </template>
          </el-table-column>
          <el-table-column align="center" label="Số Lượng" prop="quantity">
            <template slot-scope="scope">
              <span>{{ scope.row.quantity }}</span>
            </template>
          </el-table-column>
          <el-table-column align="center" label="Số tiền" prop="price">
            <template slot-scope="scope">
              <span>{{formatPrice(scope.row.price )}} VND</span>
            </template>
          </el-table-column>
          <el-table-column align="center" label="Ngày Hủy" prop="updated_at">
            <template slot-scope="scope">
              <span>{{ scope.row.updated_at }}</span>
            </template>
          </el-table-column>

        </el-table>
      </div>

      <div >
            <h3>Lịch sử nạp tiền</h3>
            <el-table  :data="tableData" element-loading-text="Loading" border fit highlight-current-row>
                <el-table-column align="center" label="Ngày Nạp" prop="created_at">
                    <template slot-scope="scope">
                        <span>{{ scope.row.created_at }}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="Số tiền" prop="money">
                    <template slot-scope="scope">
                        <span>{{formatPrice(scope.row.money )}} VND</span>
                    </template>
                </el-table-column>

            </el-table>
        </div>

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
            customer: [{
                id: '',
                name: '',
                email: '',
                property:''
            }],
            customer_update:{
                id: '',
                name: '',
                email: '',
                property:'',
            },
            history:{
                id_customer:'',
                money:'',
            },
            tableData:{
                created_at:'',
                money: '',
            },
            cancel:{
              name:'',
              quantity:'',
              price:'',
              updated_at:''
            },
            order:{
              name:'',
              quantity:'',
              price:'',
              created_at:''
          },
            queryCancel:{
              idCustomer:this.$route.params.id,
              first:'',
              last:'',
              status:0,
            },
          queryOrder:{
            idCustomer:this.$route.params.id,
            first:'',
            last:'',
            status:1,
          }


        };
    },
    created() {
      var curr = new Date; // get current date
      var first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week
      var last = first + 7; // last day is the first day + 6
      var firstday = new Date(curr.setDate(first)).toISOString().slice(0, 10);
      var lastday = new Date(curr.setDate(last)).toISOString().slice(0, 10);
      this.queryCancel.first = firstday;
      this.queryCancel.last = lastday;
      this.queryOrder.first = firstday;
      this.queryOrder.last = lastday;
        this.history.id_customer=this.$route.params.id;
        this.customer_update.id=this.$route.params.id;
        this.fetchData();
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
        async save() {
            // console.log("new",this.history.money);
            this.customer_update.property = this.customer.property + parseInt(this.history.money);
            console.log('tong tien', this.customer_update);
            const data = await customerResource.update(this.$route.params.id,this.customer_update);
            const his = await historyResource.store(this.history);
            this.$router.go();


        },
        async fetchData() {
            const res = await customerResource.get(this.$route.params.id);
            //const his = await historyResource.get(7);
           const history = await historyResource.list(this.queryCancel);
           const orders = await orderResource.list(this.queryOrder);
           this.order = orders.data.items;
           const cancels = await orderResource.list(this.queryCancel);
          this.cancel = cancels.data.items;

          // this.tableData.created_at = history.data.items.created_at;
           this.tableData = history.data.items;
            console.log('hehe',res);
           // console.log('his',his);
            this.customer = res.data.item;
            this.customer_update.name = res.data.item.name;
            this.customer_update.email = res.data.item.email;
           // this.customer_update =res.data.item;
        },
    },
};
</script>

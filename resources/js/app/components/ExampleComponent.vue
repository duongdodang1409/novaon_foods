<template>
  <div>
    <div style="padding-bottom: 20px">
      <el-menu
          :default-active="activeIndex2"
          class="el-menu-demo"
          mode="horizontal"
          background-color="#545c64"
          text-color="#fff"
          active-text-color="#ffd04b">
        <el-menu-item index="1">Menu</el-menu-item>
        <el-submenu index="2" v-if="email">
          <template slot="title">{{ email }}</template>
          <el-menu-item index="2-1">Số dư tài khoản:{{ formatPrice(property) }}VND</el-menu-item>
          <el-menu-item index="2-1"><a href="/logout" style="color: white">Đăng xuất</a></el-menu-item>
        </el-submenu>
        <!--        <el-menu-item index="2" v-else><a href="/login">Đăng Nhập Bằng NOVAON-ID</a></el-menu-item>-->
        <el-menu-item>
          <el-button @click="drawer_edit = true"
                     icon="el-icon-shopping-cart-full">{{ badge }}
          </el-button>

        </el-menu-item>
      </el-menu>
    </div>
    <div>
      <el-container style="border: 1px solid #eee">
        <el-aside width="200px" style="background-color: rgb(238, 241, 246)">
          <el-menu>
            <el-submenu index="1">
              <template slot="title"><i class="el-icon-reading"></i>Menu Trong Tuần</template>
              <el-menu-item-group>
                <el-menu-item index="1-1" @click="getID(1)"><i class="el-icon-view"></i>Thứ 2</el-menu-item>
                <el-menu-item index="1-2" @click="getID(2)"><i class="el-icon-view"></i>Thứ 3</el-menu-item>
                <el-menu-item index="1-3" @click="getID(3)"><i class="el-icon-view"></i>Thứ 4</el-menu-item>
                <el-menu-item index="1-4" @click="getID(4)"><i class="el-icon-view"></i>Thứ 5</el-menu-item>
                <el-menu-item index="1-5" @click="getID(5)"><i class="el-icon-view"></i>Thứ 6</el-menu-item>
                <el-menu-item index="1-6" @click="getID(6)"><i class="el-icon-view"></i>Thứ 7</el-menu-item>
              </el-menu-item-group>

            </el-submenu>


          </el-menu>
          <el-menu>
            <el-submenu index="1">
              <template slot="title"><i class="el-icon-reading"></i>Đơn Hàng</template>
              <el-menu-item-group>
                <el-menu-item index="1-1" @click="showHistoryOrder(customer_update.id)"><i class="el-icon-view"></i>Đã
                  Đặt Trong Tuần
                </el-menu-item>
                <el-menu-item index="1-2" @click="showHistoryOrderCancel(customer_update.id)"><i
                    class="el-icon-view"></i>Đơn Hủy
                </el-menu-item>
              </el-menu-item-group>
            </el-submenu>
          </el-menu>
        </el-aside>

        <el-container>
          <el-main>
            <el-row id="Menu-food">
              <el-container>
                <el-header>Menu Thứ {{ this.query.menu_id + 1 }}</el-header>
              </el-container>
              <el-col :span="6" v-for="item in menu1s" v-bind:key="item.id_food" style="padding: 20px 15px;"
                      class="card-food">
                <el-card :body-style="{ padding: '0px' }">
                  <img :src=" url +item.image" class="image">
                  <div style="padding: 14px;">
                    <span>{{ item.name }}</span>
                    <p style="color: red">{{ formatPrice(item.price) }} VND</p>
                    <div class="bottom clearfix">
                      <el-button type="warning" @click="addToCart(item)" style="text-align: center" plain>Thêm Món
                      </el-button>
                    </div>
                  </div>
                </el-card>
              </el-col>
            </el-row>


            <el-row id="History-order">
              <h3>Lịch sử đặt đơn trong tuần</h3>
              <el-table :data="historyOrder">
                <el-table-column align="center" label="Menu" prop="menu" width="95">
                  <template slot-scope="scope">
                    <span>Thứ {{ scope.row.id_menu + 1 }}</span>
                  </template>
                </el-table-column>

                <el-table-column align="center" label="Tên Món" prop="name">
                  <template slot-scope="scope">
                    <span>{{ scope.row.name }}</span>
                  </template>
                </el-table-column>
                <el-table-column align="center" label="Ảnh" prop="image">
                  <template slot-scope="scope">
                    <img v-bind:src="url + scope.row.image" alt="" style="max-height: 100px;"/>
                  </template>
                </el-table-column>
                <el-table-column align="center" label="Số lượng" prop="qty">
                  <template slot-scope="scope">
                    <span>{{ scope.row.quantity }}</span>
                  </template>
                </el-table-column>

                <el-table-column align="center" label="Giá" prop="price">
                  <template slot-scope="scope">
                    <span>{{ formatPrice(scope.row.price) }} VND</span>
                  </template>
                </el-table-column>

                <el-table-column align="center" label="Action">
                  <template slot-scope="scope">
                    <el-button @click="deleteData(scope.row.id,scope.row.id_menu)" type="danger" icon="el-icon-delete">
                    </el-button>
                  </template>
                </el-table-column>

              </el-table>
            </el-row>

            <el-row id="History-order-cancel">
              <h3>Đơn hủy trong tuần</h3>
              <el-table :data="historyOrder">
                <el-table-column align="center" label="Menu" prop="menu" width="95">
                  <template slot-scope="scope">
                    <span>Thứ {{ scope.row.id_menu + 1 }}</span>
                  </template>
                </el-table-column>

                <el-table-column align="center" label="Tên Món" prop="name">
                  <template slot-scope="scope">
                    <span>{{ scope.row.name }}</span>
                  </template>
                </el-table-column>
                <el-table-column align="center" label="Ảnh" prop="image">
                  <template slot-scope="scope">
                    <img v-bind:src="url + scope.row.image" alt="" style="max-height: 100px;"/>
                  </template>
                </el-table-column>
                <el-table-column align="center" label="Số lượng" prop="qty">
                  <template slot-scope="scope">
                    <span>{{ scope.row.quantity }}</span>
                  </template>
                </el-table-column>

                <el-table-column align="center" label="Giá" prop="price">
                  <template slot-scope="scope">
                    <span>{{ formatPrice(scope.row.price) }} VND</span>
                  </template>
                </el-table-column>

              </el-table>
            </el-row>
          </el-main>
        </el-container>
      </el-container>
    </div>
    <el-drawer
        title="Giỏ Hàng"
        :visible.sync="drawer_edit"
        :direction="direction"
        size="50%">
      <template>

        <div>

          <el-row :gutter="24" v-for="(item,n) in cartItems" class="cart" style="padding-left: 20px">
            <el-col :span="4">
              <div class="grid-content bg-purple">
                <img :src=" url +item.image" class="image">
              </div>
            </el-col>
            <el-col :span="8">
              <div style="">
                {{ item.name }}
              </div>
            </el-col>
            <el-col :span="4">
              <div style="">
                {{ formatPrice(item.price) }} VND
              </div>
            </el-col>
            <el-col :span="4">
              <div style="">
                <el-input-number size="mini" v-model="item.qty" @change="handleChange" :min="1"
                                 :max="10"></el-input-number>
              </div>
            </el-col>
            <el-col :span="4">
              <div style="padding-left: 40px">
                <el-button type="danger" @click="removeCart(n)" icon="el-icon-delete" circle></el-button>
              </div>
            </el-col>
          </el-row>
          <el-row v-show="cartItems.length ===0">
            <h3 style="text-align: center">
              <i class="el-icon-remove-outline"></i>
              Giỏ Hàng Rỗng
              <i class="el-icon-remove-outline"></i>
            </h3>
          </el-row>
          <h3 style="padding-left: 30px" v-show="cartItems.length !==0"> Tổng Tiền : {{ formatPrice(total) }} VND</h3>
          <div style="padding-left: 400px" v-show="cartItems.length !==0">
            <el-button type="primary" @click="order()" plain>Đặt Hàng</el-button>

          </div>

        </div>

      </template>
    </el-drawer>

  </div>
</template>

<script>

import Resource from "../api/resource";

const foodResource = new Resource('v1/foods')
const restaurantResource = new Resource('v1/restaurants')
const customerResource = new Resource('v1/customers')
const weekdayResource = new Resource('v1/weekdays')
const orderResource = new Resource('v1/orders')

export default {

  data() {
    return {
      cartItems: [],
      drawer_edit: false,
      drawer_delete: false,
      direction: 'rtl',
      currentDate: new Date(),
      day: '',
      url: process.env.MIX_APP_URL,
      email: '',
      activeIndex2: '1',
      query: {
        menu_id: '',
        email: '',
        first: '',
        last: '',
        idCustomer: '',
        status: '',
      },
      total: 0,
      badge: 0,
      property: '',
      customer: {
        email: '',
      },
      menu1s: [],
      menu1: [{
        id_food: '',
        name: '',
        price: '',
        image: '',
        description: '',
        qty: '',
      }],
      don_hang: {
        id_food: '',
        id_restaurant: '',
        id_customer: '',
        id_menu: '',
        quantity: '',
        price: '',
      },
      customer_update: {
        id: '',
        name: '',
        email: '',
        property: ''
      },
      update_order: {
        id: '',
        quantity: '',
        menu_id: '',
        price: '',
        status: '',
        id_food: '',
        id_restaurant: '',
        id_customer: '',
      },
      historyOrder: [{}],

    }
  },


  created() {
    var d = new Date();
    var n = d.getDay();
    this.day = n;
    var curr = new Date; // get current date
    var first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week
    var last = first + 7; // last day is the first day + 6
    var firstday = new Date(curr.setDate(first)).toISOString().slice(0, 10);
    var lastday = new Date(curr.setDate(last)).toISOString().slice(0, 10);
    this.query.first = firstday;
    this.query.last = lastday;
    this.query.menu_id = n;
    console.log('first', this.query.first);
    console.log('last', this.query.last);

    var email = localStorage.getItem('email');
    this.customer.email = email;
    this.query.email = email;

    this.email = email;
    if (email != '') {
      this.query.email = email;

    } else {
      this.query.email = 'tesst';
    }
    this.fetchData();
    this.viewCart();
  },

  methods: {
    getID(id) {
      document.getElementById('Menu-food').style.display = "block";
      document.getElementById('History-order-cancel').style.display = "none";
      document.getElementById('History-order').style.display = "none";
      this.query.menu_id = id;
      this.fetchData();
    },
    async order() {
      console.log('so tien', this.property);
      console.log('tien dat', this.total);

      if (this.property >= this.total) {
        this.customer_update.property = this.customer_update.property - parseInt(this.total);
        const update_cus = await customerResource.update(this.customer_update.id, this.customer_update);
        for (let i = 0; i < this.cartItems.length; i++) {
          this.don_hang.id_food = this.cartItems[i].id_food;
          this.don_hang.quantity = this.cartItems[i].qty;
          this.don_hang.price = this.cartItems[i].qty * this.cartItems[i].price;
          this.don_hang.id_restaurant = this.cartItems[i].restaurant_id;
          this.don_hang.id_customer = this.customer_update.id;
          this.don_hang.id_menu = this.cartItems[i].id_menu;
          const res = await orderResource.store(this.don_hang);
          // let name = this.cartItems[i].name;
          // let price = this.cartItems[i].price;
          // let data = {
          //   'entry.1094607625': name,
          //   'entry.1996054880': price,
          // }
          // let queryString = new URLSearchParams(data);
          // queryString = queryString.toString();
          // let xhr = new XMLHttpRequest();
          // xhr.open("POST", "https://docs.google.com/forms/u/0/d/e/1FAIpQLSeiwkvgbUHfwuDoVNg1nWmdOdpI3aypI9ZVTLZifkQgoQiK3Q/formResponse", true);
          // xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          // xhr.send(queryString);
        }
        localStorage.removeItem('carts');
        toast.fire({
          icon: 'success',
          title: 'Bạn đặt hàng thành công',
        })
      } else {
        toast.fire({
          icon: 'warning',
          title: 'Bạn không còn đủ tiền !',
        })
      }
      this.drawer_edit = false;
      window.location.href = 'http://127.0.0.1:8000/order';      //this.$route.go();

    },
    handleChange(value) {
      this.cartItems.qty = value;
      this.total = 0;
      this.cartItems.forEach(item => {
        this.total += item.qty * item.price;
      });
      localStorage.getItem('')
      let parsed = JSON.stringify(this.cartItems);
      localStorage.setItem('carts', parsed);
    },
    formatPrice(value) {
      let val = (value / 1).toFixed(0).replace('.', ',')
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    },


    async fetchData() {
      const foods1 = await foodResource.list(this.query);
      const customers = await customerResource.list(this.query);

      if (customers.data.items.length == 0) {
        var pos = this.email.indexOf('@');
        var newstr =   this.email.slice(pos);
        if(newstr.includes("novaon")){
        const res = await customerResource.store(this.customer);
        }
      }
      ;
      this.menu1 = foods1.data.items;
      this.menu1s = this.menu1;
      this.customer_update.name = customers.data.items[0].name;
      this.customer_update.email = customers.data.items[0].email;
      this.customer_update.property = customers.data.items[0].property;
      this.customer_update.id = customers.data.items[0].id;
      this.property = customers.data.items[0].property;
    },


    viewCart() {
      if (localStorage.getItem('carts')) {
        this.cartItems = JSON.parse(localStorage.getItem('carts'));
        this.total = 0;
        this.cartItems.forEach(item => {
          this.total += item.qty * item.price;
        });
        this.badge = this.cartItems.length;
      }
    },
    addToCart(itemToAdd) {
      var date = new Date();
      var hour = date.getHours();
      var minute = date.getMinutes();
      if ((this.day < itemToAdd.id_menu) || ((this.day == itemToAdd.id_menu) && ((hour < 9) || ((hour == 9) && (minute <= 20))))) {
        let found = false;
        let itemInCart = this.cartItems.filter(item => item.id_food === itemToAdd.id_food);
        let isItemInCart = itemInCart.length > 0;
        if (isItemInCart === false) {
          this.cartItems.push(itemToAdd);
        } else {
          itemInCart[0].qty += itemToAdd.qty;
        }
        itemToAdd.qty = 1;
        this.storeCart();
      } else {
        toast.fire({
          icon: 'warning',
          title: 'Quá hạn đặt món này !',
        })
      }
    },
    storeCart() {
      let parsed = JSON.stringify(this.cartItems);
      localStorage.setItem('carts', parsed);
      this.viewCart();
    },
    removeCart(product) {
      this.cartItems.splice(product, 1);
      this.storeCart();
    },

    async showHistoryOrder(id_customer) {
      document.getElementById('Menu-food').style.display = "none";
      document.getElementById('History-order-cancel').style.display = "none";
      document.getElementById('History-order').style.display = "block";
      this.query.idCustomer = id_customer;
      this.query.status = 1;
      const order = await orderResource.list(this.query);
      this.historyOrder = order.data.items;
    },

    async showHistoryOrderCancel(id_customer) {
      document.getElementById('Menu-food').style.display = "none";
      document.getElementById('History-order-cancel').style.display = "block";
      document.getElementById('History-order').style.display = "none";
      this.query.idCustomer = id_customer;
      this.query.status = 0;
      const order = await orderResource.list(this.query);
      this.historyOrder = order.data.items;

    },
    async deleteData(idOrder, idMenu) {
      var date = new Date();
      var hour = date.getHours();
      var minute = date.getMinutes();
      if (confirm("Bạn có chắc chắn muốn xóa đơn hàng?")) {
        if ((this.day < idMenu) || (this.day == idMenu && ((hour < 9) || ((hour == 9) && (minute <= 20))))) {
          const order = await orderResource.get(idOrder);
          this.update_order.id = idOrder;
          this.update_order.menu_id = order.data.item.menu_id;
          this.update_order.price = order.data.item.price;
          this.update_order.status = 0;
          this.update_order.id_food = order.data.item.id_food;
          this.update_order.id_restaurant = order.data.item.id_restaurant;
          this.update_order.quantity = order.data.item.quantity;
          this.update_order.id_customer = this.customer_update.id;
          console.log('order_update', this.update_order);
          this.customer_update.property = this.customer_update.property + parseInt(order.data.item.price);
          const update_cus = await customerResource.update(this.customer_update.id, this.customer_update);
          const update_order = await orderResource.update(this.update_order.id, this.update_order);
          window.location.href = 'http://127.0.0.1:8000/order';
        } else {
          toast.fire({
            icon: 'warning',
            title: 'Quá hạn hủy đơn này !',
          })
        }
      }
    },
  }

}

</script>
<style>
#History-order, #History-order-cancel {
  display: none;
}

.bottom {
  margin-top: 13px;
  line-height: 12px;
}

.button {
  padding: 0;
  float: right;
}

.image {
  width: 100%;
  display: block;
  height: 200px;

}

.cart .image {
  /*border-radius: 50%;*/
  /*width: 70%;*/
  max-height: 80px;
}

.clearfix:before,
.clearfix:after {
  display: table;
  content: "";
}

.clearfix:after {
  clear: both
}

.el-header {
  background-color: #B3C0D1;
  color: #333;
  text-align: center;
  line-height: 60px;
}

.el-row {
  margin-bottom: 20px;

&
:last-child {
  margin-bottom: 0;
}

}
.el-col {
  border-radius: 4px;
}


element.style {
  padding: 20px 15px;
  height: 320px;
  width: 220px;
}

.el-c
.grid-content {
  border-radius: 4px;
  min-height: 36px;
}

.row-bg {
  padding: 10px 0;
  background-color: #f9fafc;
}

.card-food .image {
  height: 250px;
}

</style>
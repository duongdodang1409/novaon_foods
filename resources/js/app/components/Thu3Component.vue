<template>
    <div>
        <h3>Thứ 3</h3>
        <el-table
            :data="tableData"
            style="width: 100%"
            max-height="250">
            <el-table-column
                fixed
                prop="name"
                label="Tên Món"
                width="600">
            </el-table-column>
            <el-table-column
                prop="price"
                label="Giá"
                width="120">
            </el-table-column>
            <el-table-column align="center" label="Đặt Hàng">
                <template slot-scope="scope">
                    <el-button @click="order(scope.row.id_food)" type="danger" style="margin-left: 16px;">
                        Đặt Món
                    </el-button>
                </template>
            </el-table-column>  </el-table>

    </div>
</template>

<script>
import Resource from "../api/resource";

const foodResource = new Resource('v1/foods')
const restaurantResource = new Resource('v1/restaurants')
const customerResource =  new Resource('v1/customers')

export default {

    data() {
        return {
            length:'',
            query: {
                menu_id: '2',
                email:'',
            },
            tableData: [{
                id_food:'',
                name:'',
                price:'',
            }],
            customer_update:{
                id:'',
                name:'',
                email:'',
                property:''
            }

        }
    },
    created() {

        var email = localStorage.getItem('email');
        if(email != ''){
            this.query.email = email;

        }else {
            this.query.email = 'tesst';

        }
        console.log("email que",       this.query.email);
        this.fetchData();
    },
    methods: {
        async fetchData() {
            const foods = await foodResource.list(this.query);
            const customers = await customerResource.list(this.query);
            this.length =  customers.data.items.length;
            console.log('menufood',foods);
            console.log("customer",customers);
            this.tableData = foods.data.items;
            this.customer_update.name = customers.data.items[0].name;
            this.customer_update.email = customers.data.items[0].email;
            this.customer_update.property = customers.data.items[0].property;
            this.customer_update.id = customers.data.items[0].id;

        },
        async order(id_food) {
            if(this.length > 0){
                const food_id = await foodResource.get(id_food);
                // console.log("this customer",this.customer_update);
                console.log('foodid', food_id);
                let name = food_id.data.item.name;
                let price = food_id.data.item.price;
                let restaurant_id = food_id.data.item.restaurant_id;
                if (confirm("Bạn có chắc chắn đặt  " + name + " ?")) {

                    if (parseInt(price) <= this.customer_update.property) {
                        this.customer_update.property = this.customer_update.property - parseInt(price);
                        const update_cus = await customerResource.update(this.customer_update.id, this.customer_update);
                        const restaurant = await restaurantResource.get(restaurant_id);
                        let brand = restaurant.data.item.brand;
                        console.log("restaurant", brand);
                        let data = {
                            'entry.1094607625': name,
                            'entry.2136475780': brand,
                            'entry.1996054880': price,
                            'entry.1240292884':this.customer_update.name
                        }
                        console.log("data ne", data);
                        let queryString = new URLSearchParams(data);
                        queryString = queryString.toString();
                        console.log("string", queryString);
                        let xhr = new XMLHttpRequest();
                        xhr.open("POST", "https://docs.google.com/forms/u/0/d/e/1FAIpQLSeiwkvgbUHfwuDoVNg1nWmdOdpI3aypI9ZVTLZifkQgoQiK3Q/formResponse", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.send(queryString);
                        toast.fire({
                            icon: 'success',
                            title: 'Bạn đặt món thành công!',
                        })

                    } else {
                        alert("Ban Can Nap Them Tien");
                    }
                }
            }else{
                alert("Bạn Cần Đăng Nhập NOVAON ID");
            }


        },
    }
}
</script>

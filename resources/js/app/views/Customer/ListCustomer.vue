<template>
    <div class="app-container">
        <el-row class="toolbar" style="margin-bottom: 20px;">
            <el-form :model="query">
                <el-col :span="4" style="padding-right: 20px">
                    <el-form-item prop="keySearch">
                        <el-input placeholder="Email Khách Hàng " clearable v-model="query.keySearch"/>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item>
                        <el-button type="primary" @click="search(query.keySearch)">Search</el-button>
                    </el-form-item>
                </el-col>
            </el-form>
            <el-col :span="12">
                <el-button type="primary" @click="create()" icon="el-icon-plus"
                           plain size="small" style="float: right;">{{ $t('my_lang.create') }}
                </el-button>
            </el-col>
        </el-row>
        <el-table v-loading="listLoading" :data="customer" element-loading-text="Loading" border fit highlight-current-row>
            <el-table-column align="center" :label="$t('my_lang.name')" prop="name">
                <template slot-scope="scope">
                    <span>{{ scope.row.name }}</span>
                </template>
            </el-table-column>
            <el-table-column align="center" :label="$t('my_lang.email')" prop="email">
                <template slot-scope="scope">
                    <span>{{ scope.row.email }}</span>
                </template>
            </el-table-column>
            <el-table-column align="center" label="Số dư" prop="property">
                <template slot-scope="scope">
                    <span>{{formatPrice(scope.row.property)}} VND</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="Action">
                <template slot-scope="scope">
                    <el-button @click="drawer_edit = true; editData(scope.row.id)" type="primary"
                               icon="el-icon-edit">
                    </el-button>
                    <el-button @click="viewData(scope.row.id)" type="primary"
                               icon="el-icon-view">
                    </el-button>
                    <el-button @click="deleteData(scope.row.id)" type="danger" icon="el-icon-delete">
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
                <el-form-item label="Email" prop="email">
                    <el-input  v-model="model_edit.email" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="Số dư" prop="property">
                    <el-input  v-model="model_edit.property" autocomplete="off">
                    </el-input>
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
export default {
    data() {
        return {
            tien_cu:'',
            drawer_edit: false,
            drawer_delete: false,
            drawer_view:false,
            direction: 'rtl',
            list: null,
            listLoading: true,
            customer: [{
                id: '',
                name: '',
                email: '',
                property:'',
            }],
            model_edit: {
                id: '',
                name: '',
                email: '',
                property:''
            },
            query: {
                keySearch: '',
            },
            history:{
                id_customer:'',
                money:'',
            }

        };
    },
    created() {
        this.fetchData();
        if (this.drawer_edit == true) this.editData();
        if (this.drawer_view == true) this.viewData();
    },
    methods: {

        changePage(page) {
            this.query.page = page;
            console.log(page);
            this.fetchData();
        },
        validate(formName) {
            this.$refs[formName].validate(valid => {
                console.log(valid)
                if (valid) this.save()
            });
        },
        async search(keySearch) {
            this.query.keySearch = keySearch;
            console.log(this.query.keySearch);
            this.fetchData();
        },
        async fetchData() {
            this.listLoading = true;
            const res = await customerResource.list(this.query);
            console.log('list cus',res);
            this.customer = res.data.items;
            this.listLoading = false;
        },
      formatPrice(value) {
        let val = (value/1).toFixed(0).replace('.', ',')
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
      },
        create() {
            this.$router.push({path: '/customer/create'});
        },
        async viewData(id){
            this.$router.push({name: 'History', params: {id: id}});
        },
        async editData(id) {
            const res = await customerResource.get(id);
            this.model_edit = res.data.item;
            this.model_edit.id = res.data.item.id;
            this.model_edit.name = res.data.item.name;
            this.model_edit.email = res.data.item.email;
            this.model_edit.property = res.data.item.property;
        },
        async save() {
            const res = await customerResource.update(this.model_edit.id, this.model_edit);
            this.drawer_edit = false;
            this.drawer_view = false;

            this.fetchData();
            toast.fire({
                icon: 'success',
                title: 'Khách hàng đã được update',
            })
        },
        async deleteData(id) {
            if (confirm("Bạn có chắc chắn muốn xóa khách hàng?")) {
                const res = await customerResource.destroy(id);
                this.fetchData();
                toast.fire({
                    icon: 'success',
                    title: 'Khách Hàng Đã Được Xóa !',
                })
            }
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

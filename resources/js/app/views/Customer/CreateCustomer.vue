<template>
    <div class="app-container">
        <el-form ref="form" :model="customer" :rules="rules" label-width="120px">
            <el-form-item label="Tên " prop="name">
                <el-input v-model="customer.name"/>
            </el-form-item>
            <el-form-item label="Email" prop="email">
                <el-input v-model="customer.email" clearable/>
            </el-form-item>

            <el-form-item label="Số tiền " prop="property">
                <el-input v-model="customer.property" clearable/>
            </el-form-item>

            <el-button type="primary" @click="validate('form')">Save</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import {getList} from '@/api/table';
import Resource from '../../api/resource'


const customerResource = new Resource('v1/customers')


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
            customer: {
                id: '',
                name: '',
                email: '',
                property: '',
            },
            rules: {
                name: [
                    {required: true, message: "Vui lòng nhập tên ", trigger: 'blur'}
                ],
                email: [
                    {required: true, message: "Vui lòng nhập Email", trigger: 'blur'}
                ],
                property: [
                    {required: true, message: "Vui lòng nhập Số tiền", trigger: 'blur'}
                ],


            },
        };
    },

    methods: {
        validate(formName) {
            this.$refs[formName].validate(valid => {
                if (valid) this.save()
            });
        },
        async save() {
            const res = await customerResource.store(this.customer)
        },


    },
};
</script>




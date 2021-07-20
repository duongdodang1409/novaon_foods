<template>
  <div class="app-container">
    <el-form :model="user" status-icon :rules="rules" ref="form" label-width="120px" class="demo-ruleForm">
      <el-form-item :label="$t('my_lang.name')" prop="name">
        <el-input v-model="user.name"></el-input>
      </el-form-item>
      <el-form-item :label="$t('my_lang.email')" prop="email"
                    :rules="[{ type: 'email', message: 'Please input correct email address', trigger: ['blur', 'change'] }]">
        <el-input v-model="user.email"></el-input>
      </el-form-item>
      <el-form-item :label="$t('my_lang.password')" prop="password">
        <el-input type="password" v-model="user.password" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="Confirm" prop="checkPass">
        <el-input type="password" v-model="user.checkPass" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="Role" prop="role">
        <el-select v-model="user.role" placeholder="Select">
          <el-option
            v-for="item in roles"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="validate('form')">Submit</el-button>
        <el-button @click="resetForm('form')">Reset</el-button>
      </el-form-item>
    </el-form>

  </div>
</template>
<script>


import Resource from '../../api/resource'

const userResource = new Resource('v1/users')
export default {
  data() {
    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('Please input the password again'));
      } else if (value !== this.user.password) {
        callback(new Error('Confirm Password is not correct!'));
      } else {
        callback();
      }
    };
    return {
      roles: [
        {
          value: "admin",
          label: "Admin",
        },
        {
          value: "user",
          label: "User",
        }
      ],
      user: {
        id: '',
        name: '',
        email: '',
        password: '',
        role: "user",
        actived: '',
        checkPass: '',
      },
      rules: {
        name: [
          {required: true, message: "Vui lòng nhập Tên", trigger: 'blur'}
        ],
        email: [
          {required: true, message: "Vui lòng nhập Email", trigger: 'blur'}
        ],
        password: [
          {required: true, message: "Vui lòng nhập Password", trigger: 'blur'}
        ],
        checkPass: [
          {validator: validatePass2, trigger: 'blur'}
        ],
      },

    }
  },
  methods: {
    validate(formName) {
      this.$refs[formName].validate(valid => {
        console.log(valid)
        if (valid) this.save()
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    async save() {
      const res = await userResource.store(this.user);
      this.$router.push({path: '/users'})
      toast.fire({
        icon: 'success',
        title: 'User has been created',
      })
    },

  }
}
</script>

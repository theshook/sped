<template>
  <div>
    <b-card class="col-lg-5 shadow-sm mx-auto mt-lg-5 mt-sm-3 d-none">
      <h5>Sign In to your account</h5>

      <b-form class="mt-lg-3 mt-sm-5">
        <b-form-group label="Email Address*" label-class="text-sm">
          <b-form-input
            class="text-sm py-4"
            v-model="$v.form.email.$model"
            :state="validateState('email')"
            placeholder="Enter your email address"
            aria-describedby="input-email-feedback"
          ></b-form-input>

          <b-form-invalid-feedback id="input-email-feedback">
            Email Address is required and must be
            valid
          </b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Password*" label-class="text-sm">
          <b-form-input
            class="text-sm py-4"
            v-model="$v.form.password.$model"
            :state="validateState('password')"
            placeholder="Enter your password"
            aria-describedby="input-password-feedback"
          ></b-form-input>

          <b-form-invalid-feedback id="input-password-feedback">Password is required</b-form-invalid-feedback>
        </b-form-group>

        <b-button class="font-weight-bold py-2 mb-3" variant="primary" block>Sign In</b-button>
        <a href="#" class="text-sm">Forgot Password?</a>
      </b-form>
    </b-card>
  </div>
</template>
<script>
import {
  email,
  required,
  minLength,
  maxLength
} from "vuelidate/lib/validators";
export default {
  name: "ParentLogin",
  props: ["host"],
  data() {
    return {
      form: {
        email: null,
        password: null
      }
    };
  },
  computed: {},
  validations: {
    form: {
      email: {
        required,
        email
      },
      password: {
        required
      }
    }
  },
  mounted() {},
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    }
  }
};
</script>

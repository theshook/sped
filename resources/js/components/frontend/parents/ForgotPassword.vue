<template>
  <div>
    <b-card class="col-lg-4 shadow-sm mx-lg-auto mt-lg-5">
      <h3>Forgot Password?</h3>

      <p class="text-muted">Send yourself password reset instructions.</p>

      <b-form class="mt-sm-3">
        <b-form-group label="Email Address*" label-class="text-sm">
          <b-form-input
            class="text-sm form-control-lg py-4"
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

        <b-button
          class="btn-submit font-weight-bold py-3 mb-3 float-right"
          variant="primary"
        >Send instructions</b-button>
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
  name: "ParentForgotPassword",
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

<template>
  <div>
    <b-form>
      <h3>
        <strong>Forgot</strong>
        <span class="text-muted">your password?</span>
      </h3>
      <small class="text-muted">Reset your password.</small>

      <div class="input-box">
        <label for="email" class="input-label">E-mail</label>
        <b-form-input type="email" id="email" placeholder="Your e-mail"></b-form-input>
      </div>

      <b-button variant="primary" class="py-3 px-3 my-3" block>Submit</b-button>
    </b-form>
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
        email: null
      }
    };
  },
  computed: {},
  validations: {
    form: {
      email: {
        required,
        email
      }
    }
  },
  mounted() {},
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    validateForm: function() {},

    submit: function() {}
  }
};
</script>

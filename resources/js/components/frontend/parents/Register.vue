<template>
  <div>
    <b-form>
      <h3 class="font-weight-bold">Parent register</h3>
      <small class="text-muted">Manage and view your child's documents.</small>

      <div class="input-box">
        <label for="name" class="input-label">Name</label>
        <b-form-input id="name" placeholder="Your name"></b-form-input>
      </div>

      <div class="input-box">
        <label for="email" class="input-label">E-mail</label>
        <b-form-input id="email" placeholder="Your e-mail"></b-form-input>
      </div>

      <div class="input-box">
        <label for="password" class="input-label">Password</label>
        <b-form-input type="password" id="password" placeholder="Your password"></b-form-input>
      </div>

      <div class="input-box">
        <label for="cpassword" class="input-label">Confirm Password</label>
        <b-form-input type="password" id="cpassword" placeholder="Re-type your password"></b-form-input>
      </div>

      <div class="d-flex align-items-baseline mb-3">
        <b-form-checkbox class="mr-auto">
          <small class="text-muted">
            I accept
            <a href="#">Terms of Service</a>
            and
            <a href="#">Privacy Policy</a>
            .
          </small>
        </b-form-checkbox>
      </div>

      <b-button variant="primary" class="py-3 px-3 mb-3" block>Continue</b-button>

      <p class="font-weight-bold text-muted text-sm">
        Already have an account?
        <a href="/parent/login">Click here</a>
      </p>
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
    },

    validateForm: function() {},

    register: function() {}
  }
};
</script>

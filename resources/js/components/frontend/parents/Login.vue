<template>
  <div>
    <b-form>
      <h3 class="font-weight-bold">Parent log in</h3>
      <small class="text-muted">Manage and view your child's documents.</small>

      <p class="text-sm text-danger mt-2 mb-1" v-if="login_invalid">
        <b-icon icon="exclamation-triangle-fill" class="mr-2"></b-icon>This user was not found, try again.
      </p>

      <div class="input-box">
        <label for="email" class="input-label">E-mail</label>
        <b-form-input id="email" placeholder="eg: name@domain.com"></b-form-input>
      </div>

      <div class="input-box">
        <label for="password" class="input-label">Password</label>
        <b-form-input type="password" id="password" placeholder="********"></b-form-input>
      </div>

      <div class="d-flex align-items-baseline mb-3">
        <b-form-checkbox class="mr-auto">
          <small class="text-muted">Remember me</small>
        </b-form-checkbox>

        <a href="/parent/forgot/password" class="text-sm ml-auto">Forgot password?</a>
      </div>

      <b-button variant="primary" class="py-3 px-3 mb-3" block>
        Log In
        <b-icon icon="arrow-right" class="ml-2"></b-icon>
      </b-button>

      <p class="text-muted text-sm">
        Don't have an account?
        <a
          href="/parent/register"
          class="font-weight-bold text-decoration-none"
        >Click here</a>
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
      login_invalid: false,
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

    login: function() {}
  }
};
</script>

import Vue from "vue";

//FRONTEND (Parent)
Vue.component(
  "parent-login",
  require("./components/frontend/Parents/Login.vue").default
);

Vue.component(
  "parent-register",
  require("./components/frontend/Parents/Register.vue").default
);

Vue.component(
  "parent-forgot-password",
  require("./components/frontend/Parents/ForgotPassword.vue").default
);

Vue.component(
  "intake-form",
  require("./components/frontend/Parents/IntakeForm.vue").default
);

//FRONTEND (PUPIL)
Vue.component(
  "pupil-assessments-index",
  require("./components/frontend/Pupils/Assessments/Tests/Index.vue").default
);

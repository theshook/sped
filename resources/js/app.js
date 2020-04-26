require("./bootstrap");

//Vue
import Vue from "vue";

//Sweetalert
import Swal from "sweetalert2";

//Bootstrap-vue
import { BootstrapVue, BootstrapVueIcons } from "bootstrap-vue";
//Bootstrap
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
//Vuelidate
import Vuelidate from "vuelidate";
//CkEditor5
import CKEditor from "@ckeditor/ckeditor5-vue";

Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);
Vue.use(Vuelidate);
Vue.use(CKEditor);
window.swal = Swal;

//Import frontend components
require("./components-frontend");
//Import backend components
require("./components-backend");

Vue.prototype.$user = document
  .querySelector("meta[name='user']")
  .getAttribute("content");

const app = new Vue({
  el: "#app"
});

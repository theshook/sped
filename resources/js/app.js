/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require("./bootstrap");

//Vue
import Vue from "vue";

//Sweetalert
import Swal from "sweetalert2";

//Bootstrap-vue
import {
    BootstrapVue,
    BootstrapVueIcons
} from "bootstrap-vue";
//Bootstrap
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
//Vuelidate
import Vuelidate from "vuelidate";

Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);
Vue.use(Vuelidate);

// window.Vue = require("vue");
window.swal = Swal;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//BACKEND (Provinces)
Vue.component(
    "provinces-index",
    require("./components/backend/Province/Index.vue").default
);
//BACKEND (Schools)
Vue.component(
    "schools-index",
    require("./components/backend/Schools/Index.vue").default
);
//BACKEND (Pupils)
Vue.component(
    "pupils-index",
    require("./components/backend/Pupils/Index.vue").default
);
//BACKEND (Teachers)
Vue.component(
    "teachers-index",
    require("./components/backend/Teachers/Index.vue").default
);
//BACKEND (Tests)
Vue.component(
    "tests-index",
    require("./components/backend/Tests/Index.vue").default
);
//BACKEND (Tests - manage questions to test)
Vue.component(
    "test-manage-questions",
    require("./components/backend/Tests/TestManageQuestions.vue").default
);
//BACKEND (Teacher-Questions)
Vue.component(
    "teacher-questions-index",
    require("./components/backend/AssessmentQuestions/Index.vue").default
);

/** ChecklistCategories & Checklists */
Vue.component(
    "checklist-categories-index",
    require("./components/backend/ChecklistCategories/Index.vue").default
);
Vue.component(
    "checklists-index",
    require("./components/backend/Checklists/Index.vue").default
);
/** END ChecklistCategories & Checklists */
//BACKEND (Reports)
Vue.component(
    "reports-index",
    require("./components/backend/Reports/Index.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app"
});

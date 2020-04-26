import Vue from "vue";

//BACKEND (Provinces)
Vue.component(
  "provinces-index",
  require("./components/backend/Province/Index.vue").default
);
//BACKEND (Provinces-TRASH)
Vue.component(
  "provinces-trash",
  require("./components/backend/Province/Trash.vue").default
);
//BACKEND (Schools)
Vue.component(
  "schools-index",
  require("./components/backend/Schools/Index.vue").default
);
//BACKEND (Schools- TRASH)
Vue.component(
  "schools-trash",
  require("./components/backend/Schools/Trash.vue").default
);
//BACKEND (Pupils)
Vue.component(
  "pupils-index",
  require("./components/backend/Pupils/Index.vue").default
);
//BACKEND (Teachers- TRASH)
Vue.component(
  "pupils-trash",
  require("./components/backend/Pupils/Trash.vue").default
);
//BACKEND (Teachers)
Vue.component(
  "teachers-index",
  require("./components/backend/Teachers/Index.vue").default
);
//BACKEND (Teachers- TRASH)
Vue.component(
  "teachers-trash",
  require("./components/backend/Teachers/Trash.vue").default
);
//BACKEND (Tests)
Vue.component(
  "tests-index",
  require("./components/backend/Tests/Index.vue").default
);
//BACKEND (Schools- TRASH)
Vue.component(
  "tests-trash",
  require("./components/backend/Tests/Trash.vue").default
);
//BACKEND (Tests - manage questions to test)
Vue.component(
  "test-manage-questions",
  require("./components/backend/Tests/Manage/Questions.vue").default
); //BACKEND (Tests - show)
Vue.component(
  "test-show",
  require("./components/backend/Tests/Show.vue").default
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

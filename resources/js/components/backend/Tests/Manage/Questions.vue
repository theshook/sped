<template>
  <div>
    <b-container class="mt-2">
      <div class="page-header">
        <div class="d-flex align-items-baseline">
          <h2 class="font-weight-bold">Manage Questions</h2>

          <b-button
            v-b-modal.confirm-added-questions-modal
            variant="success"
            size="sm"
            class="ml-auto"
          >
            <b-icon icon="check-circle" class="mr-2"></b-icon>Save Changes
          </b-button>
        </div>

        <b-breadcrumb :items="breadcrumb_link"></b-breadcrumb>
      </div>

      <b-form-row>
        <b-col lg="6">
          <b-card class="shadow-sm mb-4" no-body>
            <b-card-header>
              <div class="d-lg-flex d-sm-block align-items-baseline">
                <h6 class="text-black-50 mr-auto mb-0">Available Questions</h6>

                <b-form-select v-model="question_type_filter" size="sm" class="col-lg-6 ml-auto">
                  <b-form-select-option :value="Number(1)">Multiple Choices</b-form-select-option>
                  <b-form-select-option :value="Number(2)">Enumeration</b-form-select-option>
                  <b-form-select-option :value="Number(3)">Identification</b-form-select-option>
                </b-form-select>
              </div>
            </b-card-header>

            <b-card-body>
              <!-- Sorted teacher questions list seperated by question type -->
              <draggable
                :list="sortedTeacherQuestions"
                group="questions_group"
                v-if="show_teacher_questions"
                @change="log"
              >
                <div v-for="(question, index) in sortedTeacherQuestions" :key="index">
                  <b-row class="py-3 border-bottom text-black-50">
                    <b-col lg="6">
                      <small class="mb-0">{{question.question}}</small>
                    </b-col>

                    <b-col lg="6">
                      <div class="d-flex align-items-baseline">
                        <small class="mr-auto mb-0">
                          <span
                            class="badge badge-primary mb-0 p-1"
                            v-if="question.question_type === 1"
                          >Multiple choices</span>
                          <span
                            class="badge badge-info mb-0 p-1"
                            v-if="question.question_type === 2"
                          >Enumeration</span>
                          <span
                            class="badge badge-secondary mb-0 p-1"
                            v-if="question.question_type === 3"
                          >Identification</span>
                        </small>

                        <b-button
                          variant="link"
                          size="sm"
                          class="ml-auto"
                          @click="addQuestion(index)"
                        >
                          <b-icon icon="plus-circle"></b-icon>
                        </b-button>
                      </div>
                    </b-col>
                  </b-row>
                </div>

                <div
                  class="text-center py-5"
                  v-if="!teacher_questions.length || !sortedTeacherQuestions.length"
                >
                  <small class="text-muted mb-0">
                    <b-icon icon="file-earmark" class="mr-1"></b-icon>No questions available.
                  </small>
                </div>
              </draggable>

              <div class="text-center py-5" v-else>
                <b-spinner variant="primary"></b-spinner>
              </div>
            </b-card-body>
          </b-card>
        </b-col>

        <b-col lg="6">
          <b-card class="shadow-sm mb-4" no-body>
            <b-card-header>
              <div class="d-lg-flex d-sm-block align-items-baseline">
                <h6 class="text-black-50 mr-auto mb-0">Added Questions</h6>

                <b-button variant="light" size="sm">
                  <b-icon icon="gear"></b-icon>
                </b-button>
              </div>
            </b-card-header>
            <b-card-body>
              <draggable
                :list="questions"
                group="questions_group"
                v-if="show_teacher_questions"
                @change="log"
              >
                <div v-for="(question, index) in questions" :key="index">
                  <b-row class="py-3 border-bottom text-black-50">
                    <b-col lg="6">
                      <small class="mb-0">{{question.question}}</small>
                    </b-col>

                    <b-col lg="6">
                      <div class="d-flex align-items-baseline">
                        <small class="mr-auto mb-0">
                          <span
                            class="badge badge-primary mb-0 p-1"
                            v-if="question.question_type === 1"
                          >Multiple choices</span>
                          <span
                            class="badge badge-info mb-0 p-1"
                            v-if="question.question_type === 2"
                          >Enumeration</span>
                          <span
                            class="badge badge-secondary mb-0 p-1"
                            v-if="question.question_type === 3"
                          >Identification</span>
                        </small>

                        <b-button
                          variant="link"
                          size="sm"
                          class="ml-auto"
                          @click="removeQuestion(index)"
                        >
                          <b-icon icon="backspace"></b-icon>
                        </b-button>
                      </div>
                    </b-col>
                  </b-row>
                </div>

                <div class="text-center py-5" v-if="!questions.length">
                  <small class="text-muted mb-0">
                    <b-icon icon="file-earmark" class="mr-1"></b-icon>No questions added.
                  </small>
                </div>
              </draggable>

              <div class="text-center py-5" v-else>
                <b-spinner variant="primary"></b-spinner>
              </div>
            </b-card-body>
          </b-card>
        </b-col>
      </b-form-row>
    </b-container>

    <!-- MODALS -->

    <!-- SEARCH -->
    <b-modal id="search-modal" class="bodyless-search-modal" hide-header hide-footer>
      <b-form v-on:submit.prevent>
        <b-form-input
          v-model="search"
          class="form-control"
          placeholder="Search"
          @input="searchQuestion"
          autofocus
        ></b-form-input>
      </b-form>

      <div class="d-flex mt-1 px-2">
        <small class="text-muted mt-1 mr-auto">
          Press
          <span class="badge badge-primary">ESC</span> to close
        </small>

        <div class="ml-auto">
          <b-button variant="link" size="sm" @click="closeSearchModal">Close</b-button>
        </div>
      </div>
    </b-modal>

    <!-- CONFIRM ADDED QUESTIONS -->
    <b-modal
      id="confirm-added-questions-modal"
      title="Save Changes"
      :ok-title="submit_disabled ? 'Saving' : 'Save'"
      :ok-disabled="submit_disabled"
      ok-variant="success"
      @ok="submitAddedQuestions"
      ok-only
      button-size="sm"
    >
      <p
        class="text-sm text-center text-muted mb-1"
      >Do you confirm to save this test questions list?</p>
    </b-modal>
  </div>
</template>

<script>
import { required, minLength, maxLength } from "vuelidate/lib/validators";
import draggable from "vuedraggable";
export default {
  name: "TestQuestionsIndex",
  props: {
    test_id: {
      type: Number,
      required: true
    }
  },
  components: { draggable },
  data() {
    return {
      show_teacher_questions: false,
      submit_disabled: false,
      breadcrumb_link: [
        {
          text: "Dashboard",
          href: "/configure/dashboard"
        },
        {
          text: "Tests",
          href: "/configure/tests"
        },

        {
          text: "Manage Questions",
          href: "#"
        }
      ],
      test: {},
      teacher_questions: [],
      teacher_questions_id: [],
      question_type_filter: 1,
      questions: [],
      questions_type_1: [],
      questions_type_2: [],
      questions_type_3: [],
      unconfirmed_questions_id: [],
      questions_id: [],
      questions_id_qeue: [],
      question_fields: [
        {
          label: "Question",
          key: "question",
          sortable: true
        },
        {
          label: "Action",
          key: "index"
        }
      ],

      search: "",
      limit: 10,
      teacher_questions_curr_page: 1,
      response: {},

      //DELETE
      delete_index: null,
      delete_id: null
    };
  },
  validations: {},
  computed: {
    sortedTeacherQuestions: function() {
      let teacher_questions_filer = this.question_type_filter;
      // 1 = multiple choices || 2 = enumeration || 3 = identification
      switch (teacher_questions_filer) {
        case 1:
          return this.questions_type_1;
          break;

        case 2:
          return this.questions_type_2;
          break;

        case 3:
          return this.questions_type_3;
          break;

        default:
          return (this.teacher_questions = []);
      }
    },

    sortedTestQuestions: function() {}
  },
  mounted() {
    this.getTest();
  },
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    getTest: async function(page) {
      this.show_teacher_questions = false;
      let testAPI = `/test/${this.test_id}`;
      await axios
        .get(testAPI)
        .then(response => {
          this.test = response.data.test;
          this.questions_id = JSON.parse(response.data.questions_id);
          this.questions = response.data.questions;
          this.teacher_questions = response.data.teacher_questions;

          this.teacher_questions.forEach(question =>
            this.teacher_questions_id.push(question.id)
          );

          this.sortQuestions();
          this.removeDoubleQuestions();
        })
        .catch(err => console.log(err))
        .finally(() => (this.show_teacher_questions = true));
    },

    // SORT QUESTIONS BY TYPE (INDIVIDUAL ARRAYS)
    sortQuestions: function() {
      // MULTIPLE CHOICES
      let question_type1 = this.teacher_questions.filter(
        question => question.question_type === 1
      );
      this.questions_type_1 = question_type1;

      // ENUMERATION
      let question_type2 = this.teacher_questions.filter(
        question => question.question_type === 2
      );
      this.questions_type_2 = question_type2;

      // IDENTIFICATION
      let question_type3 = this.teacher_questions.filter(
        question => question.question_type === 3
      );
      this.questions_type_3 = question_type3;
    },

    // REMOVE QUESTION IF BOTH EXIST ON (AVAILABLE/ADDED)
    removeDoubleQuestions: function() {
      this.teacher_questions.forEach((teacher_question, index1) => {
        this.questions_id.forEach((question_id, index2) => {
          if (this.teacher_questions[index1].id === this.questions_id[index2]) {
            this.teacher_questions.splice(index1, 1);
          }
        });
      });

      // FOR ADD QUESTIONS METHODS
      // REMOVE QUESTION ID TO IT'S ARRAY (BY TYPE)

      // MULTIPLE CHOICES
      if (this.questions_type_1.length > 0 && this.question_type_filter == 1) {
        this.questions_id.forEach((question_id, index1) => {
          this.questions_type_1.forEach((question_type_1, index2) => {
            if (
              this.questions_id[index1] === this.questions_type_1[index2].id
            ) {
              this.questions_type_1.splice(index2, 1);
            }
          });
        });
      }

      // ENUMERATION
      if (this.questions_type_2.length > 0 && this.question_type_filter == 2) {
        this.questions_id.forEach((question_id, index1) => {
          this.questions_type_2.forEach((question_type_2, index2) => {
            if (
              this.questions_id[index1] === this.questions_type_2[index2].id
            ) {
              this.questions_type_2.splice(index2, 1);
            }
          });
        });
      }

      // IDENTIFICATION
      if (this.questions_type_3.length > 0 && this.question_type_filter == 3) {
        this.questions_id.forEach((question_id, index1) => {
          this.questions_type_3.forEach((question_type_3, index2) => {
            if (
              this.questions_id[index1] === this.questions_type_3[index2].id
            ) {
              this.questions_type_3.splice(index2, 1);
            }
          });
        });
      }
    },

    searchQuestion: function() {
      let search = this.search.toLowerCase();

      if (!search) {
        this.getQuestions();
      }

      this.questions = this.questions.filter(
        question => question.name.indexOf(search) > -1
      );
    },

    // ADD-REMOVE QUESTIONS FROM THE TEST
    addQuestion: function(index) {
      let question = null;
      switch (this.question_type_filter) {
        case 1:
          question = this.questions_type_1[index];
          break;

        case 2:
          question = this.questions_type_2[index];
          break;

        case 3:
          question = this.questions_type_3[index];
          break;
      }

      const { id, question_type } = question;

      this.questions_id.push(id);
      this.unconfirmed_questions_id.push(id);
      this.questions.push(question);
      this.teacher_questions.splice(index, 1); // Remove browsed question to teacher questions

      this.removeDoubleQuestions();
    },

    removeQuestion: function(index) {
      let question = this.questions[index];
      const { id, question_type } = question;

      this.teacher_questions_id.push(id); // Add question id to teacher questions
      // Check question type
      switch (question_type) {
        case 1:
          this.questions_type_1.push(question);
          break;

        case 2:
          this.questions_type_2.push(question);
          break;

        case 3:
          this.questions_type_3.push(question);
          break;
      }

      this.questions.splice(index, 1); // Remove question from test questions
    },

    //CONFIRM ADDED TEACHER QUESTIONS TO TEST QUESTIONS
    submitAddedQuestions: function(event) {
      event.preventDefault();
      this.confirmAddedQuestions();
    },

    confirmAddedQuestions: function() {
      this.submit_disabled = true;
      let testAPI = `/test/${this.test.id}`;

      const data = {
        questions_id: this.questions_id
      };

      axios
        .put(testAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.getTest();
            this.$bvModal.hide("confirm-added-questions-modal");

            swal.fire({
              icon: "success",
              title: "Added",
              text: "Test questions successfully added",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to add test questions",
              timer: 3000
            });
          }
        })
        .catch(err => {
          swal.fire({
            icon: "error",
            title: err.response.data.message,
            text: err.response.data.errors.name[0],
            timer: 3000
          });
        })
        .finally(() => (this.submit_disabled = false));
    },

    resetSearch: function() {
      this.search = "";
      this.getQuestions();
    },

    closeSearchModal: function() {
      this.$bvModal.hide("search-modal");
    },

    log: function(evt) {
      console.log(evt);
    }
  }
};
</script>

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
          <b-card class="shadow-sm mb-2" no-body>
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
              <!-- Sorted teacher questions list from computed sortedTeacherQuestions() -->
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

                <div class="text-center py-5" v-if="!teacher_questions.length">
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
          <b-card class="shadow-sm mb-2" no-body>
            <b-card-header>
              <div class="d-lg-flex d-sm-block align-items-baseline">
                <h6 class="text-black-50 mr-auto mb-0">Added Questions</h6>

                <b-button variant="light" size="sm">
                  <b-icon icon="gear"></b-icon>
                </b-button>
              </div>
            </b-card-header>
            <b-card-body>
              <draggable :list="questions" group="questions_group" @change="log">
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
          href: "/dashboard"
        },
        {
          text: "Test",
          href: "#"
        },

        {
          text: "Manage Question",
          href: "#"
        }
      ],
      test: {},
      teacher_questions: [],
      question_type_filter: 1,
      questions: [],
      questions_id: [],
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
          return this.teacher_questions.filter(
            question => question.question_type === 1
          );
          break;

        case 2:
          return this.teacher_questions.filter(
            question => question.question_type === 2
          );
          break;

        case 3:
          return this.teacher_questions.filter(
            question => question.question_type === 3
          );
          break;

        default:
          return (this.teacher_questions = []);
      }
    }
  },
  mounted() {
    this.getTest();
  },
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    getTest: function(page) {
      this.show_teacher_questions = false;
      let testAPI = `/test/${this.test_id}`;
      axios
        .get(testAPI)
        .then(response => {
          this.test = response.data.test;
          this.questions_id = response.data.questions.id;
          this.questions = response.data.questions;
          this.teacher_questions = response.data.teacher_questions;
        })
        .catch(err => console.log(err))
        .finally(() => (this.show_teacher_questions = true));
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
      let teacher_question_id = this.teacher_questions[index].id;
      let teacher_question = this.teacher_questions[index];

      this.questions.push(teacher_question); // Add browsed question to test questions list
      this.teacher_questions.splice(index, 1); // Remove browsed question to teacher questions
    },

    removeQuestion: function(index) {
      let question_id = this.questions[index].id;
      let question = this.questions[index];

      this.teacher_questions.push(question); // Add question to teacher questions
      this.questions.splice(index, 1); // Remove question to test questions
    },

    //CONFIRM ADDED TEACHER QUESTIONS TO TEST QUESTIONS
    submitAddedQuestions: function(event) {
      event.preventDefault();
      this.confirmAddedQuestions();
    },

    confirmAddedQuestions: function() {
      this.submit_disabled = true;
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

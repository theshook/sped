<template>
  <div>
    <b-container class="mt-2">
      <b-card class="shadow-sm">
        <b-card-body>
          <b-row>
            <b-col lg="6">
              <h2 class="text-primary font-weight-bold">Questions</h2>
            </b-col>

            <b-col lg="6">
              <div class="d-flex justify-content-end align-baseline">
                <b-button variant="primary" size="sm" v-b-modal.add-modal>
                  <b-icon icon="pencil"></b-icon>
                  <!-- <span class="fa fa-fw fa-plus-circle"></span> -->
                  Add Question
                </b-button>
              </div>
            </b-col>
          </b-row>

          <b-row class="mb-2">
            <b-col lg="6">
              <b-form class="text-muted text-md" inline>
                <small>Show</small>
                <select
                  class="form-control form-control-sm text-sm col-sm-2 mx-1"
                  id="row-limit"
                  @change="getQuestions"
                  v-model="limit"
                >
                  <option value="10">10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
                <small>entries</small>
              </b-form>
            </b-col>

            <b-col lg="6" class="d-flex justify-content-end align-content-end">
              <b-form class="col-sm-12 col-md-6 px-0">
                <b-form-input size="sm" v-model="search" @input="getQuestions" placeholder="Search"></b-form-input>
              </b-form>
            </b-col>
          </b-row>

          <b-table
            borderless
            striped
            hover
            id="question-table"
            :items="questions"
            :fields="questions_fields"
            :busy="table_busy"
            responsive="md"
            show-empty
          >
            <template v-slot:cell(question)="data">{{ data.item.question }}</template>

            <template v-slot:cell(question_type)="data">
              <span
                class="badge badge-primary mb-0 p-1"
                v-if="data.item.question_type === 1"
              >Multiple choices</span>
              <span
                class="badge badge-info mb-0 p-1"
                v-if="data.item.question_type === 2"
              >Enumeration</span>
              <span
                class="badge badge-secondary mb-0 p-1"
                v-if="data.item.question_type === 3"
              >Identification</span>
            </template>

            <template v-slot:cell(checklist)="data">{{ data.item.checklist.category.name }}</template>

            <template v-slot:cell(index)="data">
              <b-btn-group>
                <b-button
                  v-b-modal.edit-modal
                  size="sm"
                  variant="warning"
                  class="text-white"
                  @click="edit(data.index)"
                >
                  <b-icon icon="pencil"></b-icon>
                </b-button>

                <b-button
                  v-b-modal.delete-modal
                  size="sm"
                  variant="danger"
                  @click="remove(data.index)"
                >
                  <b-icon icon="trash"></b-icon>
                </b-button>
              </b-btn-group>
            </template>

            <template v-slot:table-busy>
              <div class="text-center py-3">
                <b-spinner type="grow" variant="primary"></b-spinner>
              </div>
            </template>

            <template v-slot:empty="scope">
              <div class="text-center py-3">
                <p class="text-muted mb-0">{{ scope.emptyText }}</p>
              </div>
            </template>
          </b-table>

          <b-container class="clearfix px-0" fluid>
            <b-pagination
              class="float-right"
              size="sm"
              v-model="current_page"
              :per-page="Number(response.per_page)"
              :total-rows="Number(response.total)"
              @change="getQuestions"
              aria-controls="question-table"
            ></b-pagination>
          </b-container>
        </b-card-body>
      </b-card>
    </b-container>

    <!-- MODALS -->
    <!-- ADD -->
    <b-modal
      scrollable
      id="add-modal"
      title="Add Question"
      ok-title="Submit"
      ok-variant="success"
      ok-only
      @ok="submitAdd"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form>
        <b-form-group label="Category" label-class="text-sm">
          <b-form-select
            v-model="$v.form.checklist_category_id.$model"
            :state="validateState('checklist_category_id')"
            aria-describedby="input-category-feedback"
          >
            <b-form-select-option
              v-for="category in checklistCategories"
              :key="category.id"
              :value="category.id"
            >{{ category.name }}</b-form-select-option>
          </b-form-select>
          <b-form-invalid-feedback id="input-category-feedback">This field is required</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Teacher" label-class="text-sm">
          <b-form-select
            v-model="$v.form.teacher_id.$model"
            :state="validateState('teacher_id')"
            aria-describedby="input-teacher-feedback"
          >
            <b-form-select-option
              v-for="teacher in teachers"
              :key="teacher.id"
              :value="teacher.id"
            >{{ teacher.name }} - {{ teacher.school }}</b-form-select-option>
          </b-form-select>
          <b-form-invalid-feedback id="input-teacher-feedback">This field is required</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Question type" label-class="text-sm">
          <b-form-select
            v-model="$v.form.question_type.$model"
            :state="validateState('question_type')"
            aria-describedby="input-question-type-feedback"
            @change="checkQuestionType"
          >
            <template v-slot:first>
              <b-form-select-option :value="null" disabled>Choose</b-form-select-option>
            </template>
            <b-form-select-option :value="1">Multiple choices</b-form-select-option>
            <b-form-select-option :value="2">Enumeration</b-form-select-option>
            <b-form-select-option :value="3">Identification</b-form-select-option>
          </b-form-select>
          <b-form-invalid-feedback id="input-teacher-feedback">This field is required</b-form-invalid-feedback>
        </b-form-group>

        <section v-if="show_question_fields && form.question_type !== null">
          <b-form-group label="Question" label-class="text-sm">
            <b-form-textarea
              v-model="$v.form.question.$model"
              :state="validateState('question')"
              aria-describedby="invalid-input-question"
            ></b-form-textarea>

            <b-form-invalid-feedback id="invalid-input-question">
              This field is required and must be atleast 10
              characters.
            </b-form-invalid-feedback>
          </b-form-group>

          <section v-if="form.question_type == 1">
            <b-form-group label="Choice #1" label-class="text-sm">
              <b-form-textarea
                v-model="$v.form.choice1.$model"
                :state="validateState('choice1')"
                aria-describedby="invalid-input-choice1"
                rows="4"
              ></b-form-textarea>

              <b-form-invalid-feedback id="invalid-input-choice1">
                This field is required and must be atleast 3
                characters.
              </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Choice #2" label-class="text-sm">
              <b-form-textarea
                v-model="$v.form.choice2.$model"
                :state="validateState('choice2')"
                aria-describedby="invalid-input-choice2"
                rows="4"
              ></b-form-textarea>

              <b-form-invalid-feedback id="invalid-input-choice2">
                This field is required and must be atleast 3
                characters.
              </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Choice #3" label-class="text-sm">
              <b-form-textarea
                v-model="$v.form.choice3.$model"
                :state="validateState('choice3')"
                aria-describedby="invalid-input-choice3"
                rows="4"
              ></b-form-textarea>

              <b-form-invalid-feedback id="invalid-input-choice3">
                This field is required and must be atleast 3
                characters.
              </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="Choice #4" label-class="text-sm">
              <b-form-textarea
                v-model="$v.form.choice4.$model"
                :state="validateState('choice4')"
                aria-describedby="invalid-input-choice4"
                rows="4"
              ></b-form-textarea>

              <b-form-invalid-feedback id="invalid-input-choice4">
                This field is required and must be atleast 3
                characters.
              </b-form-invalid-feedback>
            </b-form-group>
          </section>

          <b-form-group label="Answer" label-class="text-sm">
            <b-form-textarea
              v-model="$v.form.answer.$model"
              :state="validateState('answer')"
              aria-describedby="invalid-input-answer"
              rows="2"
            ></b-form-textarea>

            <b-form-invalid-feedback id="invalid-input-answer">
              This field is required and must be atleast 3
              characters.
            </b-form-invalid-feedback>
          </b-form-group>

          <b-form-group label="Explanation" label-class="text-sm">
            <b-form-textarea
              v-model="form.explanation"
              aria-describedby="invalid-input-explanation"
              rows="2"
            ></b-form-textarea>

            <b-form-text class="text-muted text-small">* This field is optional</b-form-text>

            <b-form-invalid-feedback
              id="invalid-input-explanation"
            >This field must be atleast 3 characters.</b-form-invalid-feedback>
          </b-form-group>
        </section>
      </b-form>
    </b-modal>

    <!-- EDIT -->
    <b-modal
      scrollable
      id="edit-modal"
      title="Update Question"
      ok-title="Submit"
      ok-variant="success"
      ok-only
      @ok="submitUpdate"
      button-size="sm"
    >
      <b-form>
        <b-form-group label="Category" label-class="text-sm">
          <b-form-select
            v-model="$v.form.checklist_category_id.$model"
            :state="validateState('checklist_category_id')"
            aria-describedby="input-category-feedback"
          >
            <b-form-select-option
              v-for="category in checklistCategories"
              :key="category.id"
              :value="category.id"
            >{{ category.name }}</b-form-select-option>
          </b-form-select>
          <b-form-invalid-feedback id="input-category-feedback">This field is required</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Teacher" label-class="text-sm">
          <b-form-select
            v-model="$v.form.teacher_id.$model"
            :state="validateState('teacher_id')"
            aria-describedby="input-teacher-feedback"
          >
            <b-form-select-option
              v-for="teacher in teachers"
              :key="teacher.id"
              :value="teacher.id"
            >{{ teacher.name }} - {{ teacher.school }}</b-form-select-option>
          </b-form-select>
          <b-form-invalid-feedback id="input-teacher-feedback">This field is required</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Question" label-class="text-sm">
          <b-form-textarea
            v-model="$v.form.question.$model"
            :state="validateState('question')"
            aria-describedby="invalid-input-question"
          ></b-form-textarea>

          <b-form-invalid-feedback id="invalid-input-question">
            This field is required and must be atleast 10
            characters.
          </b-form-invalid-feedback>
        </b-form-group>

        <section v-if="form.question_type === 1">
          <b-form-group label="Choice #1" label-class="text-sm">
            <b-form-textarea
              v-model="$v.form.choice1.$model"
              :state="validateState('choice1')"
              aria-describedby="invalid-input-choice1"
              rows="4"
            ></b-form-textarea>

            <b-form-invalid-feedback id="invalid-input-choice1">
              This field is required and must be atleast 3
              characters.
            </b-form-invalid-feedback>
          </b-form-group>

          <b-form-group label="Choice #2" label-class="text-sm">
            <b-form-textarea
              v-model="$v.form.choice2.$model"
              :state="validateState('choice2')"
              aria-describedby="invalid-input-choice2"
              rows="4"
            ></b-form-textarea>

            <b-form-invalid-feedback id="invalid-input-choice2">
              This field is required and must be atleast 3
              characters.
            </b-form-invalid-feedback>
          </b-form-group>

          <b-form-group label="Choice #3" label-class="text-sm">
            <b-form-textarea
              v-model="$v.form.choice3.$model"
              :state="validateState('choice3')"
              aria-describedby="invalid-input-choice3"
              rows="4"
            ></b-form-textarea>

            <b-form-invalid-feedback id="invalid-input-choice3">
              This field is required and must be atleast 3
              characters.
            </b-form-invalid-feedback>
          </b-form-group>

          <b-form-group label="Choice #4" label-class="text-sm">
            <b-form-textarea
              v-model="$v.form.choice4.$model"
              :state="validateState('choice4')"
              aria-describedby="invalid-input-choice4"
              rows="4"
            ></b-form-textarea>

            <b-form-invalid-feedback id="invalid-input-choice4">
              This field is required and must be atleast 3
              characters.
            </b-form-invalid-feedback>
          </b-form-group>
        </section>

        <b-form-group label="Answer" label-class="text-sm">
          <b-form-textarea
            v-model="$v.form.answer.$model"
            :state="validateState('answer')"
            aria-describedby="invalid-input-answer"
            rows="2"
          ></b-form-textarea>

          <b-form-invalid-feedback id="invalid-input-answer">
            This field is required and must be atleast 3
            characters.
          </b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Explanation" label-class="text-sm">
          <b-form-textarea
            v-model="form.explanation"
            aria-describedby="invalid-input-explanation"
            rows="2"
          ></b-form-textarea>

          <b-form-invalid-feedback
            id="invalid-input-explanation"
          >This field must be atleast 3 characters.</b-form-invalid-feedback>
        </b-form-group>
      </b-form>
    </b-modal>

    <!-- DELETE CONFIRM -->
    <b-modal
      id="delete-modal"
      title="Confirm"
      ok-title="Continue"
      ok-variant="danger"
      @ok="destroy"
      ok-only
      button-size="sm"
    >
      <p class="text-center text-muted mb-1">Are you sure you want to remove this province?</p>
    </b-modal>
  </div>
</template>

<script>
import {
  required,
  requiredIf,
  requiredUnless,
  minLength,
  maxLength
} from "vuelidate/lib/validators";
export default {
  name: "TeacherQuestionsIndex",
  props: ["host"],
  data() {
    return {
      table_busy: false,
      search: "",
      limit: 10,
      current_page: 1,
      teachers: [],
      checklists: [],
      questions: null,
      questions_fields: [
        {
          key: "question",
          label: "Question",
          sortable: true
        },
        {
          key: "question_type",
          label: "Type",
          sortable: true
        },
        {
          key: "checklist",
          label: "Category",
          sortable: true
        },
        {
          key: "index",
          label: "Action"
        }
      ],
      checklistCategories: null,
      response: {},
      show_question_fields: false,

      // FORM
      form: {
        checklist_category_id: null,
        teacher_id: null,
        question_type: null,
        question: null,
        choice1: null,
        choice2: null,
        choice3: null,
        choice4: null,
        answer: null,
        explanation: null
      },

      // EDIT
      edit_id: null,
      edit_index: null,

      // DELETE
      delete_id: null,
      delete_index: null
    };
  },
  computed: {
    isMultipleChoice: function() {
      return this.form.question_type == 1 ? true : false;
    }
  },
  validations: {
    form: {
      checklist_category_id: {
        required
      },
      teacher_id: {
        required
      },
      question_type: {
        required
      },
      question: {
        required,
        minLength: minLength(10),
        maxLength: maxLength(200)
      },
      choice1: {
        required: requiredIf(function() {
          return this.isMultipleChoice;
        }),
        minLength: minLength(3),
        maxLength: maxLength(200)
      },
      choice2: {
        required: requiredIf(function() {
          return this.isMultipleChoice;
        }),
        minLength: minLength(3),
        maxLength: maxLength(200)
      },
      choice3: {
        required: requiredIf(function() {
          return this.isMultipleChoice;
        }),
        minLength: minLength(3),
        maxLength: maxLength(200)
      },
      choice4: {
        required: requiredIf(function() {
          return this.isMultipleChoice;
        }),
        minLength: minLength(3),
        maxLength: maxLength(200)
      },
      answer: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(200)
      }
    }
  },
  mounted() {
    this.getQuestions();
    this.getTeachers();
    this.getChecklistCategories();
  },
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    getQuestions: function(page) {
      this.table_busy = true;
      const questionsAPI = `${this.host}/questions?search=${this.search}&limit=${this.limit}&page=${page}`;
      axios
        .get(questionsAPI)
        .then(response => {
          this.questions = response.data.data;
          this.response = response.data;
        })
        .catch(err => console.log(err))
        .finally(() => (this.table_busy = false));
    },

    getTeachers: function() {
      let teachersAPI = `${this.host}/teachers/raw`;
      axios
        .get(teachersAPI)
        .then(response => {
          let teacherArr = response.data;
          for (let i = 0; i < teacherArr.length; i++) {
            let teacher = `${teacherArr[i].first_name} ${teacherArr[i].last_name}`;
            this.teachers.push({
              id: teacherArr[i].id,
              name: teacher,
              school: teacherArr[i].school.name
            });
          }
        })
        .catch(err => console.log(err.response));
    },

    getChecklistCategories: function() {
      let checklistCategoriesAPI = `${this.host}/checklists/categories/raw`;
      axios
        .get(checklistCategoriesAPI)
        .then(response => {
          this.checklistCategories = response.data;
        })
        .catch(err => console.log(err.response));
    },

    submitAdd: function(event) {
      event.preventDefault();
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      } else {
        this.add();
      }
    },

    add: function() {
      const questionsAPI = `${this.host}/questions`;
      const data = {
        checklist_category_id: this.form.checklist_category_id,
        teacher_id: this.form.teacher_id,
        question_type: this.form.question_type,
        question: this.form.question,
        choice1: this.form.choice1,
        choice2: this.form.choice2,
        choice3: this.form.choice3,
        choice4: this.form.choice4,
        answer: this.form.answer,
        explanation: this.form.explanation
      };

      axios
        .post(questionsAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.getQuestions();
            this.$bvModal.hide("add-modal");
            this.resetForm();

            swal.fire({
              icon: "success",
              title: "Added",
              text: "Assessment question successfully added",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to add assessment question",
              timer: 3000
            });
          }
        })
        .catch(err =>
          swal.fire({
            icon: "error",
            title: err.response.data.message,
            text: err.response.data.errors,
            timer: 3000
          })
        );
    },

    edit: function(index) {
      this.edit_id = this.questions[index].id;
      this.edit_index = index;
      this.form.checklist_category_id = this.questions[
        index
      ].checklist_category_id;
      this.form.teacher_id = this.questions[index].teacher_id;
      this.form.question_type = this.questions[index].question_type;
      this.form.question = this.questions[index].question;
      this.form.choice1 = this.questions[index].choice1;
      this.form.choice2 = this.questions[index].choice2;
      this.form.choice3 = this.questions[index].choice3;
      this.form.choice4 = this.questions[index].choice4;
      this.form.answer = this.questions[index].answer;
      this.form.explanation = this.questions[index].explanation;
    },

    submitUpdate: function(event) {
      event.preventDefault();
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      } else {
        this.update();
      }
    },

    update: function() {
      const questionsAPI = `${this.host}/question/${this.edit_id}`;
      const data = {
        checklist_category_id: this.form.checklist_category_id,
        teacher_id: this.form.teacher_id,
        question_type: this.form.question_type,
        question: this.form.question,
        choice1: this.form.choice1,
        choice2: this.form.choice2,
        choice3: this.form.choice3,
        choice4: this.form.choice4,
        answer: this.form.answer
      };

      this.questions[
        this.edit_index
      ].checklist_category_id = this.form.checklist_category_id;
      this.questions[this.edit_index].teacher_id = this.form.teacher_id;
      this.questions[this.edit_index].question_type = this.form.question_type;
      this.questions[this.edit_index].question = this.form.question;
      this.questions[this.edit_index].choice1 = this.form.choice1;
      this.questions[this.edit_index].choice2 = this.form.choice2;
      this.questions[this.edit_index].choice3 = this.form.choice3;
      this.questions[this.edit_index].choice4 = this.form.choice4;
      this.questions[this.edit_index].answer = this.form.answer;
      this.questions[this.edit_index].explanation = this.form.explanation;

      axios
        .put(questionsAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.$bvModal.hide("edit-modal");
            this.resetForm();

            swal.fire({
              icon: "success",
              title: "Updated",
              text: "Assessment question information successfully updated",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to update assessment question",
              timer: 3000
            });
          }
        })
        .catch(err =>
          swal.fire({
            icon: "error",
            title: err.response.data.message,
            text: err.response.data.errors,
            timer: 3000
          })
        );
    },

    remove: function(index) {
      this.delete_id = this.questions[index].id;
      this.delete_index = index;
    },

    destroy: function(index) {
      const questionsAPI = `${this.host}/question/${this.delete_id}`;
      axios
        .delete(questionsAPI)
        .then(response => {
          if (response.data.status == 201) {
            this.questions.splice(this.delete_index, 1);
            this.delete_index = null;
            swal.fire({
              icon: "success",
              title: "Deleted",
              text: "Assessment question successfully deleted",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to delete assessment question",
              timer: 3000
            });
          }
        })
        .catch(err =>
          swal.fire({
            icon: "error",
            title: err.response.data.message,
            text: err.response.data,
            timer: 3000
          })
        );
    },

    checkQuestionType: function() {
      if (this.form.question_type == null) {
        this.show_question_fields = false;
      } else {
        this.show_question_fields = true;
      }
    },

    resetForm: function() {
      this.$v.$reset();
      this.form.checklist_category_id = null;
      this.form.teacher_id = null;
      this.form.question_type = null;
      this.form.question = null;
      this.form.choice1 = null;
      this.form.choice2 = null;
      this.form.choice3 = null;
      this.form.choice4 = null;
      this.form.answer = null;
      this.form.explanation = null;
    }
  }
};
</script>

<template>
  <div>
    <b-container class="mt-2">
      <b-card class="shadow-sm mb-3">
        <b-card-body>
          <b-row class="mb-2">
            <b-col lg="6">
              <p class="text-muted">Title</p>
              <h3 class="text-primary font-weight-bolder mt-2">{{ test.title }}</h3>
            </b-col>
            <b-col lg="6">
              <p class="text-muted">Last updated</p>
              <p class="text-muted">{{test.updated_at}}</p>
            </b-col>
          </b-row>

          <b-row>
            <b-col lg="6">
              <p class="text-muted">Description</p>
              <p class="text-muted font-weight-bold pt-2">{{ test.description }}</p>
            </b-col>

            <b-col lg="6">
              <p class="text-muted">Actions:</p>
              <b-button
                class="shadow-sm"
                variant="primary"
                size="sm"
                v-b-modal.questions-modal
                v-if="!questions.length == 0"
              >
                <b-icon icon="plus"></b-icon>Add question
              </b-button>

              <b-button class="shadow-sm" variant="info" size="sm">
                <b-icon icon="file-earmark"></b-icon>View PDF
              </b-button>

              <b-button class="shadow-sm" variant="success" size="sm">
                <b-icon icon="check-circle"></b-icon>Publish assessment
              </b-button>
            </b-col>
          </b-row>
        </b-card-body>
      </b-card>

      <b-card class="shadow-sm">
        <b-card-body>
          <section class="text-center" v-if="questions.length == 0">
            <p class="test-black-50">No questions available.</p>
            <b-button class="shadow-sm" variant="primary" size="sm" v-b-modal.questions-modal>
              <b-icon icon="plus"></b-icon>Click here to add
            </b-button>
          </section>

          <section v-if="!questions.length == 0">
            <b-table
              borderless
              striped
              hover
              sticky-header="500px"
              id="questions-table"
              :items="questions"
              :fields="questions_fields"
              responsive="md"
            >
              <template v-slot:cell(question)="data">
                {{
                data.item.question
                }}
              </template>

              <template v-slot:cell(category)="data">
                {{
                data.item.category.name
                }}
              </template>

              <template v-slot:cell(question_type)="data">
                <span v-if="data.item.question_type == 1">Multiple Choices</span>
                <span v-if="data.item.question_type == 2">Enumeration</span>
                <span v-if="data.item.question_type == 3">Identification</span>
              </template>

              <template v-slot:cell(index)="data">
                <b-button variant="danger" size="sm" @click="removeQuestion(data.index)">Remove</b-button>
              </template>
            </b-table>
          </section>
        </b-card-body>
      </b-card>
    </b-container>

    <!-- ADD QUESTIONS -->
    <b-modal
      scrollable
      id="questions-modal"
      class="px-0"
      size="xl"
      title="Browse questions"
      ok-title="Save Changes"
      ok-variant="success"
      ok-only
      :ok-disabled="checkTestQuestionsLength"
      @ok="openQuestionsAddConfirmModal"
      button-size="sm"
    >
      <b-tabs pills vertical small>
        <b-tab class="pt-0 mt-0" title="Multiple Choices">
          <b-table
            borderless
            striped
            hover
            id="multiple-choices-questions-table"
            :items="questions_multiple_choices"
            :fields="questions_fields"
            responsive="md"
          >
            <template v-slot:cell(question)="data">
              {{
              data.item.question
              }}
            </template>

            <template v-slot:cell(category)="data">
              {{
              data.item.category.name
              }}
            </template>

            <template v-slot:cell(index)="data">
              <b-btn-group>
                <b-button variant="primary" size="sm" @click="addQuestion(data.index)">
                  <b-icon icon="plus" class="mr-2"></b-icon>Add
                </b-button>
              </b-btn-group>
            </template>
          </b-table>

          <!-- <b-container class="clearfix px-0" fluid>
            <b-pagination
              class="float-right"
              size="sm"
              v-model="current_page"
              :per-page="Number(response.per_page)"
              :total-rows="Number(response.total)"
              @change="getTests"
              aria-controls="multiple-choices-questions-table"
            ></b-pagination>
          </b-container>-->
        </b-tab>

        <b-tab title="Enumeration">
          <p>Enumeration</p>
        </b-tab>

        <b-tab title="Identification">
          <p>Identification</p>
        </b-tab>
      </b-tabs>
    </b-modal>

    <!-- CONFIRM ADD QUESTIONS -->
    <b-modal
      id="confirm-questions-add-modal"
      title="Confirm"
      ok-title="Proceed"
      ok-variant="success"
      @ok="confirmAddQuestion"
      cancel-title="Cancel"
      cancel-variant="light"
      button-size="sm"
    >
      <p class="text-muted">Do confirm to add these questions to this test?</p>
    </b-modal>

    <!-- <b-table
        borderless
        striped
        hover
        sticky-header="500px"
        id="questions-table"
        :items="teacher_questions"
        :fields="teacher_questions_fields"
        responsive="md"
      >
        <template v-slot:cell(question)="data">
          {{
          data.item.question
          }}
        </template>

        <template v-slot:cell(category)="data">
          {{
          data.item.category.name
          }}
        </template>

        <template v-slot:cell(index)="data">
          <b-button variant="primary" size="sm" @click="addQuestion(data.index)">
            <b-icon class="mr-2" icon="plus"></b-icon>Add
          </b-button>
        </template>
    </b-table>-->
  </div>
</template>

<script>
export default {
  name: "TestManageQuestions",
  props: ["host", "test_id"],
  data() {
    return {
      test: {},
      questions: [],
      questions_id: [],
      questions_id_queue: [],
      questions_multiple_choices: [],
      questions_enumeration: [],
      questions_identification: [],
      questions_fields: [
        {
          key: "question",
          label: "Question",
          sortable: true
        },
        {
          key: "category",
          label: "Category",
          sortable: true
        },
        {
          key: "question_typq",
          label: "Type",
          sortable: true
        },
        {
          key: "index",
          label: "Action"
        }
      ],
      teacher_questions: null,
      teacher_questions_fields: [
        {
          key: "question",
          label: "Question",
          sortable: true
        },
        {
          key: "category",
          label: "Category",
          sortable: true
        },
        {
          key: "index",
          label: "Action"
        }
      ]
    };
  },
  validations: {},
  computed: {
    checkTestQuestionsLength: function() {
      return this.questions_id_queue.length ? false : true;
    }
  },
  mounted() {
    this.getTest();

    this.$bvModal.show("questions-modal");
  },
  methods: {
    getTest: function() {
      const testsAPI = `${this.host}/test/${this.test_id}`;
      axios
        .get(testsAPI)
        .then(response => {
          this.test = response.data.test;
          this.teacher_questions = response.data.teacher_questions;
          this.questions = response.data.questions;
          this.questions_id =
            JSON.parse(response.data.questions_id) == null
              ? []
              : JSON.parse(response.data.questions_id);

          this.checkTestQuestions(); //Check test questions over available teacher questions to add/browse to avoid duplicate
          this.sortTeacherQuestions(); //Check question type and sort it to 3 questions types array (multiple choice, enumeration, identification)
        })
        .catch(err => console.log(err));
    },

    checkTestQuestions: function() {
      for (let i = 0; i < this.teacher_questions.length; i++) {
        for (let q = 0; q < this.questions.length; q++) {
          if (this.teacher_questions[i].id === this.questions[q].id) {
            this.teacher_questions.splice(i, 1);
          }
        }
      }
    },

    sortTeacherQuestions: function() {
      //Filtering question type == 1 (multiple choice)
      const multiple_choices = this.teacher_questions.filter(question => {
        return question.question_type == 1;
      });
      this.questions_multiple_choices = multiple_choices;

      //Filtering question type == 2 (enumeration)
      const enumeration = this.teacher_questions.filter(question => {
        return question.question_type == 2;
      });
      this.questions_enumeration = enumeration;

      //Filtering question type == 3 (identification)
      const identification = this.teacher_questions.filter(question => {
        return question.question_type == 3;
      });
      this.questions_identification = identification;
    },

    addQuestion: function(index) {
      let id = this.teacher_questions[index].id;
      this.questions_id_queue.push(id);
      this.teacher_questions.splice(index, 1);

      this.$bvToast.toast(`Question added`, {
        title: "Added!",
        variant: "success",
        autoHideDelay: 1000,
        appendToast: true
      });
    },

    confirmAddQuestion: function(event) {
      event.preventDefault();
      let testsAPI = `${this.host}/test/${this.test_id}`;

      this.questions_id_queue.forEach(id => {
        this.questions_id.push(id);
      });

      const data = {
        questions_id: this.questions_id
      };

      axios
        .put(testsAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.getTest();
            this.resetQuestionsIdQueue();
            this.$bvModal.hide("confirm-questions-add-modal");
            swal.fire({
              icon: "success",
              title: "Added",
              text: "Test questions successfully updated",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to update test questions",
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

    removeQuestion: function(index) {
      let id = this.questions[index].id;
      for (let i = 0; i < this.questions_id.length; i++) {
        if (this.questions_id[i] === id) {
          this.questions_id.splice(i, 1);
          this.questions.splice(index, 1);
          break;
        }
      }

      let testsAPI = `${this.host}/test/${this.test_id}`;
      const data = {
        questions_id: this.questions_id
      };
      axios
        .put(testsAPI, data)
        .then(response => {})
        .catch(err =>
          swal.fire({
            icon: "error",
            title: err.response.data.message,
            text: err.response.data.errors,
            timer: 3000
          })
        );

      this.$bvToast.toast(`Question removed`, {
        title: "Removed!",
        variant: "danger",
        autoHideDelay: 2000,
        appendToast: true
      });
    },

    resetQuestionsIdQueue: function() {
      this.questions_id_queue = [];
    },

    openQuestionsAddConfirmModal: function() {
      this.$bvModal.show("confirm-questions-add-modal");
    }
  }
};
</script>

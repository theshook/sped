<template>
  <div>
    <b-container class="mt-2">
      <b-card class="shadow-sm mb-3">
        <b-card-body>
          <b-row class="mb-2">
            <b-col lg="6">
              <p class="text-muted">Title</p>
              <h3 class="text-primary front-weight-bolder">{{ test.title }}</h3>
            </b-col>
            <b-col lg="6">
              <p class="text-muted">Description</p>
              <p class="text-muted font-weight-bold pt-2">{{ test.description }}</p>
            </b-col>
          </b-row>

          <b-row>
            <b-col lg="6">
              <p class="text-muted">No. of questions</p>
              <h4
                class="text-muted font-weight-bold"
                v-if="questions.length != 0"
              >{{ questions.length }}</h4>
              <p class="text-muted font-weight-bold" v-if="questions.length == 0">N/A</p>
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

              <b-button class="shadow-sm" variant="success" size="sm">
                <b-icon icon="check"></b-icon>Publish
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
      size="xl"
      title="Browse questions"
      ok-title="Done"
      ok-variant="success"
      ok-only
      @ok="confirmAddQuestion"
      button-size="sm"
    >
      <section v-for="(question, i) in teacher_questions" :key="i">
        <b-row class="border-bottom p-3 mb-2">
          <b-col lg="11">
            <small class="text-muted">{{ question.question }}</small>
          </b-col>
          <b-col lg="1">
            <b-button variant="primary" size="sm" @click="addQuestion(i)">
              <b-icon icon="plus"></b-icon>
            </b-button>
          </b-col>
        </b-row>
      </section>
    </b-modal>
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
      questions_fields: [
        {
          key: "question",
          label: "Question"
        },
        {
          key: "index",
          label: "Action"
        }
      ],
      questions_id: [],
      teacher_questions: null
    };
  },
  validations: {},
  computed: {},
  mounted() {
    this.getTest();
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

          for (let i = 0; i < this.teacher_questions.length; i++) {
            for (let q = 0; q < this.questions.length; q++) {
              if (this.teacher_questions[i].id === this.questions[q].id) {
                this.teacher_questions.splice(i, 1);
              }
            }
          }
        })
        .catch(err => console.log(err));
    },

    addQuestion: function(index) {
      let id = this.teacher_questions[index].id;
      this.questions_id.push(id);
      this.teacher_questions.splice(index, 1);

      this.$bvToast.toast(`Question added`, {
        title: "Added!",
        variant: "success",
        autoHideDelay: 1000,
        appendToast: true
      });
    },

    confirmAddQuestion: function() {
      let testsAPI = `${this.host}/test/${this.test_id}`;
      const data = {
        questions_id: this.questions_id
      };
      axios
        .put(testsAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.getTest();
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
    }
  }
};
</script>

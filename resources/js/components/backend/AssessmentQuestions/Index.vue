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
              <!-- <b-form-select MY BUG EWAN KO PAREHAS NAMAN SA BABA HAHAHAHA
							@change="getProvinces"
							v-model="limit">
								<b-form-select-option value="10" selected>10</b-form-select-option>
								<b-form-select-option value="25">25</b-form-select-option>
								<b-form-select-option value="50">50</b-form-select-option>
								<b-form-select-option value="100">100</b-form-select-option>
              </b-form-select>-->

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
            responsive="md"
          >
            <template v-slot:cell(name)="data">{{ data.item.name }}</template>

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
      id="add-modal"
      title="Add Question"
      ok-title="Submit"
      ok-variant="success"
      ok-only
      @ok="submitAdd"
      button-size="sm"
    >
      <b-form>
        <!-- <b-form-group label="Name" label-class="text-sm">
                    <b-form-input
					v-model="$v.form.name.$model"
					:state="validateState('name')"
					aria-describedby="invalid-input-name"></b-form-input>

					<b-form-invalid-feedback
					id="invalid-input-name">
						This field is required and must be atleast 3 characters.
					</b-form-invalid-feedback>
        </b-form-group>-->
      </b-form>
    </b-modal>

    <!-- EDIT -->
    <b-modal
      id="edit-modal"
      title="Update Question"
      ok-title="Submit"
      ok-variant="success"
      ok-only
      @ok="submitUpdate"
      button-size="sm"
    >
      <b-form>
        <!-- <b-form-group label="Name" label-class="text-sm">
                    <b-form-input
					v-model="$v.form.name.$model"
					:state="validateState('name')"
					aria-describedby="invalid-input-name"></b-form-input>

					<b-form-invalid-feedback
					id="invalid-input-name">
						This field is required and must be atleast 3 characters.
					</b-form-invalid-feedback>
        </b-form-group>-->
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
import { required, minLength, maxLength } from "vuelidate/lib/validators";
export default {
  name: "TeacherQuestionsIndex",
  props: ["host", "userid"],
  data() {
    return {
      search: "",
      limit: 10,
      current_page: 1,
      questions: null,
      questions_fields: [
        {
          key: "question",
          label: "Question",
          sortable: true
        },
        {
          key: "index",
          label: "Action"
        }
      ],
      response: {},

      // ADD
      form: {},

      // EDIT
      edit_id: null,
      edit_index: null,

      // DELETE
      delete_id: null,
      delete_index: null
    };
  },
  validations: {
    form: {}
  },
  computed: {},
  mounted() {
    this.getQuestions();
  },
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    getQuestions: function(page) {
      const questionsAPI = `${this.host}/teacher/${this.userid}/questions?search=${this.search}&limit=${this.limit}&page=${page}`;
      axios
        .get(questionsAPI)
        .then(response => {
          this.questions = response.data;
          this.response = response.data;

          console.log(this.questions);
        })
        .catch(err => console.log(err));
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
        name: this.form.name
      };

      axios
        .post(questionsAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.getProvinces();
            this.form.name = null;
            this.$bvModal.hide("add-modal");
            this.$v.$reset();

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
            text: err.response.data.errors.name[0],
            timer: 3000
          })
        );
    },

    edit: function(index) {
      this.edit_id = this.question[index].id;
      this.edit_index = index;
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
        name: this.form.name
      };

      axios
        .put(questionsAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.question[this.edit_index].name = this.form.name;
            this.$bvModal.hide("edit-modal");
            this.$v.$reset();

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
            text: err.response.data.errors.name[0],
            timer: 3000
          })
        );
    },

    remove: function(index) {
      this.delete_id = this.questions[index].id;
      this.delete_index = index;
    },

    destroy: function(index) {
      const questionsAPI = `${this.host}/questions/${this.delete_id}`;
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
            text: err.response.data.errors.name[0],
            timer: 3000
          })
        );
    }
  }
};
</script>

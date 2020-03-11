<template>
  <div>
    <b-container class="mt-2">
      <b-card class="shadow-sm">
        <b-card-body>
          <b-row>
            <b-col lg="6">
              <h2 class="text-primary font-weight-bold">Tests</h2>
            </b-col>

            <b-col lg="6">
              <div class="d-flex justify-content-end align-baseline">
                <b-button variant="primary" size="sm" v-b-modal.add-modal>
                  <b-icon icon="pencil"></b-icon>
                  <!-- <span class="fa fa-fw fa-plus-circle"></span> -->
                  Add Test
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
                  @change="getTests"
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
                <b-form-input size="sm" v-model="search" @input="getTests" placeholder="Search"></b-form-input>
              </b-form>
            </b-col>
          </b-row>

          <b-table
            borderless
            striped
            hover
            id="province-table"
            :items="tests"
            :fields="tests_fields"
            responsive="md"
          >
            <template v-slot:cell(name)="data">{{ data.item.name }}</template>

            <template
              v-slot:cell(teacher)="data"
            >{{ data.item.teacher.first_name + ' ' + data.item.teacher.last_name }}</template>

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
              @change="getTests"
              aria-controls="province-table"
            ></b-pagination>
          </b-container>
        </b-card-body>
      </b-card>
    </b-container>

    <!-- MODALS -->
    <!-- ADD -->
    <b-modal
      id="add-modal"
      title="Add Test"
      ok-title="Submit"
      ok-variant="success"
      ok-only
      @ok="submitAdd"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form>
        <b-form-group label="Teacher" label-class="text-sm">
          <b-form-select
            v-model="$v.form.teacher_id.$model"
            :state="validateState('teacher_id')"
            aria-describedby="input-teacher-feedback"
          >
            <b-form-select-option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
              {{ teacher.name }} -
              {{ teacher.school }}
            </b-form-select-option>
          </b-form-select>
          <b-form-invalid-feedback id="input-teacher-feedback">This field is required</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Title" label-class="text-sm">
          <b-form-input
            v-model="$v.form.title.$model"
            :state="validateState('title')"
            aria-describedby="input-title-feedback"
          ></b-form-input>
          <b-form-invalid-feedback id="input-title-feedback">
            This field is required and must be atleast 3
            characters
          </b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Description" label-class="text-sm">
          <b-form-textarea
            v-model="$v.form.description.$model"
            :state="validateState('description')"
            rows="4"
            aria-describedby="input-description-feedback"
          ></b-form-textarea>

          <b-form-invalid-feedback>
            This field is required and must be atleast 10
            characters
          </b-form-invalid-feedback>
        </b-form-group>
      </b-form>
    </b-modal>

    <!-- EDIT/UPDATE -->
    <b-modal
      id="edit-modal"
      title="Update Province Information"
      ok-title="Save Changes"
      ok-variant="success"
      ok-only
      @ok="submitUpdate"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form>
        <b-form-group label="Teacher" label-class="text-sm">
          <b-form-select
            v-model="$v.form.teacher_id.$model"
            :state="validateState('teacher_id')"
            aria-describedby="input-teacher-feedback"
          >
            <b-form-select-option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
              {{ teacher.name }} -
              {{ teacher.school }}
            </b-form-select-option>
          </b-form-select>
          <b-form-invalid-feedback id="input-teacher-feedback">This field is required</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Title" label-class="text-sm">
          <b-form-input
            v-model="$v.form.title.$model"
            :state="validateState('title')"
            aria-describedby="input-title-feedback"
          ></b-form-input>
          <b-form-invalid-feedback id="input-title-feedback">
            This field is required and must be atleast 3
            characters
          </b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Description" label-class="text-sm">
          <b-form-textarea
            v-model="$v.form.description.$model"
            :state="validateState('description')"
            rows="4"
            aria-describedby="input-description-feedback"
          ></b-form-textarea>

          <b-form-invalid-feedback>
            This field is required and must be atleast 10
            characters
          </b-form-invalid-feedback>
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
import { required, minLength, maxLength } from "vuelidate/lib/validators";
export default {
  name: "TestsIndex",
  props: ["host"],
  data() {
    return {
      search: "",
      limit: 10,
      current_page: 1,
      teachers: [],
      tests: null,
      tests_fields: [
        {
          key: "title",
          label: "Title",
          sortable: true
        },
        {
          key: "teacher",
          label: "Teacher",
          sortable: true
        },
        {
          key: "index",
          label: "Action"
        }
      ],
      province_list: [],
      response: {},

      // ADD
      form: {
        title: "",
        description: "",
        teacher_id: ""
      },

      // EDIT
      edit_id: null,
      edit_index: null,
      edit_name: null,

      // DELETE
      delete_id: null,
      delete_index: null
    };
  },
  validations: {
    form: {
      teacher_id: {
        required
      },
      title: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(60)
      },
      description: {
        required,
        minLength: minLength(10),
        maxLength: maxLength(100)
      }
    }
  },
  computed: {},
  mounted() {
    this.getTests();
    this.getTeachers();
  },
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    getTests: function(page) {
      const testsAPI = `${this.host}/tests?search=${this.search}&limit=${this.limit}`;
      axios
        .get(testsAPI)
        .then(response => {
          this.tests = response.data.data;
          this.response = response.data;
        })
        .catch(err => console.log(err));
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

    submitAdd: function(event) {
      event.preventDefault();
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      } else {
        this.add();
        this.$bvModal.hide("add-modal");
      }
    },

    add: function() {
      const testsAPI = `${this.host}/tests`;
      const data = {
        teacher_id: this.form.teacher_id,
        title: this.form.title,
        description: this.form.description
      };

      axios
        .post(testsAPI, data)
        .then(response => {
          console.log(response.data);
          if (response.data.status == 201) {
            this.getTests();
            this.resetForm();

            swal.fire({
              icon: "success",
              title: "Added",
              text: "Test successfully added",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to add test",
              timer: 3000
            });
          }
        })
        .catch(err =>
          swal.fire({
            icon: "error",
            title: err.response.data.message,
            text: err.response.data.errors,
            timer: 10000
          })
        );
    },

    edit: function(index) {
      this.edit_id = this.tests[index].id;
      this.edit_index = index;
      this.form.teacher_id = this.tests[index].teacher_id;
      this.form.title = this.tests[index].title;
      this.form.description = this.tests[index].description;
    },

    submitUpdate: function(event) {
      event.preventDefault();
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      } else {
        this.update();
        this.$bvModal.hide("edit-modal");
      }
    },

    update: function() {
      const testsAPI = `${this.host}/test/${this.edit_id}`;
      const data = {
        teacher_id: this.form.teacher_id,
        title: this.form.title,
        description: this.form.description
      };

      axios
        .put(testsAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            for (let t = 0; t < this.teachers.length; t++) {
              if (this.form.teacher_id === this.teachers[t].id) {
                this.tests[this.edit_index].teacher.first_name = this.teachers[
                  t
                ].name.split(" ")[0];
                this.tests[this.edit_index].teacher.last_name = this.teachers[
                  t
                ].name.split(" ")[1];
                break;
              }
            }

            this.tests[this.edit_index].teacher_id = this.form.teacher_id;
            this.tests[this.edit_index].title = this.form.title;
            this.tests[this.edit_index].description = this.form.description;

            this.resetForm();

            swal.fire({
              icon: "success",
              title: "Updated",
              text: "Test successfully updated",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to update test information",
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
      this.delete_id = this.tests[index].id;
      this.delete_index = index;
    },

    destroy: function(index) {
      const testsAPI = `${this.host}/test/${this.delete_id}`;
      axios
        .delete(testsAPI)
        .then(response => {
          if (response.data.status == 201) {
            this.tests.splice(this.delete_index, 1);
            this.delete_index = null;

            swal.fire({
              icon: "success",
              title: "Deleted",
              text: "Test successfully deleted",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to delete test",
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

    resetForm: function() {
      this.$v.$reset();
      this.form.title = null;
    }
  }
};
</script>

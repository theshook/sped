<template>
  <div>
    <b-container class="mt-2">
      <b-card class="shadow-sm">
        <b-card-body>
          <b-row>
            <b-col lg="6">
              <h2 class="text-primary font-weight-bold">Teachers</h2>
            </b-col>

            <b-col lg="6">
              <div class="d-flex justify-content-end align-baseline">
                <b-button variant="primary" size="sm" v-b-modal.add-modal>
                  <b-icon icon="pencil"></b-icon>
                  <!-- <span class="fa fa-fw fa-plus-circle"></span> -->
                  Add Teacher
                </b-button>
              </div>
            </b-col>
          </b-row>

          <b-row class="mb-2">
            <b-col lg="6">
              <!-- <b-form-select MY BUG EWAN KO PAREHAS NAMAN SA BABA HAHAHAHA
							@change="getTeachers"
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
                  @change="getTeachers"
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
                <b-form-input size="sm" v-model="search" @input="getTeachers" placeholder="Search"></b-form-input>
              </b-form>
            </b-col>
          </b-row>

          <b-table
            borderless
            striped
            hover
            id="teacher-table"
            :items="teachers"
            :fields="teachers_fields"
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
              @change="getTeachers"
              aria-controls="teacher-table"
            ></b-pagination>
          </b-container>
        </b-card-body>
      </b-card>
    </b-container>

    <!-- MODALS -->
    <!-- ADD -->
    <b-modal
      size="lg"
      id="add-modal"
      title="Add Teacher"
      ok-title="Submit"
      ok-variant="success"
      ok-only
      @ok="submitAdd"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form>
        <b-form-group label="School" label-class="text-sm">
          <b-form-select
            v-model="$v.form.school_id.$model"
            :state="validateState('school_id')"
            aria-describedby="input-school-feedback"
          >
            <b-form-select-option
              v-for="school in schools"
              :key="school.id"
              :value="school.id"
            >{{school.name}}</b-form-select-option>
          </b-form-select>

          <b-form-invalid-feedback id="input-school-feedback">This field is required.</b-form-invalid-feedback>
        </b-form-group>

        <label class="text-sm">Full name</label>
        <b-form-row>
          <b-col lg="4" no-gutters>
            <b-form-group>
              <b-form-input
                v-model="$v.form.first_name.$model"
                :state="validateState('first_name')"
                aria-describedby="input-fname-feedback"
                placeholder="First name"
              ></b-form-input>

              <b-form-invalid-feedback
                id="input-fname-feedback"
              >This field is required and must be atleast 3 characters.</b-form-invalid-feedback>
            </b-form-group>
          </b-col>

          <b-col lg="4" no-gutters>
            <b-form-group>
              <b-form-input
                v-model="$v.form.middle_name.$model"
                :state="validateState('middle_name')"
                aria-describedby="input-mname-feedback"
                placeholder="Middle name"
              ></b-form-input>

              <b-form-invalid-feedback
                id="input-mname-feedback"
              >This field is required and must be atleast 3 characters.</b-form-invalid-feedback>
            </b-form-group>
          </b-col>

          <b-col lg="4" no-gutters>
            <b-form-group>
              <b-form-input
                v-model="$v.form.last_name.$model"
                :state="validateState('last_name')"
                aria-describedby="input-lname-feedback"
                placeholder="Last name"
              ></b-form-input>

              <b-form-invalid-feedback
                id="input-lname-feedback"
              >This field is required and must be atleast 3 characters.</b-form-invalid-feedback>
            </b-form-group>
          </b-col>
        </b-form-row>
      </b-form>
    </b-modal>

    <!-- ADD -->
    <b-modal
      size="lg"
      id="edit-modal"
      title="Update Teacher Information"
      ok-title="Submit"
      ok-variant="success"
      ok-only
      @ok="submitUpdate"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form>
        <b-form-group label="School" label-class="text-sm">
          <b-form-select
            v-model="$v.form.school_id.$model"
            :state="validateState('school_id')"
            aria-describedby="input-school-feedback"
          >
            <b-form-select-option
              v-for="school in schools"
              :key="school.id"
              :value="school.id"
            >{{school.name}}</b-form-select-option>
          </b-form-select>

          <b-form-invalid-feedback id="input-school-feedback">This field is required.</b-form-invalid-feedback>
        </b-form-group>

        <label class="text-sm">Full name</label>
        <b-form-row>
          <b-col lg="4" no-gutters>
            <b-form-group>
              <b-form-input
                v-model="$v.form.first_name.$model"
                :state="validateState('first_name')"
                aria-describedby="input-fname-feedback"
                placeholder="First name"
              ></b-form-input>

              <b-form-invalid-feedback
                id="input-fname-feedback"
              >This field is required and must be atleast 3 characters.</b-form-invalid-feedback>
            </b-form-group>
          </b-col>

          <b-col lg="4" no-gutters>
            <b-form-group>
              <b-form-input
                v-model="$v.form.middle_name.$model"
                :state="validateState('middle_name')"
                aria-describedby="input-mname-feedback"
                placeholder="Middle name"
              ></b-form-input>

              <b-form-invalid-feedback
                id="input-mname-feedback"
              >This field is required and must be atleast 3 characters.</b-form-invalid-feedback>
            </b-form-group>
          </b-col>

          <b-col lg="4" no-gutters>
            <b-form-group>
              <b-form-input
                v-model="$v.form.last_name.$model"
                :state="validateState('last_name')"
                aria-describedby="input-lname-feedback"
                placeholder="Last name"
              ></b-form-input>

              <b-form-invalid-feedback
                id="input-lname-feedback"
              >This field is required and must be atleast 3 characters.</b-form-invalid-feedback>
            </b-form-group>
          </b-col>
        </b-form-row>
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
  name: "TeachersIndex",
  props: ["host"],
  data() {
    return {
      search: "",
      limit: 10,
      current_page: 1,
      teachers: null,
      teachers_fields: [
        {
          key: "first_name",
          label: "First name",
          sortable: true
        },
        {
          key: "last_name",
          label: "Last name",
          sortable: true
        },
        {
          key: "school.name",
          label: "School",
          sortable: true
        },
        {
          key: "index",
          label: "Action"
        }
      ],
      schools: null,
      schools_list: [],
      response: {},

      // ADD
      form: {
        school_id: null,
        first_name: null,
        last_name: null,
        middle_name: null
      },

      // EDIT
      edit_id: null,
      edit_index: null,

      // DELETE
      delete_id: null,
      delete_index: null
    };
  },
  validations: {
    form: {
      school_id: {
        required
      },
      first_name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(60)
      },
      last_name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(60)
      },
      middle_name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(60)
      }
    }
  },
  computed: {},
  mounted() {
    this.getTeachers();
    this.getSchools();
  },
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    getTeachers: function(page) {
      const teachersAPI = `${this.host}/teachers?search=${this.search}&limit=${this.limit}&page=${page}`;
      axios
        .get(teachersAPI)
        .then(response => {
          this.teachers = response.data.data;
          this.response = response.data;
        })
        .catch(err => console.log(err));
    },

    getSchools: function() {
      const schoolsAPI = `${this.host}/schools/raw`;
      axios
        .get(schoolsAPI)
        .then(response => {
          this.schools = response.data;

          for (let i = 0; i < this.schools.length; i++) {
            this.schools_list.push(this.schools[i].name);
          }
        })
        .catch(err => console.log(err));
    },

    formatDate: function(index) {
      const teachers = this.teachers;
      //return teachers[index].created_at.split('-')
      const month = teachers[index].created_at.split("-")[1];
      const dateDay = teachers[index].created_at.split("-")[2].split(" ")[0];
      const dateYear = teachers[index].created_at.split("-")[0];
      let dateMonth = null;
      switch (month) {
        case "01":
          dateMonth = "January";
          break;
        case "02":
          dateMonth = "February";
          break;
        case "03":
          dateMonth = "March";
          break;
        case "04":
          dateMonth = "April";
          break;
        case "05":
          dateMonth = "May";
          break;
        case "06":
          dateMonth = "June";
          break;
        case "07":
          dateMonth = "July";
          break;
        case "08":
          dateMonth = "August";
          break;
        case "09":
          dateMonth = "September";
          break;
        case "10":
          dateMonth = "October";
          break;
        case "11":
          dateMonth = "November";
          break;
        case "12":
          dateMonth = "December";
          break;
      }

      return `${dateMonth} ${dateDay}, ${dateYear}`;
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
      const teachersAPI = `${this.host}/teachers`;
      const data = {
        school_id: this.form.school_id,
        first_name: this.form.first_name,
        last_name: this.form.last_name,
        middle_name: this.form.middle_name
      };

      axios
        .post(teachersAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.getTeachers();
            this.resetForm();
            this.$bvModal.hide("add-modal");

            swal.fire({
              icon: "success",
              title: "Added",
              text: "Teacher successfully added",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to add teacher",
              timer: 3000
            });
          }
        })
        .catch(err => console.log(err));
    },

    edit: function(index) {
      this.$v.$reset();
      this.edit_id = this.teachers[index].id;
      this.edit_index = index;
      this.form.school_id = this.teachers[index].school_id;
      this.form.first_name = this.teachers[index].first_name;
      this.form.last_name = this.teachers[index].last_name;
      this.form.middle_name = this.teachers[index].middle_name;
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
      const teachersAPI = `${this.host}/teacher/${this.edit_id}`;
      const data = {
        school_id: this.form.school_id,
        first_name: this.form.first_name,
        last_name: this.form.last_name,
        middle_name: this.form.middle_name
      };

      axios
        .put(teachersAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.teachers[this.edit_index].school_id = this.form.school_id;
            this.teachers[this.edit_index].first_name = this.form.first_name;
            this.teachers[this.edit_index].middle_name = this.form.middle_name;
            this.teachers[this.edit_index].last_name = this.form.last_name;
            this.$v.$reset();
            this.$bvModal.hide("edit-modal");

            swal.fire({
              icon: "success",
              title: "Updated",
              text: "Teacher information successfully updated",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to update teacher information",
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
      this.delete_id = this.teachers[index].id;
      this.delete_index = index;
    },

    destroy: function(index) {
      const teachersAPI = `${this.host}/teacher/${this.delete_id}`;
      axios
        .delete(teachersAPI)
        .then(response => {
          if (response.data.status == 201) {
            this.teachers.splice(this.delete_index, 1);
            this.delete_index = null;
            swal.fire({
              icon: "success",
              title: "Deleted",
              text: "Teacher successfully deleted",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to delete teacher",
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

    resetForm: function() {
      this.$v.$reset();
      this.form.school_id = null;
      this.form.first_name = null;
      this.form.last_name = null;
      this.form.middle_name = null;
    }
  }
};
</script>

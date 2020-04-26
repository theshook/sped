<template>
  <div>
    <b-container class="mt-2">
      <div class="page-header">
        <h2 class="font-weight-bold">Teachers</h2>

        <b-breadcrumb :items="breadcrumb_link"></b-breadcrumb>
      </div>

      <b-card class="shadow-sm" no-body>
        <b-card-header>
          <div class="d-flex align-items-end">
            <div class="mr-auto">
              <b-button-group>
                <b-button variant="light" size="sm" class="text-sm border">
                  <b-icon icon="trash" class="mr-2"></b-icon>View Trash
                </b-button>

                <b-button variant="light" size="sm" class="text-sm border" @click="resetSearch">
                  <b-icon icon="arrow-repeat" class="mr-2"></b-icon>Refresh Table
                </b-button>

                <b-button variant="light" size="sm">
                  <b-icon icon="funnel" class="mr-2"></b-icon>Filter
                </b-button>

                <b-button variant="light" size="sm" v-b-modal.search-modal>
                  <b-icon icon="search" class="mr-2"></b-icon>Search
                </b-button>
              </b-button-group>
            </div>

            <div class="ml-auto">
              <b-button variant="primary" size="sm" v-b-modal.add-modal>
                <b-icon icon="pencil-square" class="mr-2"></b-icon>Add Teacher
              </b-button>
            </div>
          </div>
        </b-card-header>
        <b-card-body>
          <b-row class="mb-2 d-none">
            <b-col lg="6">
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
            id="teachers-table"
            :items="teachers"
            :fields="teachers_fields"
            :busy="table_busy"
            responsive="md"
            show-empty
          >
            <template v-slot:cell(name)="data">{{ data.item.first_name }} {{data.item.last_name}}</template>

            <template v-slot:cell(index)="data">
              <b-btn-group class="rounded">
                <b-button v-b-modal.edit-modal size="sm" variant="light" @click="edit(data.index)">
                  <b-icon icon="pencil"></b-icon>
                </b-button>

                <b-button
                  v-b-modal.delete-modal
                  size="sm"
                  variant="light"
                  @click="remove(data.index)"
                >
                  <b-icon icon="trash"></b-icon>
                </b-button>

                <!-- <b-button
                  v-b-modal.province-schools-modal
                  size="sm"
                  variant="light"
                  @click="getSchoolsList(data.index)"
                >
                  <b-icon icon="card-text" class="mr-2"></b-icon>Schools List
                </b-button>-->
              </b-btn-group>
            </template>

            <template v-slot:table-busy>
              <div class="text-center py-3">
                <b-spinner variant="primary"></b-spinner>
              </div>
            </template>

            <template v-slot:empty="scope">
              <div class="text-center py-3">
                <b-img
                  :src="'/images/no_data_3.svg'"
                  alt="No data banner"
                  blank-color="#f1f1f1"
                  rounted
                  fluid
                ></b-img>
                <p class="text-muted mt-3 mb-1">{{ scope.emptyText }}</p>
                <b-button variant="link" size="sm" @click="resetSearch">Reset search</b-button>
              </div>
            </template>
          </b-table>

          <b-container class="d-flex px-0" fluid>
            <small class="text-muted" v-if="teachers.length">
              Showing {{ response.from }} - {{ response.to }} of
              {{ response.total }} entries
            </small>

            <b-pagination
              v-if="teachers.length"
              class="ml-auto"
              size="sm"
              v-model="current_page"
              :per-page="Number(response.per_page)"
              :total-rows="Number(response.total)"
              @change="getTeachers"
              aria-controls="teachers-table"
            ></b-pagination>
          </b-container>
        </b-card-body>
      </b-card>
    </b-container>

    <!-- MODALS -->

    <!-- SEARCH -->
    <b-modal id="search-modal" class="bodyless-search-modal" hide-header hide-footer>
      <b-form v-on:submit.prevent>
        <b-form-input
          v-model="search"
          class="form-control"
          placeholder="Search"
          @input="searchTeachers"
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

    <!-- ADD -->
    <b-modal
      id="add-modal"
      title="Add Teacher"
      ok-variant="success"
      :ok-title="submit_disabled ? 'Adding' : 'Add'"
      :ok-disabled="submit_disabled"
      ok-only
      @ok="submitAdd"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form @keyup.enter="submitAdd">
        <b-form-group label="School" label-class="text-sm" class="mb-1">
          <b-form-select
            v-model="$v.form.school_id.$model"
            :state="validateState('school_id')"
            aria-describedby="input-school-feedback"
          >
            <template v-slot:first>
              <b-form-select-option :value="null" diabled>--</b-form-select-option>
            </template>

            <b-form-select-option
              v-for="school in schools"
              :key="school.id"
              :value="school.id"
            >{{school.name}}</b-form-select-option>
          </b-form-select>

          <b-form-invalid-feedback id="input-school-feedback">This field is required.</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="First name" label-class="text-sm">
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

        <b-form-group label="Middle name" label-class="text-sm">
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
      <b-form @keyup.enter="submitUpdate">
        <b-form-group label="School" label-class="text-sm" class="mb-1">
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

        <b-form-group label="First name" label-class="text-sm">
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

        <b-form-group label="Middle name" label-class="text-sm">
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
      </b-form>
    </b-modal>

    <!-- DELETE CONFIRM -->
    <b-modal
      id="delete-modal"
      title="Confirm"
      :ok-title="submit_disabled ? 'Deleting' : 'Delete'"
      :ok-disabled="submit_disabled"
      ok-variant="danger"
      @ok="submitDestroy"
      ok-only
      button-size="sm"
    >
      <p class="text-sm text-center text-muted mb-1">Are you sure you want to remove this teacher?</p>
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
      table_busy: false,
      submit_disabled: false,
      breadcrumb_link: [
        {
          text: "Dashboard",
          href: "/dashboard"
        },
        {
          text: "Teachers",
          href: "#"
        }
      ],
      search: "",
      limit: 10,
      current_page: 1,
      teachers: [],
      teachers_raw: [],
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
  async mounted() {
    await this.getTeachers();
    await this.getSchools();
  },
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    getTeachers: function(page) {
      this.table_busy = true;
      const teachersAPI = `/teachers?search=${this.search}&limit=${this.limit}&page=${page}`;
      axios
        .get(teachersAPI)
        .then(response => {
          this.teachers = response.data.paginated.data;
          this.teachers_raw = response.data.raw;
          this.response = response.data.paginated;
        })
        .catch(err => console.log(err))
        .finally(() => (this.table_busy = false));
    },

    getSchools: function() {
      const schoolsAPI = `/schools/raw`;
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

    searchTeachers: function() {
      let search = this.search.toLowerCase();

      if (!search) {
        this.getTeachers();
      }

      this.teachers = this.teachers_raw.filter(
        teacher => teacher.first_name.toLowerCase().indexOf(search) > -1
      );
    },

    submitAdd: function(event) {
      event.preventDefault();
      this.$v.form.$touch();
      this.submit_disabled = true;
      if (this.$v.form.$anyError) {
        return;
        this.submit_disabled = false;
      } else {
        this.add();
      }
    },

    add: function() {
      const teachersAPI = `/teachers`;
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
        .catch(err => console.log(err))
        .finally(() => (this.submit_disabled = false));
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
      this.submit_disabled = true;
      if (this.$v.form.$anyError) {
        return;
        this.submit_disabled = false;
      } else {
        this.update();
      }
    },

    update: function() {
      const teachersAPI = `/teacher/${this.edit_id}`;
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
        )
        .finally(() => (this.submit_disabled = false));
    },

    remove: function(index) {
      this.delete_id = this.teachers[index].id;
      this.delete_index = index;
    },

    submitDestroy: function(event) {
      event.preventDefault();
      this.submit_disabled = true;
      this.destroy();
    },

    destroy: function() {
      const teachersAPI = `/teacher/${this.delete_id}`;
      axios
        .delete(teachersAPI)
        .then(response => {
          if (response.data.status == 201) {
            this.teachers.splice(this.delete_index, 1);
            this.delete_index = null;
            this.$bvModal.hide("delete-modal");
            swal.fire({
              icon: "success",
              title: "Deleted",
              text: "Teacher successfully deleted",
              timer: 3000
            });
          } else {
            this.$bvModal.hide("delete-modal");
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
        )
        .finally(() => (this.submit_disabled = false));
    },

    resetForm: function() {
      this.$v.$reset();
      this.form.school_id = null;
      this.form.first_name = null;
      this.form.last_name = null;
      this.form.middle_name = null;
    },

    resetSearch: function() {
      this.search = "";
      this.getTeachers();
    },

    closeSearchModal: function() {
      this.$bvModal.hide("search-modal");
    }
  }
};
</script>

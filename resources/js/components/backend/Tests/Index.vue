<template>
  <div>
    <b-container class="mt-2">
      <div class="page-header">
        <h2 class="font-weight-bold">Tests</h2>

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
                  <b-icon icon="arrow-repeat" class="mr-2"></b-icon>Refresh
                  Table
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
                <b-icon icon="pencil-square" class="mr-2"></b-icon>Add Test
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
            id="tests-table"
            :items="tests"
            :fields="tests_fields"
            :busy="table_busy"
            responsive="md"
            show-empty
          >
            <template v-slot:cell(name)="data">{{ data.item.name }}</template>

            <template v-slot:cell(teacher)="data">
              {{
              data.item.teacher.first_name + " " + data.item.teacher.last_name
              }}
            </template>

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

                <b-button size="sm" variant="light" @click="view(data.index)">
                  <b-icon icon="eye" class="mr-2"></b-icon>View
                </b-button>

                <b-button size="sm" variant="light" @click="manage(data.index)">
                  <b-icon icon="filter-left" class="mr-2"></b-icon>Manage
                  Questions
                </b-button>

                <b-button
                  v-b-tooltip.hover.right
                  title="Publish as Assessment Test"
                  size="sm"
                  variant="success"
                  @click="publish(data.index)"
                >
                  <b-icon icon="layers" class="mr-2"></b-icon>Publish
                </b-button>
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
            <small class="text-muted" v-if="tests.length">
              Showing {{ response.from }} - {{ response.to }} of
              {{ response.total }} entries
            </small>

            <b-pagination
              v-if="tests.length"
              class="ml-auto"
              size="sm"
              v-model="current_page"
              :per-page="Number(response.per_page)"
              :total-rows="Number(response.total)"
              @change="getTests"
              aria-controls="tests-table"
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
          @input="searchTest"
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
      title="Add Test"
      ok-variant="success"
      :ok-title="submit_disabled ? 'Adding' : 'Add'"
      :ok-disabled="submit_disabled"
      ok-only
      @ok="submitAdd"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form @keyup.enter="submitAdd">
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
          <b-form-invalid-feedback
            id="input-title-feedback"
          >This field is required and must be atleast 3 characters</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Description" label-class="text-sm">
          <b-form-textarea
            v-model="$v.form.description.$model"
            :state="validateState('description')"
            rows="4"
            aria-describedby="input-description-feedback"
          ></b-form-textarea>

          <b-form-invalid-feedback>This field is required and must be atleast 10 characters</b-form-invalid-feedback>
        </b-form-group>
      </b-form>
    </b-modal>

    <!-- EDIT/UPDATE -->
    <b-modal
      id="edit-modal"
      title="Update Province Information"
      ok-variant="success"
      :ok-title="submit_disabled ? 'Saving' : 'Save Changes'"
      :ok-disabled="submit_disabled"
      ok-only
      @ok="submitUpdate"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form @keyup.enter="submitUpdate">
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
          <b-form-invalid-feedback
            id="input-title-feedback"
          >This field is required and must be atleast 3 characters</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Description" label-class="text-sm">
          <b-form-textarea
            v-model="$v.form.description.$model"
            :state="validateState('description')"
            rows="4"
            aria-describedby="input-description-feedback"
          ></b-form-textarea>

          <b-form-invalid-feedback>This field is required and must be atleast 10 characters</b-form-invalid-feedback>
        </b-form-group>
      </b-form>
    </b-modal>

    <!-- DELETE CONFIRM -->
    <b-modal
      id="delete-modal"
      title="Confirm"
      ok-variant="danger"
      :ok-title="submit_disabled ? 'Deleting' : 'Delete'"
      :ok-disabled="submit_disabled"
      @ok="submitDestroy"
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
      submit_disabled: false,
      table_busy: false,
      breadcrumb_link: [
        {
          text: "Dashboard",
          href: "/dashboard"
        },
        {
          text: "Tests",
          href: "#"
        }
      ],
      search: "",
      limit: 10,
      current_page: 1,
      teachers: [],
      tests: [],
      tests_raw: [],
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
  async mounted() {
    await this.getTests();
    await this.getTeachers();
  },
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    getTests: function(page) {
      this.table_busy = true;
      const testsAPI = `/tests?search=${this.search}&limit=${this.limit}`;
      axios
        .get(testsAPI)
        .then(response => {
          this.tests_raw = response.data.raw.data;
          this.tests = response.data.paginated.data;
          this.response = response.data.paginated;
        })
        .catch(err => console.log(err))
        .finally(() => (this.table_busy = false));
    },

    getTeachers: function() {
      let teachersAPI = `/teachers/raw`;
      axios
        .get(teachersAPI)
        .then(response => {
          let teacherArr = response.data;

          teacherArr.forEach(teacher => {
            let fullName = `${teacher.first_name} ${teacher.last_name}`;

            this.teachers.push({
              id: teacher.id,
              name: fullName,
              school: teacher.school.name
            });
          });
        })
        .catch(err => console.log(err.response));
    },

    searchTest: function() {
      let search = this.search.toLowerCase();

      if (!search) {
        this.getTests();
      }

      this.tests = this.tests_raw.filter(
        test => test.title.toLowerCase().indexOf(search) > -1
      );
    },

    view: function(index) {
      let id = this.tests[index].id;
      window.location = `/configure/test/${id}/show`;
    },

    manage: function(index) {
      let id = this.tests[index].id;
      window.location = `/configure/test/${id}/manage`;
    },

    publish: function(index) {},

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
      const testsAPI = `/tests`;
      const data = {
        teacher_id: this.form.teacher_id,
        title: this.form.title,
        description: this.form.description
      };

      axios
        .post(testsAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.getTests();
            this.resetForm();
            this.$bvModal.hide("add-modal");

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
        )
        .finally(() => (this.submit_disabled = false));
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
      this.submit_disabled = true;
      if (this.$v.form.$anyError) {
        return;
        this.submit_disabled = false;
      } else {
        this.update();
      }
    },

    update: function() {
      const testsAPI = `/test/${this.edit_id}`;
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

            this.$bvModal.hide("edit-modal");
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
        )
        .finally(() => (this.submit_disabled = false));
    },

    remove: function(index) {
      this.delete_id = this.tests[index].id;
      this.delete_index = index;
    },

    submitDestroy: function(event) {
      event.preventDefault();
      this.destroy();
    },

    destroy: function(index) {
      this.submit_disabled = true;
      const testsAPI = `/test/${this.delete_id}`;
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
        )
        .finally(() => (this.submit_disabled = false));
    },

    resetForm: function() {
      this.$v.$reset();
      this.form.teacher_id = null;
      this.form.title = null;
      this.form.description = null;
    },

    resetSearch: function() {
      this.search = "";
      this.getProvinces();
    },

    closeSearchModal: function() {
      this.$bvModal.hide("search-modal");
    }
  }
};
</script>

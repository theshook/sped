<template>
  <div>
    <b-container class="mt-2">
      <div class="page-header">
        <h2 class="font-weight-bold">Schools</h2>

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
                <b-icon icon="pencil-square" class="mr-2"></b-icon>Add School
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
                  @change="getSchools"
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
                <b-form-input size="sm" v-model="search" @input="getSchools" placeholder="Search"></b-form-input>
              </b-form>
            </b-col>
          </b-row>

          <b-table
            borderless
            id="schools-table"
            :items="schools"
            :fields="schools_fields"
            :busy="table_busy"
            responsive="md"
            show-empty
          >
            <template v-slot:cell(name)="data">{{ data.item.name }}</template>

            <template v-slot:cell(province_id)="data">{{ data.item.province_id }}</template>

            <template v-slot:cell(index)="data">
              <b-btn-group>
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

                <b-button
                  v-b-modal.school-teachers-modal
                  size="sm"
                  variant="light"
                  @click="getTeacherList(data.index)"
                >
                  <b-icon icon="card-text" class="mr-2"></b-icon>Teachers List
                </b-button>

                <b-button
                  v-b-modal.school-pupils-modal
                  size="sm"
                  variant="light"
                  @click="getPupilList(data.index)"
                >
                  <b-icon icon="people" class="mr-2"></b-icon>Pupils List
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
            <small class="text-muted" v-if="schools.length">
              Showing {{ response.from }} - {{ response.to }} of
              {{ response.total }} entries
            </small>

            <b-pagination
              v-if="schools.length"
              class="ml-auto"
              size="sm"
              v-model="current_page"
              :per-page="Number(response.per_page)"
              :total-rows="Number(response.total)"
              @change="getSchools"
              aria-controls="schools-table"
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
          @input="searchSchool"
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
      title="Add School"
      ok-variant="success"
      :ok-title="submit_disabled ? 'Adding' : 'Add'"
      :ok-disabled="submit_disabled"
      ok-only
      @ok="submitAdd"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form v-on:submit.prevent="submitAdd">
        <b-form-group label="Name" label-class="text-sm">
          <b-form-input
            v-model="$v.form.name.$model"
            :state="validateState('name')"
            aria-describedby="input-name-feedback"
          ></b-form-input>

          <b-form-invalid-feedback
            id="invalid-name-feedback"
          >This form is required and must be atleast 3 characters.</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Province" label-class="text-sm">
          <b-form-select
            v-model="$v.form.province_id.$model"
            :state="validateState('province_id')"
            aria-describedby="input-province-feedback"
          >
            <b-form-select-option
              v-for="province in provinces"
              :key="province.id"
              :value="province.id"
            >{{ province.name }}</b-form-select-option>
          </b-form-select>

          <b-form-invalid-feedback id="input-province-feedback">This field is required</b-form-invalid-feedback>
        </b-form-group>
      </b-form>
    </b-modal>

    <!-- EDIT/UPDATE -->
    <b-modal
      id="edit-modal"
      title="Update School Information"
      ok-variant="success"
      :ok-title="submit_disabled ? 'Saving' : 'Save Changes'"
      :ok-disabled="submit_disabled"
      ok-only
      @ok="submitUpdate"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form v-on:submit.prevent="submitUpdate">
        <b-form-group label="Name" label-class="text-sm">
          <b-form-input
            v-model="$v.form.name.$model"
            :state="validateState('name')"
            aria-describedby="input-name-feedback"
          ></b-form-input>

          <b-form-invalid-feedback
            id="invalid-name-feedback"
          >This form is required and must be atleast 3 characters.</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label="Province" label-class="text-sm">
          <b-form-select
            v-model="$v.form.province_id.$model"
            :state="validateState('province_id')"
            aria-describedby="input-province-feedback"
          >
            <b-form-select-option
              v-for="province in provinces"
              :key="province.id"
              :value="province.id"
            >{{ province.name }}</b-form-select-option>
          </b-form-select>

          <b-form-invalid-feedback id="input-province-feedback">This field is required</b-form-invalid-feedback>
        </b-form-group>
      </b-form>
    </b-modal>

    <!-- DELETE CONFIRM -->
    <b-modal
      id="delete-modal"
      title="Delete"
      ok-variant="danger"
      :ok-title="submit_disabled ? 'Deleting' : 'Delete'"
      :ok-disabled="submit_disabled"
      @ok="submitDestroy"
      ok-only
      button-size="sm"
    >
      <p class="text-sm text-center text-muted mb-1">Are you sure you want to remove this school?</p>
    </b-modal>

    <!-- SCHOOL TEACHERS LIST -->
    <b-modal
      id="school-teachers-modal"
      :title="'Teachers in ' + school_title"
      @hidden="resetSchoolTeachers"
      body-class="pt-0"
      hide-footer
      scrollable
    >
      <div v-if="school_teachers.length">
        <b-table borderless :fields="school_teacher_fields" :items="school_teachers">
          <template v-slot:cell(name)="data">{{data.item.first_name}} {{data.item.last_name}}</template>
        </b-table>
      </div>

      <div class="text-center py-3" v-else>
        <p class="text-muted">No teachers listed to this school.</p>
      </div>
    </b-modal>

    <!-- SCHOOL TEACHERS LIST -->
    <b-modal
      id="school-pupils-modal"
      :title="'Pupils in ' + school_title"
      @hidden="resetSchoolPupils"
      body-class="pt-0"
      hide-footer
      scrollable
    >
      <div v-if="school_pupils.length">
        <b-table borderless :fields="school_pupil_fields" :items="school_pupils">
          <template v-slot:cell(name)="data">{{data.item.first_name}} {{data.item.last_name}}</template>
        </b-table>
      </div>

      <div class="text-center py-3" v-else>
        <p class="text-muted">No pupils listed to this school.</p>
      </div>
    </b-modal>
  </div>
</template>

<script>
import { required, minLength, maxLength } from "vuelidate/lib/validators";
export default {
  name: "SchoolsIndex",
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
          text: "Schools",
          href: "#"
        }
      ],
      schools: [],
      schools_raw: [],
      school_title: null,
      school_teachers: [],
      school_pupils: [],
      school_teacher_fields: [
        {
          key: "name",
          label: "Name",
          sortable: true
        }
      ],
      school_pupil_fields: [
        {
          key: "name",
          label: "Name",
          sortable: true
        }
      ],
      schools_fields: [
        {
          key: "name",
          label: "Name",
          sortable: true
        },
        {
          key: "province.name",
          label: "Province",
          sortable: true
        },
        {
          key: "index",
          label: "Action"
        }
      ],

      provinces: [],
      provinces_list: [],
      search: "",
      limit: 10,
      current_page: 1,
      perPage: 10,
      response: {},

      // ADD
      province: null,
      form: {
        province_id: null,
        name: null
      },

      // EDIT
      edit_id: null,
      edit_index: null,
      edit_name: null,
      edit_province_id: null,

      // DELETE
      delete_id: null,
      delete_index: null
    };
  },
  validations: {
    form: {
      name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(100)
      },

      province_id: {
        required
      }
    }
  },
  computed: {},
  mounted() {
    this.getSchools();
    this.getProvinces();
  },
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    searchSchool: function() {
      let search = this.search.toLowerCase();

      if (!search) {
        this.getSchools();
      }

      this.schools = this.schools_raw.filter(
        school => school.name.toLowerCase().indexOf(search) > -1
      );
    },

    getSchools: function(page) {
      this.table_busy = true;
      const schoolsAPI = `/schools?search=${this.search}&limit=${this.limit}&page=${page}`;
      axios
        .get(schoolsAPI)
        .then(response => {
          this.schools = response.data.paginated.data;
          this.schools_raw = response.data.raw;
          this.response = response.data.paginated;
        })
        .catch(err => console.log(err))
        .finally(() => (this.table_busy = false));
    },

    getProvinces: function() {
      const provincesAPI = `/provinces/raw`;
      axios
        .get(provincesAPI)
        .then(response => {
          this.provinces = response.data;
        })
        .catch(err => console.log(err));
    },

    getTeacherList: function(index) {
      this.school_title = this.schools[index].name;
      this.school_teachers = this.schools[index].teachers;
    },

    getPupilList: function(index) {
      this.school_title = this.schools[index].name;
      this.school_pupils = this.schools[index].pupils;

      console.log(this.schools[index].pupils);
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
      const schoolsAPI = `/schools`;

      const data = {
        province_id: this.form.province_id,
        name: this.form.name
      };

      axios
        .post(schoolsAPI, data)
        .then(response => {
          if (response.status == 201) {
            this.getSchools();
            this.resetForm();
            this.$bvModal.hide("add-modal");

            swal.fire({
              icon: "success",
              title: "Added",
              text: "School information successfully added",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to add school information",
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

    edit: function(index) {
      this.edit_id = this.schools[index].id;
      this.edit_index = index;
      this.form.name = this.schools[index].name;
      this.form.province_id = this.schools[index].province_id;
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
      const schoolsAPI = `/school/${this.edit_id}`;
      const data = {
        province_id: this.form.province_id,
        name: this.form.name
      };

      axios
        .put(schoolsAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.getSchools();
            this.resetForm();
            this.$bvModal.hide("edit-modal");

            swal.fire({
              icon: "success",
              title: "Updated",
              text: "Province information successfully updated",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to update province information",
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

    remove: function(index) {
      this.delete_id = this.schools[index].id;
      this.delete_index = index;
    },

    submitDestroy: function(event) {
      event.preventDefault();
      this.destroy();
    },

    destroy: function() {
      this.submit_disabled = true;

      const schoolsAPI = `/school/${this.delete_id}`;
      axios
        .delete(schoolsAPI)
        .then(response => {
          if (response.data.status == 201) {
            this.schools.splice(this.delete_index, 1);
            this.delete_index = null;
            this.$bvModal.hide("delete-modal");
            swal.fire({
              icon: "success",
              title: "Deleted",
              text: "School information successfully deleted",
              timer: 3000
            });
          } else if (response.data.status == 400) {
            swal.fire({
              icon: "error",
              title: "Failed",
              text:
                "Cannot be deleted, one or more pupils are assigned to this school",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to delete school information",
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

    resetForm: function() {
      this.$v.$reset();
      this.form.province_id = null;
      this.form.name = null;
    },

    resetSchoolTeachers: function() {
      this.school_title = null;
      this.school_teachers = [];
    },

    resetSchoolPupils: function() {
      this.school_title = null;
      this.school_pupils = [];
    },

    resetSearch: function() {
      this.search = "";
      this.getSchools();
    },

    closeSearchModal: function() {
      this.$bvModal.hide("search-modal");
    }
  }
};
</script>

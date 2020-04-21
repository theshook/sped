<template>
  <div>
    <b-container class="mt-2">
      <div class="page-header">
        <h2 class="font-weight-bold">Provinces</h2>

        <b-breadcrumb :items="breadcrumb_link"></b-breadcrumb>
      </div>

      <b-card class="shadow-sm" no-body>
        <b-card-header>
          <div class="d-flex align-items-end">
            <div class="mr-auto">
              <b-button-group>
                <b-button variant="light" size="sm">
                  <b-icon icon="funnel" class="mr-2"></b-icon>Filter
                </b-button>

                <b-button variant="light" size="sm" v-b-modal.search-modal>
                  <b-icon icon="search" class="mr-2"></b-icon>Search
                </b-button>
              </b-button-group>
            </div>

            <div class="ml-auto">
              <b-button variant="primary" size="sm" class="mr-2" v-b-modal.add-modal>
                <b-icon icon="pencil-square" class="mr-2"></b-icon>Add Province
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
                  @change="getProvinces"
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
                <b-form-input size="sm" v-model="search" @input="getProvinces" placeholder="Search"></b-form-input>
              </b-form>
            </b-col>
          </b-row>

          <b-table
            borderless
            id="province-table"
            :items="provinces"
            :fields="provinces_fields"
            :busy="table_busy"
            responsive="md"
            show-empty
          >
            <template v-slot:cell(name)="data">{{ data.item.name }}</template>

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

                <b-button
                  v-b-modal.province-schools-modal
                  size="sm"
                  variant="light"
                  @click="getSchoolsList(data.index)"
                >
                  <b-icon icon="card-text" class="mr-2"></b-icon>Schools List
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
            <small class="text-muted" v-if="provinces.length">
              Showing {{ response.from }} - {{ response.to }} of
              {{ response.total }} entries
            </small>

            <b-pagination
              v-if="provinces.length"
              class="ml-auto"
              size="sm"
              v-model="current_page"
              :per-page="Number(response.per_page)"
              :total-rows="Number(response.total)"
              @change="getProvinces"
              aria-controls="province-table"
            ></b-pagination>
          </b-container>
        </b-card-body>
      </b-card>
    </b-container>

    <!-- MODALS -->

    <!-- SEARCH -->
    <b-modal id="search-modal" class="bodyless-search-modal" hide-header hide-footer>
      <b-form>
        <b-form-input
          v-model="search"
          class="form-control"
          placeholder="Search"
          @input="searchProvince"
          autofocus
        ></b-form-input>
      </b-form>

      <div class="d-flex mt-1 px-2">
        <small class="text-muted mt-1 mr-auto">
          Press
          <span class="badge badge-dark">ESC</span> to close
        </small>

        <div class="ml-auto">
          <b-button variant="link" size="sm" @click="closeSearchModal">Close</b-button>
        </div>
      </div>
    </b-modal>

    <!-- ADD -->
    <b-modal
      id="add-modal"
      title="Add Province"
      ok-variant="success"
      :ok-title="submit_disabled ? 'Adding' : 'Add'"
      :ok-disabled="submit_disabled"
      ok-only
      @ok="submitAdd"
      @hidden="resetForm"
      button-size="sm"
    >
      <!-- -->
      <b-form v-on:submit.prevent="submitAdd">
        <b-form-group label="Name" label-class="text-sm">
          <b-form-input
            v-model="$v.form.name.$model"
            :state="validateState('name')"
            aria-describedby="invalid-input-name"
          ></b-form-input>

          <b-form-invalid-feedback id="invalid-input-name">
            This field is required and must be atleast 3
            characters.
          </b-form-invalid-feedback>
        </b-form-group>
      </b-form>
    </b-modal>

    <!-- EDIT/UPDATE -->
    <b-modal
      id="edit-modal"
      title="Update Province Information"
      :ok-title="submit_disabled ? 'Saving' : 'Save Changes'"
      :ok-disabled="submit_disabled"
      ok-variant="success"
      ok-only
      @ok="submitUpdate"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form v-on:submit.prevent="submitUpdate">
        <input type="hidden" v-model="edit_id" />
        <b-form-group label="Name" label-class="text-sm">
          <b-form-input
            v-model="$v.form.name.$model"
            :state="validateState('name')"
            aria-describedby="invalid-input-name"
          ></b-form-input>

          <b-form-invalid-feedback id="invalid-input-name">
            This field is required and must be atleast 3
            characters.
          </b-form-invalid-feedback>
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
      <p class="text-sm text-center text-muted mb-1">Are you sure you want to remove this province?</p>
    </b-modal>

    <!-- PROVINCE SCHOOLS LIST -->
    <b-modal
      id="province-schools-modal"
      :title="'Schools in ' + province_title"
      @hidden="resetProvinceSchool"
      body-class="pt-0"
      hide-footer
      scrollable
    >
      <div v-if="province_schools.length">
        <b-table borderless :fields="province_schools_fields" :items="province_schools"></b-table>
      </div>

      <div class="text-center py-3" v-else>
        <p class="text-muted">No schools listed to this province.</p>
      </div>
    </b-modal>
  </div>
</template>

<script>
import { required, minLength, maxLength } from "vuelidate/lib/validators";
export default {
  name: "ProvincesIndex",
  data() {
    return {
      host: process.env.MIX_API_HOST,

      table_busy: false,
      submit_disabled: false,
      breadcrumb_link: [
        {
          text: "Dashboard",
          href: "/dashboard"
        },
        {
          text: "Province",
          href: "#"
        }
      ],
      search: "",
      limit: 10,
      current_page: 1,
      provinces: [],
      provinces_raw: [],
      province_title: null,
      province_schools: [],
      province_schools_fields: [
        {
          key: "name",
          label: "School name",
          sortable: true
        }
      ],
      provinces_fields: [
        {
          key: "name",
          label: "Province",
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
        name: ""
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
      name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(60)
      }
    }
  },
  computed: {},
  mounted() {
    this.getProvinces();
  },
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    searchProvince: function() {
      let search = this.search.toLowerCase();
      let provinceArr = this.provinces;

      if (!search) {
        this.getProvinces();
      }

      this.provinces = this.provinces_raw.filter(
        province => province.name.toLowerCase().indexOf(search) > -1
      );
    },

    getProvinces: function(page) {
      this.table_busy = true;
      const provincesAPI = `${this.host}/provinces?search=${this.search}&limit=${this.limit}&page=${page}`;
      axios
        .get(provincesAPI)
        .then(response => {
          this.provinces = response.data.paginated.data;
          this.provinces_raw = response.data.raw;
          this.response = response.data.paginated;
        })
        .catch(err => console.log(err))
        .finally(() => (this.table_busy = false));
    },

    getSchoolsList: function(index) {
      this.province_title = this.provinces[index].name;
      this.province_schools = this.provinces[index].schools;
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
      const provincesAPI = `${this.host}/provinces`;
      const data = {
        name: this.form.name
      };

      axios
        .post(provincesAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.getProvinces();
            this.resetForm();
            this.$bvModal.hide("add-modal");

            swal.fire({
              icon: "success",
              title: "Added",
              text: "Province information successfully added",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to add province information",
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

    edit: function(index) {
      this.edit_id = this.provinces[index].id;
      this.edit_index = index;
      this.form.name = this.provinces[index].name;
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
      const provincesAPI = `${this.host}/province/${this.edit_id}`;
      const data = {
        name: this.form.name
      };

      axios
        .put(provincesAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.provinces[this.edit_index].name = this.form.name;
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
      this.delete_id = this.provinces[index].id;
      this.delete_index = index;
    },

    submitDestroy: function(event) {
      event.preventDefault();
      this.destroy();
    },

    destroy: function() {
      this.submit_disabled = true;
      const provincesAPI = `${this.host}/province/${this.delete_id}`;
      axios
        .delete(provincesAPI)
        .then(response => {
          if (response.data.status == 201) {
            this.provinces.splice(this.delete_index, 1);
            this.delete_index = null;
            this.getProvinces();
            this.$bvModal.hide("delete-modal");

            swal.fire({
              icon: "success",
              title: "Deleted",
              text: "Province information successfully deleted",
              timer: 3000
            });
          } else if (response.data.status == 400) {
            this.$bvModal.hide("delete-modal");
            swal.fire({
              icon: "error",
              title: "Failed",
              text:
                "Cannot be deleted, one or more schools are assigned to this province",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to delete province information",
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
        .finally(() => {
          setTimeout(() => {
            this.submit_disabled = false;
          }, 2000);
        });
    },

    resetForm: function() {
      this.$v.$reset();
      this.form.name = null;
    },

    resetSearch: function() {
      this.search = "";
      this.getProvinces();
    },

    resetProvinceSchool: function() {
      this.province_title = null;
      this.province_schools = [];
    },

    closeSearchModal: function() {
      this.$bvModal.hide("search-modal");
    }
  }
};
</script>

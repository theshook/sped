<template>
  <div>
    <b-container class="mt-2">
      <b-card class="shadow-sm">
        <b-card-body>
          <b-row>
            <b-col lg="6">
              <h2 class="text-primary font-weight-bold">Provinces</h2>
            </b-col>

            <b-col lg="6">
              <div class="d-flex justify-content-end align-baseline">
                <b-button variant="primary" size="sm" v-b-modal.add-modal>
                  <b-icon icon="pencil"></b-icon>
                  <!-- <span class="fa fa-fw fa-plus-circle"></span> -->
                  Add Province
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
            striped
            hover
            id="province-table"
            :items="provinces"
            :fields="provinces_fields"
            :busy="table_busy"
            responsive="md"
            show-empty
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

            <template v-slot:table-busy>
              <div class="text-center py-3">
                <b-spinner class="mb-2" variant="primary"></b-spinner>
                <p class="text-muted mb-0">Loading ...</p>
              </div>
            </template>

            <template v-slot:empty="scope">
              <div class="text-center py-3">
                <p class="text-muted mb-0">{{scope.emptyText}}</p>
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
              @change="getProvinces"
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
      title="Add Province"
      ok-title="Submit"
      ok-variant="success"
      ok-only
      @ok="submitAdd"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form>
        <b-form-group label="Name" label-class="text-sm">
          <b-form-input
            v-model="$v.form.name.$model"
            :state="validateState('name')"
            aria-describedby="invalid-input-name"
          ></b-form-input>

          <b-form-invalid-feedback
            id="invalid-input-name"
          >This field is required and must be atleast 3 characters.</b-form-invalid-feedback>
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
      <b-form ref="form">
        <input type="hidden" v-model="edit_id" />
        <b-form-group label="Name" label-class="text-sm">
          <b-form-input
            v-model="$v.form.name.$model"
            :state="validateState('name')"
            aria-describedby="invalid-input-name"
          ></b-form-input>

          <b-form-invalid-feedback
            id="invalid-input-name"
          >This field is required and must be atleast 3 characters.</b-form-invalid-feedback>
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
  name: "ProvincesIndex",
  props: ["host"],
  data() {
    return {
      table_busy: false,
      search: "",
      limit: 10,
      current_page: 1,
      provinces: null,
      provinces_fields: [
        {
          key: "name",
          label: "Name",
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

    getProvinces: function(page) {
      this.table_busy = true;
      const provincesAPI = `${this.host}/provinces?search=${this.search}&limit=${this.limit}&page=${page}`;
      axios
        .get(provincesAPI)
        .then(response => {
          this.provinces = response.data.data;
          this.response = response.data;
        })
        .catch(err => console.log(err))
        .finally(() => this.table_busy = false);
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
        );
    },

    edit: function(index) {
      this.edit_id = this.provinces[index].id;
      this.edit_index = index;
      this.form.name = this.provinces[index].name;
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
        );
    },

    remove: function(index) {
      this.delete_id = this.provinces[index].id;
      this.delete_index = index;
    },

    destroy: function(index) {
      const provincesAPI = `${this.host}/province/${this.delete_id}`;
      axios
        .delete(provincesAPI)
        .then(response => {
          if (response.data.status == 201) {
            this.provinces.splice(this.delete_index, 1);
            this.delete_index = null;

            swal.fire({
              icon: "success",
              title: "Deleted",
              text: "Province information successfully deleted",
              timer: 3000
            });
          } else if (response.data.status == 400) {
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
        );
    },

    resetForm: function() {
      this.$v.$reset();
      this.form.name = null;
    }
  }
};
</script>

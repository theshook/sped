<template>
  <div>
    <b-container class="mb-2">
      <b-card class="shadow-sm">
        <b-card-body>
          <b-row>
            <b-col lg="6">
              <h2 class="text-primary font-weight-bold">Schools (Trash)</h2>
            </b-col>

            <b-col lg="6">
              <div class="d-flex justify-content-end align-baseline">
                <b-button class="text-white" variant="warning" size="sm" v-b-modal.delete-all-modal>
                  <b-icon icon="trash"></b-icon>Empty Trash
                </b-button>
              </div>
            </b-col>
          </b-row>

          <b-row class="mb-2">
            <b-col lg="6">
              <!-- <b-form-select MY BUG EWAN KO PAREHAS NAMAN SA BABA HAHAHAHA
							@change="getDeletedSchools"
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
                  @change="getDeletedSchools"
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
                <b-form-input
                  size="sm"
                  v-model="search"
                  @input="getDeletedSchools"
                  placeholder="Search"
                ></b-form-input>
              </b-form>
            </b-col>
          </b-row>

          <b-table
            borderless
            striped
            hover
            id="schools-table"
            :items="schools"
            :fields="schools_fields"
            responsive="md"
          >
            <template v-slot:cell(name)="data">{{ data.item.name }}</template>

            <template v-slot:cell(index)="data">
              <b-btn-group>
                <b-button
                  v-b-modal.restore-modal
                  size="sm"
                  variant="success"
                  class="text-white"
                  @click="restore(data.index)"
                >
                  <!-- <b-icon icon="pencil"></b-icon> -->
                  Restore
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
              @change="getDeletedSchools"
              aria-controls="schools-table"
            ></b-pagination>
          </b-container>
        </b-card-body>
      </b-card>
    </b-container>

    <!-- RESTORE CONFIRM -->
    <b-modal
      id="restore-modal"
      title="Confirm"
      ok-title="Continue"
      ok-variant="danger"
      ok-only
      @ok="restoreConfirm"
      button-size="sm"
    >
      <p class="text-center text-muted mb-1">Are you sure you want to restore this school?</p>
    </b-modal>

    <!-- DELETE ALL CONFIRM -->
    <b-modal
      id="delete-all-modal"
      title="Confirm"
      ok-title="Continue"
      ok-variant="danger"
      ok-only
      @ok="removeAll"
      button-size="sm"
    >
      <p
        class="text-center text-muted mb-1"
      >Are you sure you want to permanently delete these schools?</p>
    </b-modal>

    <!-- DELETE CONFIRM -->
    <b-modal
      id="delete-modal"
      title="Confirm"
      ok-title="Continue"
      ok-variant="danger"
      ok-only
      @ok="removeConfirm"
      button-size="sm"
    >
      <p
        class="text-center text-muted mb-1"
      >Are you sure you want to permanently delete these schools?</p>
    </b-modal>
  </div>
</template>

<script>
export default {
  name: "SchoolsTrashIndex",
  props: ["host"],
  data() {
    return {
      search: "",
      limit: 10,
      current_page: 1,
      schools: null,
      schools_fields: [
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
      response: {},
      restore_index: null,
      delete_index: null
    };
  },
  validations: {},
  computed: {},
  mounted() {
    this.getDeletedSchools();
  },
  methods: {
    getDeletedSchools: function(page) {
      const deletedSchoolsAPI = `${this.host}/schools/deleted?search=${this.search}&limit=${this.limit}&page=${page}`;
      axios
        .get(deletedSchoolsAPI)
        .then(response => {
          this.schools = response.data.data;
          this.response = response.data;
          console.log(response.data.data);
        })
        .catch(err => console.log(err));
    },

    restore: function(index) {
      this.restore_index = index;
    },

    restoreConfirm: function() {
      let id = this.schools[this.restore_index].id;
      const deletedSchoolsAPI = `${this.host}/schools/restore/${id}`;
      axios
        .post(deletedSchoolsAPI)
        .then(response => {
          if (response.data.status == 201) {
            this.schools.splice(this.restore_index, 1);
            this.restore_index = null;
            swal.fire({
              icon: "success",
              title: "Restored",
              text: "School successfully restored",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to restore school",
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
        });
    },

    restoreAll: function() {},

    remove: function(index) {
      this.delete_index = index;
    },

    removeConfirm: function() {
      let id = this.schools[this.delete_index].id;
      const deletedSchoolsAPI = `${this.host}/schools/delete/${id}`;
      axios
        .delete(deletedSchoolsAPI)
        .then(response => {
          if (response.data.status == 201) {
            this.schools.splice(this.delete_index, 1);
            swal.fire({
              icon: "success",
              title: "Deleted",
              text: "School successfully deleted",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to delete school",
              timer: 3000
            });
          }
        })
        .catch(err => {
          swal.fire({
            icon: "error",
            title: err.response.data.message,
            text: err.response.data.errors,
            timer: 3000
          });
        });
    },

    removeAll: function() {
      const deletedSchoolsAPI = `${this.host}/schools/delete/all`;
      axios
        .delete(deletedSchoolsAPI)
        .then(response => {
          if (response.data.status == 201) {
            this.getDeletedSchools();
            swal.fire({
              icon: "success",
              title: "Deleted",
              text: "All schools successfully deleted",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to delete all schools",
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
        });
    }
  }
};
</script>

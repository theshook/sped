<template>
  <div>
    <b-container class="mb-2">
      <b-card class="shadow-sm">
        <b-card-body>
          <b-row>
            <b-col lg="6">
              <h2 class="text-primary font-weight-bold">tests (Trash)</h2>
            </b-col>

            <b-col lg="6">
              <div class="d-flex justify-content-end align-baseline">
                <b-button
                  class="text-white"
                  variant="success"
                  size="sm"
                  v-b-modal.restore-all-modal
                >
                  <b-icon class="mr-2" icon="check-circle"></b-icon>Restore All
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
                  @change="getDeletedTests"
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
                  @input="getDeletedTests"
                  placeholder="Search"
                ></b-form-input>
              </b-form>
            </b-col>
          </b-row>

          <b-table
            borderless
            striped
            hover
            id="tests-table"
            :items="tests"
            :fields="tests_fields"
            responsive="md"
          >
            <template v-slot:cell(title)="data">{{ data.item.title }}</template>

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
              @change="getDeletedTests"
              aria-controls="tests-table"
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
      ok-variant="success"
      ok-only
      @ok="restoreConfirm"
      button-size="sm"
    >
      <p class="text-center text-muted mb-1">Are you sure you want to restore this test?</p>
    </b-modal>

    <!-- RESTORE ALL CONFIRM -->
    <b-modal
      id="restore-all-modal"
      title="Confirm"
      ok-title="Continue"
      ok-variant="success"
      ok-only
      @ok="restoreAll"
      button-size="sm"
    >
      <p class="text-center text-muted mb-1">Are you sure you want to restore these tests?</p>
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
      >Are you sure you want to permanently delete these tests?</p>
    </b-modal>
  </div>
</template>

<script>
export default {
  name: "TestsTrashIndex",
  props: ["host"],
  data() {
    return {
      search: "",
      limit: 10,
      current_page: 1,
      tests: null,
      tests_fields: [
        {
          key: "title",
          label: "Title",
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
    this.getDeletedTests();
  },
  methods: {
    getDeletedTests: function(page) {
      const deletedTestsAPI = `${this.host}/tests/deleted?search=${this.search}&limit=${this.limit}&page=${page}`;
      axios
        .get(deletedTestsAPI)
        .then(response => {
          this.tests = response.data.data;
          this.response = response.data;
        })
        .catch(err => console.log(err));
    },

    restore: function(index) {
      this.restore_index = index;
    },

    restoreConfirm: function() {
      let id = this.tests[this.restore_index].id;
      const deletedTestsAPI = `${this.host}/tests/restore`;
      const data = {
        key: id
      };
      axios
        .post(deletedTestsAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.tests.splice(this.restore_index, 1);
            this.restore_index = null;
            swal.fire({
              icon: "success",
              title: "Restored",
              text: "Test successfully restored",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to test school",
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

    restoreAll: function() {
      const deletedTestsAPI = `${this.host}/tests/restore`;
      const data = {
        key: "all"
      };
      axios
        .post(deletedTestsAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.tests = null;
            swal.fire({
              icon: "success",
              title: "Restored",
              text: "All test successfully restored",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to restore tests",
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

    remove: function(index) {
      this.delete_index = index;
    },

    removeConfirm: function() {
      let id = this.tests[this.delete_index].id;
      const deletedTestsAPI = `${this.host}/tests/delete/${id}`;
      axios
        .delete(deletedTestsAPI)
        .then(response => {
          if (response.data.status == 201) {
            this.tests.splice(this.delete_index, 1);
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
        .catch(err => {
          swal.fire({
            icon: "error",
            title: err.response.data.message,
            text: err.response.data.errors,
            timer: 3000
          });
        });
    }
  }
};
</script>

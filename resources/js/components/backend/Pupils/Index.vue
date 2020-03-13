<template>
  <div>
    <b-container class="full-height mt-2">
      <b-card class="shadow-sm">
        <b-card-body>
          <b-row>
            <b-col lg="6">
              <h2 class="text-primary font-weight-bold">
                Pupils
              </h2>
            </b-col>

            <b-col lg="6">
              <div class="d-flex justify-content-end align-baseline">
                <b-button
                  variant="primary"
                  size="sm"
                  v-b-modal.add-modal
                  @click="resetForm"
                  v-if="userInfo === 'admin'"
                >
                  <b-icon icon="pencil"></b-icon>
                  <!-- <span class="fa fa-fw fa-plus-circle"></span> -->
                  Add Pupil
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
                  @change="getPupils"
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
                  @input="getPupils"
                  placeholder="Search"
                ></b-form-input>
              </b-form>
            </b-col>
          </b-row>

          <b-table
            borderless
            striped
            hover
            id="pupils-table"
            :items="pupils"
            :fields="pupil_fields"
            responsive="md"
          >
            <template v-slot:cell(id)="data">{{ data.item.id }}</template>

            <template v-slot:cell(first_name)="data">{{
              data.item.first_name
            }}</template>

            <template v-slot:cell(last_name)="data">{{
              data.item.last_name
            }}</template>

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
              @change="getPupils"
              aria-controls="pupils-table"
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
      title="Add Pupil"
      ok-title="Submit"
      ok-variant="success"
      ok-only
      @ok="submitAdd"
      @hidden="resetForm"
      scrollable
    >
      <b-form>
        <b-form-group
          label="Profile picture"
          label-class="text-sm"
          v-if="!image_url"
        >
          <b-form-file
            ref="image_input"
            v-model="$v.form.prof_pic.$model"
            :state="validateState('prof_pic')"
            aria-describedby="input-image-feedback"
            accept="image/*"
            @change="handleImage"
          ></b-form-file>

          <b-form-invalid-feedback id="input-image-feedback"
            >This field is required and must be an
            image.</b-form-invalid-feedback
          >
        </b-form-group>

        <b-container class="text-center py-0 my-0" v-if="image_url" fluid>
          <b-img class="prof-pic-preview" :src="image_url" fluid></b-img>

          <br />

          <b-container class="text-center mb-2" fluid>
            <b-button
              class="text-sm p-2 mx-2"
              variant="light"
              @click="changeImage"
              >Change</b-button
            >

            <b-button
              class="text-sm p-2 mx-2"
              variant="light"
              @click="clearImage"
              >Remove</b-button
            >
          </b-container>
        </b-container>

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

              <b-form-invalid-feedback id="input-fname-feedback"
                >This field is required and must be atleast 3
                characters.</b-form-invalid-feedback
              >
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

              <b-form-invalid-feedback id="input-fname-feedback"
                >This field is required and must be atleast 3
                characters.</b-form-invalid-feedback
              >
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

              <b-form-invalid-feedback id="input-fname-feedback"
                >This field is required and must be atleast 3
                characters.</b-form-invalid-feedback
              >
            </b-form-group>
          </b-col>
        </b-form-row>

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
              >{{ school.name }}</b-form-select-option
            >
          </b-form-select>

          <b-form-invalid-feedback id="input-school-feedback"
            >This field is required.</b-form-invalid-feedback
          >
        </b-form-group>

        <b-form-group label="Birth date" label-class="text-sm">
          <b-form-input
            type="date"
            v-model="$v.form.birth_date.$model"
            :state="validateState('birth_date')"
            aria-describedby="input-bday-feedback"
          ></b-form-input>

          <b-form-invalid-feedback id="input-bday-feedback"
            >This field is required.</b-form-invalid-feedback
          >
          <span class="text-danger text-small" v-if="bdayErr"
            >Date must not be beyond the current date.</span
          >
        </b-form-group>
      </b-form>
    </b-modal>

    <!-- EDIT -->
    <b-modal
      id="edit-modal"
      size="lg"
      title="Update Pupil Information"
      ok-title="Submit"
      ok-variant="success"
      ok-only
      @ok="submitUpdate"
      @hidden="resetForm"
      button-size="sm"
    >
      <b-form>
        <!-- <b-container 
				class="text-center"
				v-if="form.prof_pic"
				fluid >
					<b-img 
					class="prof-pic-preview"
					:src="this.assetURL + '/' + form.prof_pic"
					fluid></b-img>
				</b-container>


				<b-form-group
				label="Profile picture"
				label-class="text-sm">
					<b-form-file
					v-model="$v.form.prof_pic.$model"
					:state="validateState('prof_pic')"
					aria-describedby="input-image-feedback"
					accept="image/*"
					@change="handleImage">
					</b-form-file>

					<b-form-invalid-feedback
					id="input-image-feedback">
						This field is required and must be an image.
					</b-form-invalid-feedback>
        </b-form-group>-->

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

              <b-form-invalid-feedback id="input-fname-feedback"
                >This field is required and must be atleast 3
                characters.</b-form-invalid-feedback
              >
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

              <b-form-invalid-feedback id="input-fname-feedback"
                >This field is required and must be atleast 3
                characters.</b-form-invalid-feedback
              >
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

              <b-form-invalid-feedback id="input-fname-feedback"
                >This field is required and must be atleast 3
                characters.</b-form-invalid-feedback
              >
            </b-form-group>
          </b-col>
        </b-form-row>

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
              >{{ school.name }}</b-form-select-option
            >
          </b-form-select>

          <b-form-invalid-feedback id="input-school-feedback"
            >This field is required.</b-form-invalid-feedback
          >
        </b-form-group>

        <b-form-group label="Birth date" label-class="text-sm">
          <b-form-input
            type="date"
            v-model="$v.form.birth_date.$model"
            :state="validateState('birth_date')"
            aria-describedby="input-bday-feedback"
            @change="validateBirthDate"
          ></b-form-input>

          <b-form-invalid-feedback id="input-bday-feedback"
            >This field is required.</b-form-invalid-feedback
          >
          <span class="text-danger text-small" v-if="bdayErr"
            >Date must not be beyond the current date.</span
          >
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
      <p class="text-center text-muted mb-1">
        Are you sure you want to remove this pupil?
      </p>
    </b-modal>
  </div>
</template>
<script>
import { required, minLength, maxLength } from "vuelidate/lib/validators";
export default {
  name: "PupilsIndex",
  props: ["host"],
  data() {
    return {
      search: "",
      limit: 10,
      pupils: null,
      pupil_fields: [
        {
          key: "id",
          label: "ID",
          sortable: true
        },
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
          key: "index",
          label: "Action"
        }
      ],
      schools: null,
      schools_list: [],
      current_page: 1,
      response: {},
      image_url: null,

      // ADD
      form: {
        prof_pic: null,
        school_id: null,
        first_name: null,
        last_name: null,
        middle_name: null,
        birth_date: null
      },

      // EDIT
      edit_id: null,
      edit_index: null,

      // DELETE
      delete_id: null,
      delete_index: null,

      //FORM BUTTON LOADING
      submitLoading: false,

      // ERROR
      bdayErr: false,

      userInfo: ""
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
        maxLength: maxLength(40)
      },
      last_name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(40)
      },
      middle_name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(40)
      },
      prof_pic: {
        required
      },
      birth_date: {
        required
      }
    }
  },
  computed: {},
  mounted() {
    this.getPupils();
    this.getSchools();
    this.checkUserRoles(this.$user);
  },
  methods: {
    checkUserRoles: function(userInfo) {
      const user = JSON.parse(userInfo);
      this.userInfo = user.roles.length != 0 ? user.roles[0].name : null;
    },

    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    validateBirthDate: function() {
      let bday = this.form.birth_date;
      let currentDate = new Date().toJSON().slice(0, 10);
      if (bday === currentDate || bday > currentDate) {
        this.bdayErr = true;
      } else {
        this.bdayErr = false;
      }
    },

    getPupils: function(page) {
      let pupilsAPI = `${this.host}/pupils?search=${this.search}&limit=${this.limit}&page=${page}`;
      axios
        .get(pupilsAPI)
        .then(response => {
          this.pupils = response.data.data;
          this.response = response.data;
        })
        .catch(err => console.log(err));
    },

    getSchools: function(page) {
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

    handleImage: function(event) {
      this.form.prof_pic = event.target.files[0];
      this.image_url = URL.createObjectURL(this.form.prof_pic);
    },

    clearImage: function() {
      this.form.prof_pic = null;
      this.image_url = null;
    },

    changeImage: function() {
      this.clearImage();
      // TODO - click input file to show choose image dialog
      // this.$refs.image_input.click()
    },

    submitAdd: function(event) {
      event.preventDefault();
      this.$v.form.$touch();
      this.validateBirthDate();
      if (this.$v.form.$anyError || this.bdayErr) {
        return;
        console.log(this.bdayErr);
      } else {
        this.add();
      }
    },

    add: function() {
      let pupilsAPI = `${this.host}/pupils`;

      let data = new FormData();
      data.append("school_id", this.form.school_id);
      data.append("prof_pic", this.form.prof_pic);
      data.append("first_name", this.form.first_name);
      data.append("last_name", this.form.last_name);
      data.append("middle_name", this.form.middle_name);
      data.append("birth_date", this.form.birth_date);

      const config = {
        headers: {
          "Content-Type": "multipart/form-data"
        }
      };

      axios
        .post(pupilsAPI, data, config)
        .then(response => {
          if (response.data.status == 201) {
            this.getPupils();
            this.image_url = "";
            this.resetForm();
            this.$bvModal.hide("add-modal");

            swal.fire({
              icon: "success",
              title: "Added",
              text: "Pupil successfully added",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to add pupil",
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

    edit: function(index) {
      this.$v.$reset();

      this.edit_id = this.pupils[index].id;
      this.edit_index = index;
      this.form.school_id = this.pupils[index].school_id;
      this.form.prof_pic = this.pupils[index].prof_pic;
      this.form.first_name = this.pupils[index].first_name;
      this.form.last_name = this.pupils[index].last_name;
      this.form.middle_name = this.pupils[index].middle_name;
      this.form.birth_date = this.pupils[index].birth_date;
    },

    submitUpdate: function() {
      event.preventDefault();
      this.$v.form.$touch();
      this.validateBirthDate();
      if (this.$v.form.$anyError || this.bdayErr) {
        return;
      } else {
        this.update();
      }
    },

    update: function() {
      let pupilsAPI = `${this.host}/pupil/${this.edit_id}`;

      //TODO - #QA - Update data empty in controller

      // let data = new FormData();
      // data.append('school_id', this.form.school_id)
      // data.append('prof_pic', this.form.prof_pic)
      // data.append('first_name', this.form.first_name)
      // data.append('last_name', this.form.last_name)
      // data.append('middle_name', this.form.middle_name)
      // data.append('birth_date', this.form.birth_date)

      let data = {
        school_id: this.form.school_id,
        first_name: this.form.first_name,
        last_name: this.form.last_name,
        middle_name: this.form.middle_name,
        birth_date: this.form.birth_date,
        prof_pic: this.form.prof_pic
      };

      // const config = {
      // 	headers: {
      // 		'Content-Type': 'multipart/form-data'
      // 	}
      // }

      axios
        .put(pupilsAPI, data)
        .then(response => {
          if (response.data.status == 201) {
            this.getPupils();
            this.image_url = "";
            this.resetForm();
            this.$bvModal.hide("edit-modal");

            swal.fire({
              icon: "success",
              title: "Updated",
              text: "Pupil information successfully added",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to update pupil information",
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
      this.delete_id = this.pupils[index].id;
      this.delete_index = index;
    },

    destroy: function(index) {
      console.log(this.delete_index);
      const pupilsAPI = `${this.host}/pupil/${this.delete_id}`;
      axios
        .delete(pupilsAPI)
        .then(response => {
          if (response.data.status == 201) {
            this.pupils.splice(this.delete_index, 1);

            swal.fire({
              icon: "success",
              title: "Deleted",
              text: "Pupil successfully deleted",
              timer: 3000
            });
          } else {
            swal.fire({
              icon: "error",
              title: "Error",
              text: "Failed to delete pupil",
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

    resetForm: function() {
      this.$v.$reset();
      this.form.first_name = null;
      this.form.last_name = null;
      this.form.middle_name = null;
      this.form.birth_date = null;
      this.form.prof_pic = null;
      this.form.school_id = null;
      this.image_url = null;
    }
  }
};
</script>

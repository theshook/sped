<template>
    <div>
        <b-container class="mt-2">
            <b-card class="shadow-sm">
                <b-card-body>
                    <b-row>
                        <b-col lg="6">
                            <h2 class="text-primary font-weight-bold">
                                Schools
                            </h2>
                        </b-col>

                        <b-col lg="6">
                            <div
                                class="d-flex justify-content-end align-baseline"
                            >
                                <b-button
                                    variant="primary"
                                    size="sm"
                                    v-b-modal.add-modal
                                >
                                    <b-icon icon="pencil"></b-icon>
                                    <!-- <span class="fa fa-fw fa-plus-circle"></span> -->
                                    Add School
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
							</b-form-select> -->

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

                        <b-col
                            lg="6"
                            class="d-flex justify-content-end align-content-end"
                        >
                            <b-form class="col-sm-12 col-md-6 px-0">
                                <b-form-input
                                    size="sm"
                                    v-model="search"
                                    @input="getSchools"
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
                        <template v-slot:cell(name)="data">
                            {{ data.item.name }}
                        </template>

                        <template v-slot:cell(province_id)="data">
                            {{ data.item.province_id }}
                        </template>

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
                            @change="getSchools"
                            aria-controls="schools-table"
                        ></b-pagination>
                    </b-container>
                </b-card-body>
            </b-card>
        </b-container>

        <!-- MODALS -->
        <!-- ADD -->
        <b-modal
            id="add-modal"
            title="Add School"
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
					aria-describedby="input-name-feedback"></b-form-input>

					<b-form-invalid-feedback
					id="invalid-name-feedback">
						This form is required and must be atleast 3 characters.
					</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Province" label-class="text-sm">
                    <b-form-select 
					v-model="$v.form.province_id.$model"
					:state="validateState('province_id')"
					aria-describedby="input-province-feedback">
                        <b-form-select-option
                            v-for="province in provinces"
                            :key="province.id"
                            :value="province.id"
                            >{{ province.name }}</b-form-select-option
                        >
                    </b-form-select>

					<b-form-invalid-feedback
					id="input-province-feedback">
						This field is required
					</b-form-invalid-feedback>
                </b-form-group>
            </b-form>
        </b-modal>

        <!-- EDIT/UPDATE -->
        <b-modal
            id="edit-modal"
            title="Update School Information"
            ok-title="Submit"
            ok-variant="success"
            ok-only
            @ok="submitUpdate"
			@hidden="resetForm"
            button-size="sm"
        >
            <b-form>
                <b-form-group label="Name" label-class="text-sm">
                    <b-form-input 
					v-model="$v.form.name.$model"
					:state="validateState('name')"
					aria-describedby="input-name-feedback"></b-form-input>

					<b-form-invalid-feedback
					id="invalid-name-feedback">
						This form is required and must be atleast 3 characters.
					</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Province" label-class="text-sm">
                    <b-form-select 
					v-model="$v.form.province_id.$model"
					:state="validateState('province_id')"
					aria-describedby="input-province-feedback">
                        <b-form-select-option
                            v-for="province in provinces"
                            :key="province.id"
                            :value="province.id"
                            >{{ province.name }}</b-form-select-option
                        >
                    </b-form-select>

					<b-form-invalid-feedback
					id="input-province-feedback">
						This field is required
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
            <p class="text-center text-muted mb-1">
                Are you sure you want to remove this school?
            </p>
        </b-modal>
    </div>
</template>

<script>
import { required, minLength, maxLength } from 'vuelidate/lib/validators'
export default {
    name: "SchoolsIndex",
    props: ["host"],
    data() {
        return {
            schools: null,
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

            provinces: null,
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
			const { $dirty, $error } = this.$v.form[name]
			return $dirty ? !$error : null
		},

        getSchools: function(page) {
            const schoolsAPI = `${this.host}/schools?search=${this.search}&limit=${this.limit}&page=${page}`;
            axios
                .get(schoolsAPI)
                .then(response => {
                    this.schools = response.data.data;
					this.response = response.data;
                })
                .catch(err => console.log(err));
        },

        getProvinces: function() {
            const provincesAPI = `${this.host}/provinces/raw`;
            axios
                .get(provincesAPI)
                .then(response => {
                    this.provinces = response.data;
                })
                .catch(err => console.log(err));
        },

        formatDate: function(index) {
            const schools = this.schools;
            //return schools[index].created_at.split('-')
            const month = schools[index].created_at.split("-")[1];
            const dateDay = schools[index].created_at
                .split("-")[2]
                .split(" ")[0];
            const dateYear = schools[index].created_at.split("-")[0];
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
			event.preventDefault()
			this.$v.form.$touch()
			if(this.$v.form.$anyError) {
				return
			} else {
				this.add()
			}
		},

        add: function() {
            const schoolsAPI = `${this.host}/schools`;

            const data = {
                province_id: this.form.province_id,
                name: this.form.name
            };

            axios
                .post(schoolsAPI, data)
                .then(response => {
                    if (response.status == 201) {
						this.getSchools;
						this.$bvModal.hide('add-modal')
						this.resetForm()

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
                        text: err.response.data.errors.name[0],
                        timer: 3000
                    })
                );
        },

        edit: function(index) {
            this.edit_id = this.schools[index].id;
            this.edit_index = index;
            this.form.name = this.schools[index].name;
            this.form.province_id = this.schools[index].province_id;
        },

		submitUpdate: function(event) {
			event.preventDefault()
			this.$v.form.$touch()
			if(this.$v.form.$anyError) {
				return
			} else {
				this.update()
			}
		},

        update: function() {
            const schoolsAPI = `${this.host}/school/${this.edit_id}`;
            const data = {
                province_id: this.form.province_id,
                name: this.form.name
            };

            axios
                .put(schoolsAPI, data)
                .then(response => {
                    if (response.data.status == 201) {
						this.getSchools()
						this.$bvModal.hide('edit-modal')
						this.resetForm()

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
            this.delete_id = this.schools[index].id;
            this.delete_index = index;
        },

        destroy: function() {
            const schoolsAPI = `${this.host}/school/${this.delete_id}`;
            axios
			.delete(schoolsAPI)
			.then(response => {
				if (response.data.status == 201) {
					this.schools.splice(this.delete_index, 1);

					swal.fire({
						icon: "success",
						title: "Deleted",
						text: "School information successfully deleted",
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
			.catch(err => console.log(err.response.data));
		},
		
		resetForm: function() {
			this.$v.$reset()
			this.form.province_id = null
			this.form.name = null
		}
    }
};
</script>

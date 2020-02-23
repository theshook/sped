<template>
    <div>
        <b-container class="mt-2">
            <b-card class="shadow-sm">
                <b-card-body>
                    <b-row>
                        <b-col lg="6">
                            <h2 class="text-primary font-weight-bold">
                                Checklist Categories
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
                                    Add Checklist Categories
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
                                    @change="getChecklistCategories"
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
                                    @input="getChecklistCategories"
                                    placeholder="Search"
                                ></b-form-input>
                            </b-form>
                        </b-col>
                    </b-row>

                    <b-table
                        borderless
                        striped
                        hover
                        id="province-table"
                        :items="categories"
                        :fields="categories_fields"
                        responsive="md"
                    >
                        <template v-slot:cell(name)="data">
                            {{ data.item.name }}
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
                            @change="getChecklistCategories"
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
            title="Add Checklist Category"
            ok-title="Submit"
            ok-variant="success"
            ok-only
            @ok="add"
            button-size="sm"
        >
            <b-form>
                <b-form-group label="Name" label-class="text-sm">
                    <b-form-input v-model="name"></b-form-input>
                </b-form-group>
            </b-form>
        </b-modal>

        <!-- EDIT/UPDATE -->
        <b-modal
            id="edit-modal"
            title="Update Checklist Category Information"
            ok-title="Save Changes"
            ok-variant="success"
            ok-only
            @ok="update"
            button-size="sm"
        >
            <b-form>
                <input type="hidden" v-model="edit_id" />
                <b-form-group label="Name" label-class="text-sm">
                    <b-form-input v-model="edit_name"></b-form-input>
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
                Are you sure you want to remove this province?
            </p>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: "ChecklistCategoriedIndex",
    props: ["host"],
    data() {
        return {
            search: "",
            limit: 10,
            current_page: 1,
            categories: null,
            categories_fields: [
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
            categories_list: [],
            response: {},

            // ADD
            name: null,

            // EDIT
            edit_id: null,
            edit_index: null,
            edit_name: null,

            // DELETE
            delete_id: null
        };
    },
    computed: {},
    mounted() {
        this.getChecklistCategories();
    },
    methods: {
        getChecklistCategories: function(page) {
            const categoriesAPI = `${this.host}/checklists/categories?search=${this.search}&limit=${this.limit}&page=${page}`;
            axios
                .get(categoriesAPI)
                .then(response => {
                    this.categories = response.data.data;
                    this.response = response.data;
                })
                .catch(err => console.log(err));
        },

        formatDate: function(index) {
            const categories = this.categories;
            //return provinces[index].created_at.split('-')
            const month = categories[index].created_at.split("-")[1];
            const dateDay = categories[index].created_at
                .split("-")[2]
                .split(" ")[0];
            const dateYear = categories[index].created_at.split("-")[0];
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

        add: function() {
            const checklistCategoriesAPI = `${this.host}/checklists/categories`;
            const data = {
                name: this.name
            };

            axios
                .post(checklistCategoriesAPI, data)
                .then(response => {
                    if (response.data.status == 201) {
                        this.getChecklistCategories();
                        this.name = null;

                        swal.fire({
                            icon: "success",
                            title: "Added",
                            text: "Category information successfully added",
                            timer: 3000
                        });
                    } else {
                        swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Failed to add category information",
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
            this.edit_id = this.categories[index].id;
            this.edit_index = index;
            this.edit_name = this.categories[index].name;
        },

        update: function() {
            const categoriesAPI = `${this.host}/checklist/categories/${this.edit_id}`;
            const data = {
                name: this.edit_name
            };
            axios
                .put(categoriesAPI, data)
                .then(response => {
                    if (response.data.status == 201) {
                        this.categories[this.edit_index].name = this.edit_name;

                        swal.fire({
                            icon: "success",
                            title: "Updated",
                            text: "Category information successfully updated",
                            timer: 3000
                        });
                    } else {
                        swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Failed to update category information",
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
            this.delete_id = this.categories[index].id;
        },

        destroy: function(index) {
            const categoriesAPI = `${this.host}/checklist/categories/${this.delete_id}`;
            axios
                .delete(categoriesAPI)
                .then(response => {
                    if (response.data.status == 201) {
                        this.categories.splice(index, 1);

                        swal.fire({
                            icon: "success",
                            title: "Deleted",
                            text: "Category information successfully deleted",
                            timer: 3000
                        });
                    } else {
                        swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Failed to delete category information",
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
        }
    }
};
</script>

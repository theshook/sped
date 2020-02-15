<template>
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <div
                    class="row d-flex justify-content-between align-items-center"
                >
                    <h5 class="m-0 font-weight-bold text-primary">Provinces</h5>
                    <button
                        class="btn btn-primary"
                        data-toggle="modal"
                        data-target="#provinceModal"
                    >
                        New
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div
                    class="row d-flex justify-content-between align-items-center pb-3"
                >
                    <input
                        type="text"
                        class="form-control w-25"
                        placeholder="Search here..."
                    />
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="prov in rowProvinces" :key="prov.id">
                            <th scope="row">{{ prov.name }}</th>
                            <td>
                                <div class="row d-flex justify-content-end">
                                    <button
                                        class="btn btn-sm btn-secondary mx-1"
                                    >
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger mx-1">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <nav aria-label="Page navigation example">
                    <ul class="pagination pagination-sm justify-content-end">
                        <li class="page-item">
                            <a class="page-link" href="#">Previous</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Modal -->
        <div
            class="modal fade"
            id="provinceModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="provinceModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="provinceModalLabel">
                            Add New Province
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input
                            type="text"
                            class="form-control"
                            name="name"
                            placeholder="Province name..."
                            v-model="name"
                        />
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Close
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="submit"
                        >
                            Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["provinces"],
    data() {
        return {
            name: "",
            rowProvinces: this.provinces.data
        };
    },
    methods: {
        submit() {
            axios
                .post(`/configure/provinces`, { name: this.name })
                .then(res => {
                    swal.fire("Success!", res.data.message, "success").then(
                        result => {
                            if (result) window.location.reload();
                        }
                    );
                })
                .catch(err => {
                    swal.fire("Sorry!", "Something went wrong!", "error").then(
                        result => {
                            if (result) window.location.reload();
                        }
                    );
                });
        },
        searchProvince() {}
    },
    mounted() {}
};
</script>

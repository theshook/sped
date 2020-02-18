<template>
    <div>
		<section class="mt-2">
			<div class="card shadow-sm">
				<div class="card-header clearfix py-3">
					<h4 class="text-primary font-weight-bold float-left">Schools</h4>

					<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#add-modal">
						<small>
							<span class="fa fa-fw fa-plus-circle"></span>
							Add School
						</small>
					</button>
				</div>

				<div class="card-body">
					<table class="table table-responsive-md">
						<thead>
							<th>Name</th>
							<th>Address</th>
							<th>Date added</th>
							<th>
								<span class="fa fa-ellipsis-h"></span>
							</th>
						</thead>

						<tbody v-for="(school, i) in schools" :key="i">
							<tr>
								<td>{{ school.name }}</td>
								<td>{{ formatDate(i) }}</td>
								<td>
									<div class="btn-group">
										<button class="btn btn-warning btn-sm text-white" data-toggle="modal" data-target="#edit-modal" @click="edit(i)">
											<span class="fa fa-edit"></span>
										</button>

										<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" @click="remove(i)">
											<span class="fa fa-trash-alt"></span>
										</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</section>


		<!-- MODALS -->
		<!-- ADD -->
		<div class="modal" id="add-modal" tabindex="1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5>Add</h5>

						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>

					<div class="modal-body">
						<form>
							<div class="form-group">
								<small>Name</small>
								<input type="text" v-model="name" class="form-control form-control-lg text-sm" placeholder="Enter school name">
							</div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-success btn-sm shadow-sm" @click="add()">Save</button>
						<button type="button" class="btn btn-light btn-sm shadow-sm">Close</button>
					</div>
				</div>
			</div>
		</div>

		<!-- EDIT/UPDATE -->
		<div class="modal" id="edit-modal" tabindex="1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5>Edit</h5>

						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>

					<div class="modal-body">
						<form>
							<input type="hidden" v-model="edit_id">
							<div class="form-group">
								<small>Name</small>
								<input type="text" v-model="edit_name" class="form-control form-control-lg text-sm" placeholder="Enter school name">
							</div>
						</form>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-success btn-sm shadow-sm" @click="update()">Save Changes</button>
						<button type="button" class="btn btn-light btn-sm shadow-sm" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<!-- DELETE CONFIRM -->
		<div class="modal" id="delete-modal" tabindex="1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5>Delete</h5>

						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>

					<div class="modal-body text-center">
						<p>Are you sure you want to remove this school?</p>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-sm shadow-sm" @click="destroy()">Delete</button>
						<button type="button" class="btn btn-light btn-sm shadow-sm" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
    </div>
</template>

<script>
import $ from 'jquery'
export default {
	props: ['host'],
    data() {
        return {
			schools: null,

			// ADD
			name: null,

			// EDIT
			edit_id: null,
			edit_name: null,

			// DELETE
			delete_id: null
        }
	},
	computed: {},
    mounted() {
		this.getSchools()
	},
    methods: {
		getSchools: function() {
			const schoolsAPI = `${this.host}/schools`
			axios.get(schoolsAPI)
			.then(response => {
				this.schools = response.data.data
			})
			.catch(err => console.log(err))
		},

		formatDate: function(index) {
			const schools = this.schools
			//return schools[index].created_at.split('-')
			const month = schools[index].created_at.split('-')[1]
			const dateDay = schools[index].created_at.split('-')[2].split(' ')[0]
			const dateYear = schools[index].created_at.split('-')[0]
			let dateMonth = null
			switch(month) {
				case '01':
					dateMonth = 'January'
					break;
				case '02':
					dateMonth = 'February'
					break;
				case '03':
					dateMonth = 'March'
					break;
				case '04':
					dateMonth = 'April'
					break;
				case '05':
					dateMonth = 'May'
					break;
				case '06':
					dateMonth = 'June'
					break;
				case '07':
					dateMonth = 'July'
					break;
				case '08':
					dateMonth = 'August'
					break;
				case '09':
					dateMonth = 'September'
					break;
				case '10':
					dateMonth = 'October'
					break;
				case '11':
					dateMonth = 'November'
					break;
				case '12':
					dateMonth = 'December'
					break;
			}

			return `${dateMonth} ${dateDay}, ${dateYear}`
		},

		add: function() {
			const schoolsAPI = `${this.host}/schools`
			const data = {
				province_id: 1,
				name: this.name,
				address: 'Tinio, Brgy. Bangkal, Makati City'
			}
			const config = {
				headers: {
					'Content-Type' : 'application/json'
				}
			}

			// TODO - add default headers for axios (Content-Type: application/json)
			axios.post(schoolsAPI, data, config)
			.then(response => {
				if(response.status == 201) {
					this.getSchools()
					this.name = null
					
					$('#add-modal').modal('hide')
					$('.modal-backdrop').removeClass('show').hide()

					swal.fire({
						icon: 'success',
						title: 'Added',
						text: 'School information successfully added',
						timer: 3000
					})
				} else {
					swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Failed to add school information',
						timer: 3000
					})
				}
			})
			.catch(err => console.log(err))
		},

		edit: function(index) {
			this.edit_id = this.schools[index].id
			this.edit_name = this.schools[index].name
		}, 

		update: function() {
			const schoolsAPI = `${this.host}/school/${this.edit_id}`
			const data = {
				province_id: 5,
				name: this.edit_name,
				address: 'test address'
			}
			axios.put(schoolsAPI, data)
			.then(response => {
				if(response.status == 201) {
					this.getSchools()
					
					$('#edit-modal').modal('hide')
					$('.modal-backdrop').removeClass('show').hide()

					swal.fire({
						icon: 'success',
						title: 'Updated',
						text: 'Province information successfully updated',
						timer: 3000
					})
				} else {
					swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Failed to update province information',
						timer: 3000
					})
				}
			})
			.catch(err => console.log(err))
		},

		remove: function(index) {
			this.delete_id = this.schools[index].id
		},

		destroy: function(index) {
			const schoolsAPI = `${this.host}/school/${this.delete_id}`
			axios.delete(schoolsAPI)
			.then(response => {
				if(response.status == 201) {
					this.getSchools()
					
					$('#delete-modal').modal('hide')
					$('.modal-backdrop').removeClass('show').hide()

					swal.fire({
						icon: 'success',
						title: 'Deleted',
						text: 'School information successfully deleted',
						timer: 3000
					})
				} else {
					swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Failed to delete school information',
						timer: 3000
					})
				}
			})
			.catch(err => console.log(err))
		},
	},
};
</script>

<template>
    <div>
		<b-container class="mt-2">
			<b-card class="shadow-sm">
				<b-card-body>
					<b-card-header class="px-0">
						<b-container class="clearfix px-2" fluid>
							<h4 class="text-primary font-weight-bold float-left">Schools</h4>

							<b-button 
							variant="primary"
							size="sm"
							v-b-modal.add-modal
							class="float-right">
								<b-icon icon="pencil"></b-icon>
								<!-- <span class="fa fa-fw fa-plus-circle"></span> -->
								Add School
							</b-button>
						</b-container>
					</b-card-header>

					<b-table borderless striped hover small id="schools-table" :items="schools" :fields="schools_fields" :current-page="currentPage" :per-page="perPage" :total-rows="totalRows" responsive="md">
						<template v-slot:cell(name)="data">
							{{data.item.name}}
						</template>

						<template v-slot:cell(province_id)="data">
							{{data.item.province_id}}
						</template>

						<template v-slot:cell(index)="data">
							<b-btn-group>
								<b-button v-b-modal.edit-modal size="sm" variant="warning" class="text-white" @click="edit(data.index)">
									<b-icon icon="pencil"></b-icon>
								</b-button>

								<b-button v-b-modal.delete-modal size="sm" variant="danger" @click="remove(data.index)">
									<b-icon icon="trash"></b-icon>
								</b-button>
							</b-btn-group>
						</template>
					</b-table>

					<b-container class="clearfix px-0" fluid>

						<b-pagination
						class="float-right"
						v-model="currentPage"
						:per-page="perPage"
						:total-rows="totalRows"
						aria-controls="schools-table"></b-pagination>
					</b-container>
				</b-card-body>
			</b-card>
		</b-container>


		<!-- MODALS -->
		<!-- ADD -->
		<b-modal id="add-modal" title="Add School" ok-title="Submit" ok-variant="success" ok-only @ok="add" button-size="sm">
			<b-form>
				<b-form-group
				label="Name"
				label-class="text-sm">
					<b-form-input v-model="name"></b-form-input>
				</b-form-group>

				<b-form-group
				label="Province"
				label-class="text-sm">
					<b-form-select v-model="province_id">
						<b-form-select-option v-for="province in provinces" :key="province.id" :value="province.id">{{province.name}}</b-form-select-option>
					</b-form-select>
				</b-form-group>
			</b-form>
		</b-modal>

		<!-- EDIT/UPDATE -->
		<b-modal id="edit-modal" title="Update School Information" ok-title="Submit" ok-variant="success" ok-only @ok="update" button-size="sm">
			<b-form>
				<b-form-group
				label="Name"
				label-class="text-sm">
					<b-form-input v-model="edit_name"></b-form-input>
				</b-form-group>

				<b-form-group
				label="Province"
				label-class="text-sm">
					<b-form-select v-model="edit_province_id">
						<b-form-select-option v-for="province in provinces" :key="province.id" :value="province.id">{{province.name}}</b-form-select-option>
					</b-form-select>
				</b-form-group>
			</b-form>
		</b-modal>

		<!-- DELETE CONFIRM -->
		<b-modal id="delete-modal" title="Confirm" ok-title="Continue" ok-variant="danger" @ok="destroy" ok-only button-size="sm">
			<p class="text-center text-muted mb-1">Are you sure you want to remove this school?</p>
		</b-modal>
    </div>
</template>

<script>
export default {
	name: 'SchoolsIndex',
	props: ['host'],
    data() {
        return {
			schools: null,
			schools_fields: [
				{
					key: 'name',
					label: 'Name',
					sortable: true
				},
				{
					key: 'province_id',
					label: 'Province',
					sortable: true
				},
				{
					key: 'index',
					label: 'Action'
				}
			],

			provinces: null,
			provinces_list: [],
			currentPage: 1,
			perPage: 10,
			totalRows: null,

			// ADD
			province: null,
			province_id: null,
			province_not_found: false,
			name: null,

			// EDIT
			edit_id: null,
			edit_index: null,
			edit_name: null,
			edit_province_id: null,

			// DELETE
			delete_id: null,
			delete_index: null
        }
	},
	computed: {},
    mounted() {
		this.getSchools()
		this.getProvinces()
	},
    methods: {
		getSchools: function() {
			const schoolsAPI = `${this.host}/schools`
			axios.get(schoolsAPI)
			.then(response => {
				this.schools = response.data
			})
			.catch(err => console.log(err))
		},

		getProvinces: function() {
			const provincesAPI = `${this.host}/provinces`
			axios.get(provincesAPI)
			.then(response => {
				this.provinces = response.data.data
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
				province_id: this.province_id,
				name: this.name
			}
			
			axios.post(schoolsAPI, data)
			.then(response => {
				if(response.status == 201) {
					this.schools.push({
						name: this.name,
						province_id: this.province_id
					})
					this.name = null
					this.province_id = null

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
			this.edit_index = index
			this.edit_name = this.schools[index].name
			this.edit_province_id = this.schools[index].province_id
		}, 

		update: function() {
			const schoolsAPI = `${this.host}/school/${this.edit_id}`
			const data = {
				province_id: this.edit_province_id,
				name: this.edit_name
			}
			
			axios.put(schoolsAPI, data)
			.then(response => {
				if(response.data.status == 201) {
					this.schools[this.edit_index].name = this.edit_name
					this.schools[this.edit_index].province_id = this.edit_province_id
					this.edit_name = null
					this.edit_province_id = null

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
			this.delete_index = index
		},

		destroy: function() {
			const schoolsAPI = `${this.host}/school/${this.delete_id}`
			axios.delete(schoolsAPI)
			.then(response => {
				if(response.data.status == 201) {
					this.schools.splice(this.delete_index, 1)

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
		}
	},
};
</script>

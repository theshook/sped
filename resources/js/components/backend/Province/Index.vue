<template>
    <div>
		<b-container class="mt-2">
			<b-card class="shadow-sm">
				<b-card-body>
					<b-card-header class="px-0">
						<b-container class="clearfix px-2" fluid>
							<h4 class="text-primary font-weight-bold float-left">Provinces</h4>

							<b-button 
							variant="primary"
							size="sm"
							v-b-modal.add-modal
							class="float-right">
								<b-icon icon="pencil"></b-icon>
								<!-- <span class="fa fa-fw fa-plus-circle"></span> -->
								Add Province
							</b-button>
						</b-container>
					</b-card-header>

					<b-table borderless striped hover :items="provinces" :fields="provinces_fields" responsive="md">
						<template v-slot:cell(name)="data">
							{{data.item.name}}
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
				</b-card-body>
			</b-card>
		</b-container>


		<!-- MODALS -->
		<!-- ADD -->
		<b-modal id="add-modal" title="Add Province" ok-title="Submit" ok-variant="success" ok-only @ok="add" button-size="sm">
			<b-form>
				<b-form-group
				label="Name"
				label-class="text-sm">
					<b-form-input v-model="name"></b-form-input>
				</b-form-group>
			</b-form>
		</b-modal>

		<!-- EDIT/UPDATE -->
		<b-modal id="edit-modal" title="Update Province Information" ok-title="Save Changes" ok-variant="success" ok-only @ok="update" button-size="sm">
			<b-form>
				<input type="hidden" v-model="edit_id">
				<b-form-group
				label="Name"
				label-class="text-sm">
					<b-form-input v-model="edit_name"></b-form-input>
				</b-form-group>
			</b-form>
		</b-modal>

		<!-- DELETE CONFIRM -->
		<b-modal id="delete-modal" title="Confirm" ok-title="Continue" ok-variant="danger" @ok="destroy" ok-only button-size="sm">
			<p class="text-center text-muted mb-1">Are you sure you want to remove this province?</p>
		</b-modal>
    </div>
</template>

<script>
export default {
	name: 'ProvincesIndex',
	props: ['host'],
    data() {
        return {
			provinces: null,
			provinces_fields: [
				// 'index',
				{
					key: 'name',
					label: 'Name',
					sortable: true
				},
				{
					key: 'index',
					label: 'Action'
				}
			],
			province_list: [],

			// ADD
			name: null,

			// EDIT
			edit_id: null,
			edit_index: null,
			edit_name: null,

			// DELETE
			delete_id: null,
        }
	},
	computed: {},
    mounted() {
		this.getProvinces()
	},
    methods: {
		getProvinces: function() {
			const provincesAPI = `${this.host}/provinces`
			axios.get(provincesAPI)
			.then(response => {
				this.provinces = response.data.data

				for(let i = 0; i < this.provinces.length; i++) {
					this.province_list.push(this.provinces[i].name)
				}
			})
			.catch(err => console.log(err))
		},

		formatDate: function(index) {
			const provinces = this.provinces
			//return provinces[index].created_at.split('-')
			const month = provinces[index].created_at.split('-')[1]
			const dateDay = provinces[index].created_at.split('-')[2].split(' ')[0]
			const dateYear = provinces[index].created_at.split('-')[0]
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
			const provincesAPI = `${this.host}/provinces`
			const data = {
				name: this.name
			}

			axios.post(provincesAPI, data)
			.then(response => {
				if(response.data.status == 201) {
					this.getProvinces()
					this.name = null

					swal.fire({
						icon: 'success',
						title: 'Added',
						text: 'Province information successfully added',
						timer: 3000
					})
				} else {
					swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Failed to add province information',
						timer: 3000
					})
				}
			})
			.catch(err => console.log(err))
		},

		edit: function(index) {
			this.edit_id = this.provinces[index].id
			this.edit_index = index
			this.edit_name = this.provinces[index].name
		}, 

		update: function() {
			const provincesAPI = `${this.host}/province/${this.edit_id}`
			const data = {
				name: this.edit_name
			}
			axios.put(provincesAPI, data)
			.then(response => {
				if(response.data.status == 201) {
					this.provinces[this.edit_index].name = this.edit_name

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
			this.delete_id = this.provinces[index].id
		},

		destroy: function(index) {
			const provincesAPI = `${this.host}/province/${this.delete_id}`
			axios.delete(provincesAPI)
			.then(response => {
				if(response.data.status == 201) {
					this.provinces.splice(index, 1)

					swal.fire({
						icon: 'success',
						title: 'Deleted',
						text: 'Province information successfully deleted',
						timer: 3000
					})
				} else {
					swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Failed to delete province information',
						timer: 3000
					})
				}
			})
			.catch(err => console.log(err))
		},
	},
};
</script>

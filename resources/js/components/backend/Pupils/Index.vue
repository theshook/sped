<template>
	<div>
		<b-container class="mt-2">
			<b-card class="shadow-sm">
				<b-card-body>
					<b-card-header class="px-0">
						<b-container class="clearfix px-2" fluid>
							<h4 class="text-primary font-weight-bold float-left">Pupils</h4>

							<b-button 
							variant="primary"
							size="sm"
							v-b-modal.add-modal
							class="float-right">
								<b-icon icon="pencil"></b-icon>
								<!-- <span class="fa fa-fw fa-plus-circle"></span> -->
								Add Pupil
							</b-button>
						</b-container>
					</b-card-header>

					<b-table borderless striped hover :items="pupils" :fields="pupil_fields" responsive="md">
						<template v-slot:cell(id)="data">
							{{data.item.id}}
						</template>

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
	</div>
</template>
<script>
	export default {
		name: 'PupilsIndex',
		props: ['host'],
		data() {
			return {
				pupils: null,
				pupil_fields: [
					{
						key: 'id',
						label: 'ID',
						sortable: true
					},
					{
						key: 'name',
						label: 'Name',
						sortable: true
					},
					{
						key: 'index',
						label: 'Index'
					},
				]
			}
		}, 
		mounted() {
			this.getPupils()
		},
		methods: {
			getPupils: function() {
				let pupilsAPI = `${this.host}/pupils`
				axios.get(pupilsAPI)
				.then(response => {
					this.pupils = response.data.data

					console.log(response.data.data)
				})
				.catch(err => console.log(err))
			}
		}
	}
</script>
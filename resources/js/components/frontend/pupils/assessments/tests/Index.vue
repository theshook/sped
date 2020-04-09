<template>
  <div>
    <PageLoader v-if="show_loader" />

    <section v-else>
      <b-form-row class="mb-4">
        <b-col lg="6">
          <h1 class="font-weight-bolder text-custom-dark">Assessment Tests</h1>
        </b-col>

        <b-col lg="6" class="text-right">
          <b-input-group>
            <b-form-input
              v-model="search"
              class="col-lg-6 text-sm py-4 px-3 ml-auto"
              placeholder="Search"
            ></b-form-input>

            <template v-slot:append>
              <b-button class="text-muted text-sm" variant="light">
                <b-icon icon="search"></b-icon>
              </b-button>

              <b-button
                class="text-muted text-sm"
                variant="light"
                v-b-toggle.filter-collapse
              >
                <b-icon icon="funnel"></b-icon>
              </b-button>
            </template>
          </b-input-group>
        </b-col>
      </b-form-row>

      <!-- FILTER COLLAPSE CARD -->
      <b-collapse class="mb-3" id="filter-collapse">
        <b-card class="shadow-sm" no-body>
          <b-card-body>
            <p class="text-muted">
              <b-icon class="mr-2" icon="funnel"></b-icon>Filter Options
            </p>

            <div class="text-right pt-3">
              <b-button
                class="border-light text-sm text-muted py-2 px-3"
                variant="light"
                >Reset</b-button
              >
              <b-button
                class="border-light text-sm bg-green py-2 px-3"
                variant="success"
                >Apply</b-button
              >
            </div>
          </b-card-body>
        </b-card>
      </b-collapse>

      <div v-if="assessment_categories.length">
        <b-card
          class="assessment-test-card shadow-sm mb-4"
          v-for="(assessment, index) in assessment_categories"
          :key="index"
        >
          <b-button
            class="text-decoration-none px-0"
            variant="link"
            v-b-modal.checklist-modal
          >
            <h5>{{ assessment.title }}</h5>
          </b-button>
          <p class="assessment-test-description text-sm lh-1">
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil
            labore odit, voluptatum vel eaque itaque quidem delectus repudiandae
            reprehenderit rerum adipisci rem beatae, recusandae nobis similique!
            Recusandae sit suscipit commodi.
          </p>
        </b-card>

        <b-container class="clearfix px-0" fluid>
          <b-pagination
            class="float-right"
            v-model="current_page"
            :per-page="Number(10)"
            :total-rows="Number(assessment_categories.length)"
            @change="getAssessmentTests"
            aria-controls="assessment-tests-list"
          ></b-pagination>
        </b-container>
      </div>

      <div class="mt-5" v-else></div>
    </section>

    <!-- CHOOSE ASSESSMENT CHECKLIST MODAL -->
    <b-modal
      id="checklist-modal"
      size="small"
      title="Choose category"
      button-size="sm"
      ok-only
      ok-title="Proceed"
      ok-variant="light"
      @ok="proceedToTest"
    >
      <small class="text-muted">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus
        illo quisquam voluptatum inventore, omnis officiis.
      </small>
    </b-modal>
  </div>
</template>

<script>
import PageLoader from "./Loader.vue";
export default {
  name: "PupilAssessmentTestsIndex",
  props: ["host"],
  components: { PageLoader },
  data() {
    return {
      show_loader: true,
      search: "",
      current_page: 1,
      assessment_categories: [
        {
          title: "Knowledge Assessment"
        },
        {
          title: "Pscychological Assessment"
        },
        {
          title: "Emotional Assessment"
        },
        {
          title: "Physical Assessment"
        }
      ]
    };
  },
  computed: {},
  validations: {},
  mounted() {
    this.disableLoader();
  },
  methods: {
    getAssessmentTests: function() {},

    proceedToTest: function() {},

    disableLoader: function() {
      setTimeout(() => {
        this.show_loader = false;
      }, 3000);
    }
  }
};
</script>

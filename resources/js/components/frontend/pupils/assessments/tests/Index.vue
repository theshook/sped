<template>
  <div>
    <section class="page-header-banner">
      <div class="page-header-banner-content container">
        <h1 class="text-center font-weight-bolder">Assessment Tests</h1>

        <br />

        <b-form-input
          v-model="search"
          class="shadow-lg w-100 px-sm-3 px-lg-4 py-4"
          placeholder="Search"
        ></b-form-input>
      </div>
    </section>

    <b-container>
      <PageLoader v-if="show_loader" />

      <section v-else>
        <b-container class="no-data-found" v-if="!assessment_categories.length">
          <h4 class="font-weight-bold">No results found</h4>
          <p
            class="text-black-50"
            v-if="search_err"
          >It seems we can’t find any results based on your search.</p>
          <p class="text-muted" v-else>No open assessment test(s) as of today.</p>
        </b-container>

        <div v-else>
          <h1 class="font-weight-bolder mb-4">Psychological</h1>

          <div class="card-deck" v-for="(i, index) in 3" :key="index">
            <b-card class="border-light shadow-sm mb-3">
              <h4 class="text-black-50 font-weight-bold">Level {{ index + 1 }}</h4>
              <p class="assessment-test-description text-sm lh-1">
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quae,
                et nihil aspernatur officiis laborum ipsa?
              </p>
            </b-card>
          </div>
        </div>
      </section>
    </b-container>
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
      show_loader: false,
      show_search: false,
      search: "",
      search_err: false,
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

    toggleShowSearch: function() {
      this.show_search = !this.show_search;
    },

    disableLoader: function() {
      setTimeout(() => {
        this.show_loader = false;
      }, 3000);
    }
  }
};
</script>

<template>

  <div class="p-2">

    <h1 class="mb-5 mt-2 text-center">Kérdőívekre adott válaszok</h1>

    <div class="w-75 mx-auto">

      <div v-if="(pageLoad || (!pageLoad && !isBaseDataLoaded)) && !search" class="text-center alert alert-info fw-bold">
        {{ message }}
      </div>

      <!-- select listed survey -->
      <v-select
          :disabled="pageLoad"
          v-model="selectedSurveyId"
          :items="surveyCollection"
          item-title="name"
          item-value="id"
          label="Kérdőív kiválasztása"
          @update:modelValue="handleSurveyChange"
      ></v-select>

      <!-- search bar -->
      <v-row v-if="responseCollectionInitStateLength > 0">
        <v-col cols="12">
          <v-text-field
              v-model="search"
              class="my-4"
              label="Keresés válasz alapján"
              v-debounce:300ms="fetchData"
          ></v-text-field>
        </v-col>
      </v-row>

      <!-- table -->
      <v-data-table
          v-if="responseCollection.length"
          :headers="!isMobile ? headers : mobileHeaders"
          :items="responseCollection"
          :loading-text="'Loading...'"
          disable-pagination
          hide-default-footer
          :loading="$root.isLoading"
      >

        <!-- HTML megjelenítése az id_response oszlopban -->
        <template v-slot:item.id_response="{ item }">
          <span v-html="item.id_response"></span>
        </template>

        <!-- header -->
        <template v-slot:headers="{ headers }" v-if="isMobile">
          <tr>
            <td v-for="header in headers[0]" class="text-center">
              <span v-html="header.title" class="fw-bold text-uppercase"></span>
              <hr>
            </td>
          </tr>
        </template>

        <!-- footer -->
        <template #tfoot>
          <PaginationFooter :callback="setCurrentPage"/>
        </template>

      </v-data-table>

      <div style="margin-top: 70px" v-if="blade==0">
        <RouterLink to="/">
          <button class="btn btn-primary">
            Kezdőlap
          </button>
        </RouterLink>
      </div>

      <div style="margin-top: 70px" v-if="blade==1">
        <a href="/">
          <button class="btn btn-primary">
            Kezdőlap
          </button>
        </a>
      </div>

    </div>

  </div>
</template>

<script>

import PaginationFooter from "../Layout/PaginationFooter.vue";
import {SET_SNACKBAR} from "../../store/constants";
import {mapActions, mapGetters, mapMutations, mapState} from "vuex";

export default {
  name: "ResponseComponent",
  components: {PaginationFooter},

  data() {
    return {
      message: 'Oldal töltődik',
      pageLoad: true,
      responseCollectionLoaded: false,
      emptyDatabase: true,

      search: null,
      isMobile: window.innerWidth <= 768,

      selectedSurveyId: null
    }
  },

  created() {

  },

  computed: {
    ...mapState({
      isBaseDataLoaded() {
        return this.responseCollectionLoaded;
      },
      blade: state => state.blade
    }),
    ...mapGetters('Survey', ["surveyCollection"]),
    ...mapGetters('Response', ["headers", "mobileHeaders", "responseCollection", "responseCollectionInitStateLength"])
  },

  mounted: function () {
    window.addEventListener('resize', this.handleResize);
    this.loadActions();

    // Ensures that your code is always working on updated DOM-based data.
    this.$nextTick(() => {
      this.init();
    });
  },

  watch: {
    responseCollection: {
      deep: true,
      handler: function (newValue, oldValue) {
        console.log('responseCollection változott:', oldValue, '->', newValue)

        if (!this.responseCollection || !this.responseCollection.length) {
          this.message = "Jelenleg nincs kitöltött kérdőív a rendszerben.";
          this.responseCollectionLoaded = false;
        } else {
          const firstItem = this.responseCollection.find(item => item.question && item.question.survey);

          if (firstItem) {
            this.selectedSurveyId = firstItem.question.survey.id;
          }

          this.responseCollectionLoaded = true;
        }
      }
    },
    isBaseDataLoaded(newValue) {
      if (newValue) {
        this.message = '';

        this.pageLoad = false;
        this.emptyDatabase = false;
      }
    },
  },

  methods: {
    ...mapMutations({setSnackbar: SET_SNACKBAR}),
    ...mapActions("Table", ["setPage"]),

    ...mapActions("Response", ["getResponseData"]),
    ...mapActions("Survey", ["getSurveyData"]),

    loadActions() {
      this.getResponseData();
      this.getSurveyData();
    },

    init() {
    },

    handleSurveyChange(selectedValue) {
      this.selectedSurveyId = selectedValue;

      this.getResponseData({search: this.search, surveyId: this.selectedSurveyId});
    },

    // --------- pagination ---------
    handleResize() {
      this.isMobile = window.innerWidth <= 768;
    },

    fetchData() {
      this.getResponseData({search: this.search, surveyId: this.selectedSurveyId});
    },

    setCurrentPage(e) {
      this.setPage(e);
      this.getResponseData({search: this.search, surveyId: this.selectedSurveyId});
    },
  }

}
</script>

<style scoped>

</style>
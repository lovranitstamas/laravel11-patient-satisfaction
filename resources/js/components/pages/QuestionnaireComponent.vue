<template>

  <div class="p-2">

    <h1 class="mb-5 mt-2 text-center">Kérdőívek</h1>

    <div class="w-75 mx-auto">

      <form
          id="app"
          ref="form"
      >

        <div v-if="pageLoad || (!pageLoad && !isBaseDataLoaded)" class="text-center alert alert-info fw-bold">
          {{ message }}
        </div>

        <div class="row">
          <div class="col-12 col-md-4">
            <v-select
                v-model="selectedSurvey"
                :items="surveyCollection"
                item-title="name"
                item-value="id"
                label="Kérdőív"
                :readonly="pageLoad"
            ></v-select>
          </div>
          <div class="col-12 col-md-8">
            <v-text-field ref="question" label="Kérem adja meg a kérdést"></v-text-field>
          </div>
        </div>

        <!-- messages -->
        <div class="alert alert-info text-center font-weight-bold mt-3" role="alert" v-if="changedNothing">
          Nem történt változás. Vissza az új adatfelvitelre
        </div>

        <div class="alert alert-info text-center fw-bold mt-3" role="alert" v-show="inProgress">
          Mentés folyamatban
        </div>

      </form>

      <!-- search bar -->
      <v-row v-if="questionCollectionInitStateLength > 0">
        <v-col cols="12">
          <v-text-field
              v-model="search"
              class="my-4"
              label="Keresés kérdés alapján"
              v-debounce:300ms="fetchData"
          ></v-text-field>
        </v-col>
      </v-row>

      <!-- table -->
      <v-data-table
          v-if="questionCollection.length"
          :headers="!isMobile ? headers : mobileHeaders"
          :items="questionCollection"
          :loading-text="'Loading...'"
          disable-pagination
          hide-default-footer
          :loading="$root.isLoading"
      >

        <!-- header -->
        <template v-slot:headers="{ headers }" v-if="isMobile">
          <tr>
            <td v-for="header in headers[0]" class="text-center">
              <span v-html="header.title" class="fw-bold text-uppercase"></span>
              <hr>
            </td>
          </tr>
        </template>

        <!-- mobile/button -->
        <template v-slot:item="{ item }" v-if="isMobile">
          <div class="d-flex justify-content-center align-items-center text-center text-uppercase mt-1">
            <span v-html="item.id_question" class="d-block ms-5"></span>
            <v-icon
                @click=""
                :class="{ 'text-green': true }"
                class="mx-2 p-0"
            >
              mdi-pencil
            </v-icon>
          </div>
        </template>

        <!--button-->
        <template v-slot:item.actions="{ item }">
          <v-icon
              @click=""
              :class="{ 'text-green': true }"
          >
            mdi-pencil
          </v-icon>
          <v-icon
              @click=""
              :class="{ 'text-red': true }"
          >
            mdi-delete
          </v-icon>
        </template>

        <!-- footer -->
        <template #tfoot>
          <PaginationFooter :callback="setCurrentPage"/>
        </template>

      </v-data-table>

    </div>

  </div>
</template>

<script>

import PaginationFooter from "../Layout/PaginationFooter.vue";
import {mapActions, mapGetters, mapState} from "vuex";

export default {
  name: "QuestionnaireComponent",
  components: {PaginationFooter},

  data() {
    return {
      message: 'Oldal töltődik',
      pageLoad: true,
      questionCollectionLoaded: false,

      changedNothing: false,
      inProgress: false,

      search: null,
      selectedSurvey: null,
      isMobile: window.innerWidth <= 768,

      questionnaire: {
        id: '',
        survey_id: '',
        question: '',
      },
      storedQuestionnaire: {
        id: '',
        survey_id: '',
        question: '',
      }
    }
  },

  created() {

  },

  computed: {
    ...mapState({
      isBaseDataLoaded() {
        return this.questionCollectionLoaded;
      },
    }),
    ...mapGetters('Survey', ["surveyCollection"]),
    ...mapGetters('Questionnaire', ["headers", "mobileHeaders", "questionCollection", "questionCollectionInitStateLength"])
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
    questionCollection: {
      deep: true,
      handler: function (newValue, oldValue) {
        console.log('questionCollection változott:', oldValue, '->', newValue)

        if (!this.questionCollection || !this.questionCollection.length) {
          this.message = "Adatbázis üres. Kérem töltsön fel legalább egy kérdőívet, majd a hozzá tartozó kérdéseket.";
          this.questionCollectionLoaded = false;
        } else {
          this.questionCollectionLoaded = true;
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
    ...mapActions("Table", ["setPage"]),

    ...mapActions("Survey", ["getSurveyData"]),
    ...mapActions("Questionnaire", ["getQuestionData"]),

    loadActions() {
      this.getSurveyData();
      this.getQuestionData();
    },

    init() {
    },

    // --------- pagination ---------
    handleResize() {
      this.isMobile = window.innerWidth <= 768;
    },

    fetchData() {
      this.getQuestionData({search: this.search});
    },

    setCurrentPage(e) {
      this.setPage(e);
      this.getQuestionData({search: this.search});
    },
  }

}
</script>

<style scoped>

</style>
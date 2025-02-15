<template>

  <div class="p-2">

    <h1 class="mb-5 mt-2 text-center">Kérdőívek</h1>

    <div class="w-75 mx-auto">

      <v-data-table
          v-if="questionCollection.length"
          :headers="!isMobile ? headers : mobileHeaders"
          :items="questionCollection"
          :loading-text="'Loading...'"
          disable-pagination
          hide-default-footer
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
      search: null,
      isMobile: window.innerWidth <= 768,
    }
  },

  created() {

  },

  computed: {
    ...mapState({}),
    ...mapGetters('Survey', ["surveyCollection"]),
    ...mapGetters('Questionnaire', ["headers", "mobileHeaders", "questionCollection"])
  },

  mounted: function () {
    window.addEventListener('resize', this.handleResize);
    this.loadActions();

    // Ensures that your code is always working on updated DOM-based data.
    this.$nextTick(() => {
      this.init();
    });
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

    handleResize() {
      this.isMobile = window.innerWidth <= 768;
    },

    // --------- pagination --------- LT
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
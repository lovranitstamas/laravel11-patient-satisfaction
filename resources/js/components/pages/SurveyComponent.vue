<template>
  <div class="p-2">

    <h1 class="mb-5 mt-2 text-center">Kérdőív szerkesztése</h1>

    <div class="w-75 mx-auto">

      <form
          id="app"
          ref="form"
      >

        <div v-if="(pageLoad || (!pageLoad && !isBaseDataLoaded)) && !search" class="text-center alert alert-info fw-bold">
          {{ message }}
        </div>

        <div class="row">
          <div class="col-12 col-md-8 mx-auto">
            <v-text-field ref="question" label="Kérem adja meg a kérdőív nevét"
                          v-model="survey.name">

            </v-text-field>
          </div>
        </div>

        <!-- messages -->
        <div class="alert alert-info text-center font-weight-bold mt-3" role="alert" v-if="changedNothing">
          Nem történt változás. Vissza az új adatfelvitelre
        </div>

        <div class="alert alert-info text-center fw-bold mt-3" role="alert" v-show="inProgress">
          Művelet folyamatban
        </div>

        <div class="alert alert-success text-center font-weight-bold mt-3" role="alert" v-if="savingSuccessful">
          Mentés megtörtént
        </div>

        <div class="alert alert-success text-center font-weight-bold mt-3" role="alert" v-if="deletingSuccessful">
          Törlés megtörtént
        </div>

        <!-- modals -->
        <b-modal ref="b-modal-form-error" ok-only centered title="Hiba" @ok="closeErrorModal()">
          <ul class="my-4" v-if="errors.length">
            <li v-for="error in errors">{{ error }}</li>
          </ul>
        </b-modal>

        <div class="row">
          <div class="col-12 col-md-6 mx-auto text-center">
            <button
                type="button"
                @click.prevent="checkForm(!isUpdateMode)"
                :value="isUpdateMode ? 'Módosítás' : 'Mentés'"
                class="btn btn-lg text-white"
                :class="{'btn-success': isUpdateMode, 'btn-primary': !isUpdateMode}"
                :disabled="inProgress || emptyDatabase"
            ><!--Tovább-->{{ isUpdateMode ? 'Kérdőív módosítása' : 'Új kérdőív felvitele' }}
            </button>
          </div>
        </div>

      </form>

      <h3 class="my-5 text-center">Kérdőívek listája</h3>

      <!-- search bar -->
      <v-row v-if="surveyCollectionBasedOnPaginationInitStateLength > 0">
        <v-col cols="12">
          <v-text-field
              v-model="search"
              class="my-4"
              label="Keresés kérdőív neve alapján"
              v-debounce:300ms="fetchData"
          ></v-text-field>
        </v-col>
      </v-row>

      <p class="text-start text-danger fw-bold">
        A lakattal ellátott kérdések nem módosíthatók/törölhetők már meglévő válaszok miatt!
      </p>

      <!-- table -->
      <v-data-table
          v-if="surveyCollectionBasedOnPagination.length"
          :headers="!isMobile ? headers : mobileHeaders"
          :items="surveyCollectionBasedOnPagination"
          :loading-text="'Loading...'"
          disable-pagination
          hide-default-footer
          :loading="$root.isLoading"
      >

        <!-- HTML megjelenítése az id_response oszlopban -->
        <template v-slot:item.id_name="{ item }">
          <span v-html="item.id_name"></span>
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

        <!-- mobile/button -->
        <template v-slot:item="{ item }" v-if="isMobile">
          <div class="d-flex justify-content-center align-items-center text-center text-uppercase mt-1">
            <span v-html="item.id_question" class="d-block ms-5"></span>
            <v-icon
                v-if="!item.exists_in_responses"
                @click="loadSurveyData(item)"
                :class="{ 'text-green': true }"
                class="mx-2 p-0"
            >
              mdi-pencil
            </v-icon>
            <v-icon v-if="item.exists_in_responses"
                    @click="callDeleteFunction(item)"
                    :class="{ 'text-red': true }"
                    size="x-large"
            >
              mdi-lock
            </v-icon>
          </div>
        </template>

        <!--button-->
        <template v-slot:item.actions="{ item }">
          <v-icon v-if="!item.exists_in_responses"
                  @click="loadSurveyData(item)"
                  :class="{ 'text-green': true }"
          >
            mdi-pencil
          </v-icon>
          <v-icon v-if="!item.exists_in_responses"
                  @click="callDeleteFunction(item)"
                  :class="{ 'text-red': true }"
          >
            mdi-delete
          </v-icon>
          <v-icon v-if="item.exists_in_responses"
                  :class="{ 'text-red': true }"
                  size="x-large"
          >
            mdi-lock
          </v-icon>
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
  name: "SurveyComponent",

  components: {PaginationFooter},

  data() {
    return {
      message: 'Oldal töltődik',
      pageLoad: true,
      surveyCollectionLoaded: false,
      emptyDatabase: true,

      changedNothing: false,
      inProgress: false,
      isUpdateMode: false,
      savingSuccessful: false,
      deletingSuccessful: false,

      search: null,
      isMobile: window.innerWidth <= 768,

      survey: {
        id: '',
        name: '',
      },
      storedSurvey: {
        id: '',
        name: '',
      },
      errors: []
    }
  },

  created() {

  },

  computed: {
    ...mapState({
      isBaseDataLoaded() {
        return this.surveyCollectionLoaded;
      },
      blade: state => state.blade
    }),
    ...mapGetters('Survey', ["headers", "mobileHeaders", "surveyCollectionBasedOnPagination", "surveyCollectionBasedOnPaginationInitStateLength"])
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
    surveyCollectionBasedOnPagination: {
      deep: true,
      handler: function (newValue, oldValue) {
        console.log('surveyCollectionBasedOnPagination változott:', oldValue, '->', newValue)

        if (!this.surveyCollectionBasedOnPagination || !this.surveyCollectionBasedOnPagination.length) {
          this.message = "Adatbázis üres. Kérem töltsön fel legalább egy kérdőívet.";
          this.surveyCollectionLoaded = false;
        } else {
          this.surveyCollectionLoaded = true;
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

    ...mapActions("Survey", ["getSurveyDataBasedOnPagination", "storeSurveyData", "updateSurveyData", "deleteSurvey"]),

    loadActions() {
      this.getSurveyDataBasedOnPagination();
    },

    init() {
    },

    //load data
    loadSurveyData(surveyEntry) {
      this.fillSurvey(this.survey, surveyEntry);
      this.fillSurvey(this.storedSurvey, surveyEntry);

      this.isUpdateMode = true;

      window.scrollTo(0, 0);

    },

    fillSurvey(survey, storedSurvey) {
      survey.id = storedSurvey.id;
      survey.name = storedSurvey.name;
    },

    checkForm: function (newItem) {
      //close snackbar;
      this.setSnackbar({show: false, messages: []})

      let create = !newItem && this.survey.id ? false : true;

      if (this.isUpdateMode && !this.isSurveyChanged()) {

        this.changedNothing = true;
        setTimeout(() => {
          this.reInit();
        }, 1000);
        return;
      }

      this.closeErrorModal();

      this.errors = [];
      this.savingSuccessful = false;

      if (this.isInputsValid()) {
        this.inProgress = true;

        if (create) {
          this.storeSurveyData({survey: this.survey})
              .then((resp) => {
                this.closeSavingAndUpdating(resp);

                this.search = null;
                this.selectedSurveyId = null;

                this.setPage(1);
                this.getSurveyDataBasedOnPagination({search: this.search});
              })
              .catch(err => {
                if (err) {
                  this.errors.push(err);
                  this.showErrorModal();
                }
                this.inProgress = false;
                console.log(err)
              });
        } else {
          this.updateSurveyData({survey: this.survey})
              .then((resp) => {
                this.closeSavingAndUpdating(resp);
              })
              .catch(err => {
                if (err) {
                  this.errors.push(err);
                  this.showErrorModal(true);
                }
                this.inProgress = false;
                console.log(err)
              });
        }
      } else {
        this.fillUpErrorArray();
        return false;
      }
    },

    // validations
    isSurveyChanged() {
      for (let key in this.survey) {
        if (this.survey[key] !== this.storedSurvey[key]) {
          return true;
        }
      }
      return false;
    },

    isInputsValid() {
      return !!(this.survey.name);
    },

    // not changes and re-init form
    reInit() {
      this.setNullInputs();

      this.isUpdateMode = false;
      this.changedNothing = false;
    },

    setNullInputs() {
      this.clearSurvey(this.survey);
      this.clearSurvey(this.storedSurvey);
    },

    clearSurvey(survey) {
      survey.name = '';
    },

    closeSavingAndUpdating(resp) {
      if (resp && resp.data && !resp.data.errors) {

        this.savingSuccessful = true;
        this.isUpdateMode = false;
        this.setNullInputs();

        setTimeout(() => this.savingSuccessful = false, 5000);
      } else if (resp && resp.data && resp.data.errors) {
        // if we return r without reject and errors
        resp.data.errors.forEach((error) => {
          this.errors.push(error.message);
        });
      } else {
        // If there is no specific answer, we still consider it successful
        this.savingSuccessful = true;
        this.isUpdateMode = false;

        this.setNullInputs();
        setTimeout(() => this.savingSuccessful = false, 5000);
      }

      this.inProgress = false;
      return true;
    },

    callDeleteFunction(survey) {
      this.inProgress = true;
      this.errors = [];

      this.deleteSurvey({survey: survey})
          .then(() => {
            this.deletingSuccessful = true;
            this.inProgress = false;

            //load first page
            this.search = null;
            this.selectedSurveyId = null;

            this.setPage(1);
            this.getSurveyDataBasedOnPagination({search: this.search});

            setTimeout(() => this.deletingSuccessful = false, 5000);
          })
          .catch(err => {
            if (err) {
              this.errors.push(err);
              this.showErrorModal();
            }
            this.inProgress = false;
            console.log(err)
          });
    },

    // --------- error modals ---------
    fillUpErrorArray() {
      if (!this.survey.name) {
        this.errors.push('Kérdőív nevének megadása kötelező.');
      }

      this.showErrorModal();
    },

    showErrorModal(shouldUpdate = false) {
      this.$refs['b-modal-form-error'].show();
      if (!shouldUpdate) {
        this.isUpdateMode = false;
      }
    },

    closeErrorModal() {
      this.$refs['b-modal-form-error'].hide();
    },

    handleSurveyChange(selectedValue) {
      this.selectedSurveyId = selectedValue;

      this.getSurveyDataBasedOnPagination({search: this.search});
    },

    // --------- pagination ---------
    handleResize() {
      this.isMobile = window.innerWidth <= 768;
    },

    fetchData() {
      this.getSurveyDataBasedOnPagination({search: this.search});
    },

    setCurrentPage(e) {
      this.setPage(e);
      this.getSurveyDataBasedOnPagination({search: this.search});
    },
  }
}
</script>

<style scoped>

</style>
<template>
  <div class="p-2">

    <h1 class="mb-5 mt-2 text-center">Kérdőív kitöltése</h1>

    <div class="w-75 mx-auto">

      <form
          id="app"
          ref="form"
      >

        <div v-if="pageLoad || (!pageLoad && !isBaseDataLoaded)" class="text-center alert alert-info fw-bold">
          {{ message }}
        </div>

        <!-- messages -->
        <div class="alert alert-success text-center font-weight-bold mt-3" role="alert" v-if="savingSuccessful">
          Mentés megtörtént
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
                @click.prevent="checkForm()"
                class="btn btn-lg text-white"
                :disabled="inProgress || emptyDatabase"
            >Válaszok elküldése
            </button>
          </div>
        </div>

      </form>

    </div>
  </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations, mapState} from "vuex";
import {SET_SNACKBAR} from "../../store/constants";

export default {
  name: "UQuestionnaireComponent",

  data() {
    return {
      message: 'Oldal töltődik',
      pageLoad: true,
      questionCollectionLoaded: false,
      emptyDatabase: true,

      inProgress: false,
      savingSuccessful: false,

      errors: []
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
    ...mapGetters('Questionnaire', ["userQuestionCollection"])
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
    userQuestionCollection: {
      deep: true,
      handler: function (newValue, oldValue) {
        console.log('questionCollection változott:', oldValue, '->', newValue)

        if (!this.userQuestionCollection || !this.userQuestionCollection.length) {
          this.message = "A rendszer nem tartalmaz kérdőívet.";
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
    ...mapMutations({setSnackbar: SET_SNACKBAR}),

    ...mapActions("Questionnaire", ["getUserQuestionnaire"]),

    loadActions() {
      this.getUserQuestionnaire();
    },

    init() {
    },

    //save questions and answers
    checkForm: function () {
      //close snackbar;
      this.setSnackbar({show: false, messages: []})

      this.closeErrorModal();

      this.errors = [];
      this.savingSuccessful = false;

      if (this.isInputsValid()) {
        this.inProgress = true;

      } else {
        this.fillUpErrorArray();
        return false;
      }
    },

    isInputsValid() {
    },

    // --------- error modals ---------
    fillUpErrorArray() {
      this.showErrorModal();
    },

    showErrorModal() {
      this.$refs['b-modal-form-error'].show();
    },

    closeErrorModal() {
      this.$refs['b-modal-form-error'].hide();
    },

    // --------- pagination ---------
    handleResize() {
      this.isMobile = window.innerWidth <= 768;
    },
  }
}
</script>

<style scoped>

</style>
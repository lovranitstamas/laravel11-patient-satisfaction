<template>
  <div class="p-2">

    <h1 class="mb-5 mt-2 text-center">Kérdőív kitöltése</h1>

    <div class="w-75 mx-auto">

      <form
          id="app"
          ref="form"
          class="pt-5"
      >

        <div v-if="pageLoad || (!pageLoad && !isBaseDataLoaded)" class="text-center alert alert-info fw-bold">
          {{ message }}
        </div>

        <!-- messages -->
        <div class="alert alert-success text-center font-weight-bold mt-3" role="alert" v-if="savingSuccessful">
          Mentés megtörtént
        </div>

        <div class="row">
          <div class="col-12 col-md-8 mx-auto">
            <v-select
                label="Nem (opcionális)"
                :items="genderItems"
                item-value="value"
                item-title="title"
                v-model="submitter_name"
                @update:modelValue="setSubmitterName"
            >
            </v-select>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-md-8 mx-auto">
            <v-text-field
                label="E-mail cím (opcionális)"
                v-model="email">
            </v-text-field>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-md-8 mx-auto">
            <p class="fw-bold text-danger">1 (legrosszabb), 5 (legjobb)</p>
          </div>
        </div>

        <div v-for="userQuestion in userQuestionCollection">
          <div class="row">
            <div class="col-12 col-md-8 mx-auto">
              <p class="fw-bold">{{ userQuestion.question }}</p>
              <v-select
                  label="Kérem válasszon 1-5 ig"
                  v-model="answers[userQuestion.id]"
                  :items="['1','2', '3', '4', '5']"
                  item-value="value"
                  item-title="value"
              >
              </v-select>
            </div>
          </div>
        </div>

        <!-- modals -->
        <b-modal ref="b-modal-form-error" ok-only centered title="Hiba" @ok="closeErrorModal()">
          <ul class="my-4" v-if="errors.length">
            <li v-for="error in errors"><span v-html="error"></span></li>
          </ul>
        </b-modal>

        <div class="row">
          <div class="col-12 col-md-6 mx-auto text-center">
            <button
                type="button"
                @click.prevent="checkForm()"
                class="btn btn-lg btn-primary text-white"
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

      submitter_name: null, //gender
      email: '',
      errors: [],
      answers: {},

      genderItems: [
        {value: null, title: 'Nem adom meg'},
        {value: 1, title: 'Férfi'},
        {value: 2, title: 'Nő'}
      ]
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
    ...mapActions("Response", ["storeResponseData"]),

    loadActions() {
      this.getUserQuestionnaire();
    },

    init() {
    },

    setSubmitterName(selectedValue) {
      const selectedItem = this.genderItems.find(item => item.value === selectedValue);
      this.submitter_name = selectedItem && selectedItem.value ? selectedItem.title : null;
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

        this.storeResponseData({
          userResponses: {
            submitter_name: this.submitter_name, email: this.email,
            answers: this.answers
          }
        })
            .then((resp) => {
              this.closeSavingAndUpdating(resp);
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
        this.fillUpErrorArray();
        return false;
      }
    },

    isInputsValid() {
      if (this.email.trim().length && !this.isValidEmail(this.email)) {
        return false;
      }

      for (let question of this.userQuestionCollection) {
        if (!this.answers[question.id] || this.answers[question.id] === '') {
          return false;
        }
      }
      return true;
    },

    isValidEmail(email) {
      const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      return emailRegex.test(email);
    },

    setNullInputs() {
      this.clearQuestionnaire();

      window.scrollTo(0, 0);
    },

    clearQuestionnaire() {
      this.submitter_name = null;
      this.email = '';

      for (let key in this.answers) {
        this.answers[key] = '';
      }
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

    // --------- error modals ---------
    fillUpErrorArray() {
      if (this.email && !this.isValidEmail(this.email)) {
        this.errors.push('E-mail cím formátum nem megfelelő');
      }

      for (let question of this.userQuestionCollection) {
        if (!this.answers[question.id] || this.answers[question.id] === '') {
          this.errors.push(`"<strong>${question.question}</strong>" - kérdésre nem válaszolt.`);
        }
      }

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
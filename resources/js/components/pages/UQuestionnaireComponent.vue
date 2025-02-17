<template>
  <div class="p-2">

    <h1 class="mb-5 mt-2 text-center">Kérdőív</h1>

    <div class="w-75 mx-auto">
    </div>
  </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations} from "vuex";
import {SET_SNACKBAR} from "../../store/constants";

export default {
  name: "UQuestionnaireComponent",

  data() {
    return {}
  },

  created() {

  },

  computed: {
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

  methods: {
    ...mapMutations({setSnackbar: SET_SNACKBAR}),

    ...mapActions("Questionnaire", ["getUserQuestionnaire"]),

    loadActions() {
      this.getUserQuestionnaire();
    },

    init() {
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
<template>
  <v-snackbar
      v-model="snackbar.show"
      :timeout="-1"
      :color="snackbar.color"
      app
      right
      top
  >
    <p v-for="(message, index) in snackbar.messages" :key="index" class="m-0 mb-0">
      {{ message }}
    </p>
    <template v-slot:actions="{ attrs }">
      <v-btn
          v-bind="attrs"
          @click="setSnackbar({ show: false, messages: [] })"
          class="bg-success"
      >
        Ok
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script>
import {mapGetters, mapMutations} from 'vuex'
import {SET_SNACKBAR} from '../../store/constants'

export default {
  name: 'Snackbar',
  computed: {
    ...mapGetters(['snackbar']),
  },
  methods: {
    ...mapMutations({setSnackbar: SET_SNACKBAR}),
  },
}
</script>

<style scoped>

</style>



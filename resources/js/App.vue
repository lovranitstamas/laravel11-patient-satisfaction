<template>
  <main class="text-center">

    <Snackbar></Snackbar>

    <!--
    <div class="alert-info alert">test</div>

    <div>
      <i class="fa-solid fa-house fa-2x"></i>
    </div>

    <div class="mt-5">
      <b-badge variant="primary">Primary</b-badge>
      <b-badge variant="secondary">Secondary</b-badge>
      <b-badge variant="success">Success</b-badge>
      <b-badge variant="danger">Danger</b-badge>
      <b-badge variant="warning">Warning</b-badge>
      <b-badge variant="info">Info</b-badge>
      <b-badge variant="light">Light</b-badge>
      <b-badge variant="dark">Dark</b-badge>
    </div>

    <div>
      <h2>Example heading
        <b-badge>New</b-badge>
      </h2>
      <h3>Example heading
        <b-badge variant="primary">New</b-badge>
      </h3>
      <h4>Example heading
        <b-badge variant="secondary">New</b-badge>
      </h4>
      <h5>Example heading
        <b-badge variant="secondary">New</b-badge>
      </h5>
      <h6>Example heading
        <b-badge variant="secondary">New</b-badge>
      </h6>
    </div>

    <div class="text-center">
      <b-button variant="primary">
        Notifications
        <b-badge variant="secondary">4</b-badge>
      </b-button>
    </div>
    -->

    <router-view/>

  </main>
</template>

<script>

import Snackbar from "./components/Layout/Snackbar.vue";

export default {
  name: "App",

  components: {
    Snackbar
  }
}
</script>

<style scoped>

</style>
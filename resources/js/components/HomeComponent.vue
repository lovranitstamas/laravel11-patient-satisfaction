<script>
export default {
  name: "HomeComponent",
}
</script>

<template>
  <div class="m-1">

    <h1 class="mb-2 mt-1">Kezdőlap</h1>

    <div class="mt-3">
        <RouterLink to="/questionnaire">
          <button class="btn btn-primary">
            Kérdőív
          </button>
        </RouterLink>
    </div>

  </div>
</template>

<style scoped>

</style>
<script>
export default {
  name: "HomeComponent",
}
</script>

<template>
  <div class="">

    <h1 class="mt-5">Kezdőlap - Admin oldal</h1>

    <div>
      <RouterLink to="/survey">
        <button class="btn btn-primary mt-5">
          Kérdőívek szerkesztése
        </button>
      </RouterLink>
    </div>

    <div>
        <RouterLink to="/questionnaire">
          <button class="btn btn-primary mt-3">
            Kérdések szerkesztése
          </button>
        </RouterLink>
    </div>

    <div>
      <RouterLink to="/response">
        <button class="btn btn-primary mt-3">
          Kitöltött kérdővek
        </button>
      </RouterLink>
    </div>

  </div>
</template>

<style scoped>

</style>
<template>
  <main class="text-center">
    <div class="">

      <h1 class="mt-5">Kezdőlap - Admin oldal</h1>

      <div>
        <a href='surveys' class="btn btn-primary mt-5">
          Kérdőívek szerkesztése
        </a>
      </div>

      <div>
        <a href='questionnaires' class="btn btn-primary mt-5">
          Kérdések szerkesztése
        </a>
      </div>

      <div>
        <a href='responses' class="btn btn-primary mt-5">
          Kitöltött kérdővek
        </a>
      </div>

    </div>
  </main>
</template>

<script>
export default {
  name: "AdminDashboard",
}
</script>

<style scoped>

</style>
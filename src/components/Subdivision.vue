<template>
    <b-container>
        <b-row class="justify-content-md-center mb-3">
            <b-button pill variant="success">Добавить подразделение</b-button>
        </b-row>

        <b-card v-for="(item, index) in items" :key="index" :title="item.subdivision" class="mb-3">
            <b-card-text>
                {{ item.description }}
            </b-card-text>

            <a href="#" class="card-link text-right">Card link</a>
            <b-link href="#" class="card-link">Another link</b-link>
        </b-card>
    </b-container>
</template>

<script>
export default {
  data () {
    return {
      items: [
        { subdivision: 'Подразделение 1', description: 'Some quick example text to build on the card title content.' },
        { subdivision: 'Подразделение 2', description: 'Some quick example text to build on the card title content.' },
        { subdivision: 'Подразделение 3', description: 'Some quick example text to build on the card title content.' },
        { subdivision: 'Подразделение 4', description: 'Some quick example text to build on the card title content.' }
      ]
    }
  }
}
</script>

<style scoped lang="less">
.container {
    padding-top: 65px;
}
</style>

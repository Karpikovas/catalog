<template>
    <b-container>
        <b-row class="justify-content-md-center mb-3">
            <b-button pill variant="success">Добавить подразделение</b-button>
        </b-row>

      <template v-if="!inProgress">
        <b-card v-for="(item, index) in items" :key="index" class="mb-3">
          <b-row class="justify-content-md-end mb-3">
            <b-button-group class="mx-1">
              <b-button href="#" class="card-link" variant="info">
                <font-awesome-icon icon="edit"></font-awesome-icon>
              </b-button>
              <b-button href="#" class="card-link" variant="danger">
                <font-awesome-icon icon="trash"></font-awesome-icon>
              </b-button>
            </b-button-group>
          </b-row>
          <b-card-title>
            {{ item.name }}
          </b-card-title>
          <b-card-text>
            {{ item.description }}
          </b-card-text>
        </b-card>
      </template>

      <template v-else>
        <div class="d-flex justify-content-center mb-3">
          <b-spinner  variant="success" label="Spinning"></b-spinner>
        </div>
      </template>

    </b-container>
</template>

<script>
import axios from 'axios'
export default {
  data () {
    return {
      inProgress: true,
      items: [
        // { subdivision: 'Подразделение 1', description: 'Some quick example text to build on the card title content.' },
        // { subdivision: 'Подразделение 2', description: 'Some quick example text to build on the card title content.' },
        // { subdivision: 'Подразделение 3', description: 'Some quick example text to build on the card title content.' },
        // { subdivision: 'Подразделение 4', description: 'Some quick example text to build on the card title content.' }
      ]
    }
  },
  mounted () {
    axios
      .get('http://musiclibrary/subdivisions')
      .then(response => {
        this.inProgress = false
        this.items = response.data.data
      })
  }
}
</script>

<style scoped lang="less">
.container {
    padding-top: 65px;
}

.spinner-border {
  position: absolute;
  top: 50%;
  width: 5rem;
  height: 5rem;
}
</style>

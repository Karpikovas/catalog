<!-- eslint-disable -->
<template>
  <b-container>
    <b-alert
      :show="dismissCountDown"
      dismissible
      :variant="isSuccess ? 'success' : 'danger'"
      @dismissed="dismissCountDown=0"
    >
      <p>{{ alertMessage }}</p>
    </b-alert>
    <b-row class="justify-content-md-center mb-3">
      <b-button pill variant="success" v-on:click="modalAddShow">Добавить подразделение</b-button>
    </b-row>

    <template v-if="!inProgress">
      <b-card v-for="item in subdivisions" :key="item.id" class="mb-3">
        <b-row class="justify-content-md-end mb-3">
          <b-button-group class="mx-1">
            <b-button href="#" class="card-link" variant="info" v-on:click="modalEditShow(item)">
              <font-awesome-icon icon="edit"></font-awesome-icon>
            </b-button>
            <b-button href="#" class="card-link" variant="danger" v-on:click="modalDeleteShow(item.id)">
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
        <b-spinner variant="success" label="Spinning"></b-spinner>
      </div>
    </template>

    <b-modal
      ref="addSubdivisionModal"
      size="lg"
      title="Добавление"
      ok-title="Добавить"
      cancel-title="Отменить"
      @ok="onAddClick"
    >
      <b-container fluid>
        <b-row class="mb-3">
          <b-col cols="3">Название:</b-col>
          <b-col>
            <b-form-input
              v-model="addForm.name"
              placeholder="Введите название подразделения"
              aria-describedby="input-formatter-help"
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row class="mb-3">
          <b-col cols="3">Описание:</b-col>
          <b-col>
            <b-form-textarea
              v-model="addForm.description"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-col>
        </b-row>
      </b-container>
    </b-modal>

    <b-modal
      ref="editSubdivisionModal"
      size="lg"
      title="Добавление"
      ok-title="Добавить"
      cancel-title="Отменить"
      @ok="onEditClick"
    >
      <b-container fluid>
        <b-row class="mb-3">
          <b-col cols="3">Название:</b-col>
          <b-col>
            <b-form-input
              v-model="editForm.name"
              placeholder="Введите название подразделения"
              aria-describedby="input-formatter-help"
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row class="mb-3">
          <b-col cols="3">Описание:</b-col>
          <b-col>
            <b-form-textarea
              v-model="editForm.description"
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-col>
        </b-row>
      </b-container>
    </b-modal>

    <b-modal
      title="Удаление"
      ref="deleteSubdivisionModal"
      ok-title="Удалить"
      ok-variant="danger"
      @ok="onDeleteClick"
      cancel-title="Отменить"
    >
      Удалить информацию о подразделении?
    </b-modal>

  </b-container>
</template>

<script>
/* eslint-disable*/
import axios from 'axios'

export default {
    props: ['subdivisions'],
    data() {
        return {
            inProgress: true,
            addForm: {
                name: '',
                description: ''
            },
            editForm: {
                name: '',
                description: '',
                id: ''
            },
            dismissSecs: 10,
            dismissCountDown: 0,
            alertMessage: 'Информация успешно добавлена!',
            isSuccess: true
        }
    },
    methods: {
        modalAddShow: function () {
            this.addForm.description = ''
            this.addForm.name = ''

            this.$refs.addSubdivisionModal.show()
        },
        modalDeleteShow: function (id) {
            this.deleteId = id;
            this.$refs.deleteSubdivisionModal.show()
        },
        modalEditShow: function (subdivision) {
            this.editForm = Object.assign({}, subdivision)
            this.$refs.editSubdivisionModal.show()
        },
        onDeleteClick: function () {
            this.inProgress = true
            axios.post(`http://musiclibrary/subdivisions/sub/${this.deleteId}/delete`)
                .then(response => {
                    this.alertMsg(false, 'Информация о подразделении успешно удалена!')

                    this.subdivisions = this.subdivisions.filter((item) => {
                        return item.id !== this.deleteId
                    })
                    this.inProgress = false
                })
        },
        onAddClick: function () {
            this.inProgress = true
            let data = {
                name: this.addForm.name,
                description: this.addForm.description
            }
            let JData = JSON.stringify(data)

            axios.post('http://musiclibrary/subdivisions/sub/add', JData, {
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    let id = response.data.data[0]
                    data.id = id
                    this.subdivisions.push(data)
                    this.inProgress = false
                    this.alertMsg(true, 'Информация о подразделении успешно добавлена!')
                })
        },
        onEditClick: function () {
            this.inProgress = true
            let data = {
                name: this.editForm.name,
                description: this.editForm.description
            }

            axios.post(`http://musiclibrary/subdivisions/sub/${this.editForm.id}/update`, data, {
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    let targetIndex = this.subdivisions.findIndex(item => item.id === this.editForm.id)
                    this.subdivisions.splice(targetIndex, 1, data)
                    this.inProgress = false
                    this.alertMsg(true, 'Информация о подразделении успешно обновлена!')
                })
        },
        alertMsg: function (type, msg) {
            this.alertMessage = msg
            this.isSuccess = type
            this.dismissCountDown = this.dismissSecs
        }
    },
    watch: {
        subdivisions: function (newVal, oldVal) {
            this.inProgress = false
        }
    }
}
</script>

<style scoped lang="less">
  .container {
    padding-top: 65px;
  }

  .spinner-border {
    position: absolute;
    top: 40%;
    width: 5rem;
    height: 5rem;
  }
</style>

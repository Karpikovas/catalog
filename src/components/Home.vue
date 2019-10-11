<template>
<div>
    <b-container fluid class="main">
      <template v-if="!inProgress">
        <b-row>
            <b-col cols="2" class="position-fixed divider">
                <b-nav class="flex-sm-column text-center mt-2">
                    <b-nav-text class="mb-3"> Подразделения </b-nav-text>
                    <b-nav-item v-on:click="changeFilter('')">Все</b-nav-item>
                    <b-nav-item v-for="subdivision in subdivisions" v-bind:key="subdivision.id" v-on:click="changeFilter(subdivision.name)">{{ subdivision.name }} </b-nav-item>
                    <b-nav-item href="/subdivisions" class="mt-5"><b-button variant="outline-primary" >Подробнее о подразделениях</b-button></b-nav-item>
                </b-nav>
            </b-col>
            <b-col offset="2" class="flex-sm-column" >
              <b-alert
                      :show="dismissCountDown"
                      dismissible
                      :variant="isSuccess ? 'success' : 'danger'"
                      @dismissed="dismissCountDown=0"
              >
                <p>{{ alertMessage }}</p>
              </b-alert>
                <b-table hover
                         id="my-table"
                         v-bind:items="sortedItems"
                         v-bind:fields="fields"
                         v-bind:per-page="perPage"
                         v-bind:current-page="currentPage"
                >
                  <template v-slot:cell(fullName)="row">
                    {{ fullName(row.item.surname, row.item.name, row.item.patronymic) }}
                  </template>

                  <template v-slot:cell(show_details)="row">
                    <b-button size="sm" v-on:click="row.toggleDetails" class="mr-2" variant="info">
                      {{ row.detailsShowing ? 'Скрыть' : 'Показать'}} детали
                    </b-button>
                  </template>

                  <template v-slot:cell(actions)="row">
                    <b-button-group class="mx-1">
                      <b-button size="sm"  variant="warning" v-on:click="modalEditShow(row.item)" >
                        <font-awesome-icon icon="edit"></font-awesome-icon>
                      </b-button>
                      <b-button size="sm"  variant="danger" v-on:click="modalDeleteShow(row.item.id)">
                        <font-awesome-icon icon="trash"></font-awesome-icon>
                      </b-button>
                    </b-button-group>
                  </template>

                  <template v-slot:row-details="row">
                    <b-card :img-src="'http://musiclibrary/employees/' + row.item.id +'/photo'" img-alt="Card image" img-left img-width="168">
                      <b-row class="mb-2">
                        <b-col sm="3" class="text-sm-right"><b>ФИО:</b></b-col>
                        <b-col>{{ row.item.surname }} {{ row.item.name }} {{ row.item.patronymic }}</b-col>
                      </b-row>

                      <b-row class="mb-2">
                        <b-col sm="3" class="text-sm-right"><b>Дата рождения:</b></b-col>
                        <b-col>{{ row.item.birthday }}</b-col>
                      </b-row>
                      <b-row class="mb-2">
                        <b-col sm="3" class="text-sm-right"><b>Оклад:</b></b-col>
                        <b-col>{{ row.item.salary }}</b-col>
                      </b-row>
                      <b-row class="mb-2">
                        <b-col sm="3" class="text-sm-right"><b>Ставка:</b></b-col>
                        <b-col>{{ row.item.rate }}</b-col>
                      </b-row>

                    </b-card>
                  </template>
                </b-table>
                <b-pagination
                  v-model="currentPage"
                  :total-rows="rows"
                  :per-page="perPage"
                  aria-controls="my-table"
                  align="right"
                  class="mr-5"
                ></b-pagination>

            </b-col>

        </b-row>
      </template>
      <template v-else>
        <div class="d-flex justify-content-center mb-3">
          <b-spinner  variant="success" label="Spinning"></b-spinner>
        </div>
      </template>
      <b-modal
        title="Удаление"
        ref="deleteEmployeeModal"
        ok-title="Удалить"
        ok-variant="danger"
        @ok="onDeleteClick"
        cancel-title="Отменить"
      >
        Удалить информацию о сотруднике?
      </b-modal>

      <b-modal
        ref="editEmployeeModal"
        id="edit-employee-modal"
        size="lg"
        title="Редактирование"
        ok-title="Применить"
        cancel-title="Отменить"
        @ok="onOkClick"
      >
        <b-container fluid>
          <b-row class="mb-3">
            <b-col cols="3">Фото: </b-col>
            <b-col>
              <b-img :src="editForm.photo" img-left width="168" height="168" thumbnail fluid></b-img>
            </b-col>

          </b-row>
          <b-row class="mb-3">
            <b-col cols="3"></b-col>
            <b-col>
              <b-form-file
                      v-model="editForm.file"
                      v-on:change.prevent="onFileChange($event, editForm)"
                      browse-text="Открыть"
                      placeholder="Изменить фотографию..."
              ></b-form-file>
            </b-col>

          </b-row>
          <b-row class="mb-3">
            <b-col cols="3">ФИО:</b-col>
            <b-col>
              <b-form-input
                v-model="editForm.fullName"
                placeholder="Введите полное имя сотрудника"
                aria-describedby="input-formatter-help"
              ></b-form-input>
            </b-col>
          </b-row>

          <b-row class="mb-3">
            <b-col cols="3">Дата рождения: </b-col>
            <b-col>
              <date-picker v-model="editForm.birthday"  :config="{format: 'YYYY-M-DD'}"></date-picker>
            </b-col>
          </b-row>

          <b-row class="mb-3">
            <b-col cols="4">Подразделение / Должность:</b-col>
            <b-col>
              <b-form-select
                v-model="editForm.subdivision"
                :options="subdivisions"
                value-field="name"
                text-field="name"
              ></b-form-select>
            </b-col>
            <b-col>
              <b-form-select
                v-model="editForm.post"
                :options="posts"
                value-field="name"
                text-field="name"
              ></b-form-select>
            </b-col>
          </b-row>

          <b-row >
            <b-col cols="3">Оклад / Ставка: </b-col>
            <b-col>
              <b-input-group prepend="₽">
                <b-form-input
                  v-model="editForm.salary"
                  type="number"
                  min="0.00"
                  placeholder="Оклад"
                  aria-describedby="input-formatter-help"
                ></b-form-input>
              </b-input-group>
            </b-col>
            <b-col>
              <b-form-input
                v-model="editForm.rate"
                type="number"
                min="0.00"
                placeholder="Ставка"
                aria-describedby="input-formatter-help"
              ></b-form-input>
            </b-col>
          </b-row>

        </b-container>
      </b-modal>
    </b-container>
</div>
</template>

<script>
import axios from 'axios'
import { bus } from '../main'

export default {
  props: ['subdivisions', 'posts'],
  name: 'Home',
  data () {
    return {
      inProgress: true,
      perPage: 10,
      currentPage: 1,
      dismissSecs: 10,
      dismissCountDown: 0,
      alertMessage: 'Информация успешно добавлена!',
      isSuccess: true,
      editForm: {
        birthday: '',
        id: '',
        fullName: '',
        photo: '',
        salary: '',
        rate: '',
        subdivision: '',
        post: '',
        file: null
      },
      items: [],
      currentFilter: null,
      fields: [
        {
          key: 'fullName',
          label: 'Сотрудник'
        },
        {
          key: 'subdivision',
          label: 'Подразделение'
        },
        {
          key: 'post',
          label: 'Должность'
        },
        {
          key: 'show_details',
          label: ''
        },
        {
          key: 'actions',
          label: 'Действия'
        }
      ]
    }
  },
  computed: {
    rows: function () {
      return this.items.length
    },
    sortedItems: function () {
      let newItems = this.items.slice().sort(function (a, b) {
        return (a.surname > b.surname) ? 1 : ((b.surname > a.surname) ? -1 : 0)
      })
      if (this.currentFilter) {
        return newItems.filter((item) => {
          return item.subdivision === this.currentFilter
        })
      } else {
        return newItems
      }
    }
  },
  methods: {
    changeFilter: function (name) {
      this.currentFilter = name
    },
    fullName: function (surname, name, patronymic) {
      let fullName = surname + ' ' + name[0] + '.'
      if (patronymic) {
        fullName += patronymic[0] + '.'
      }
      return fullName
    },
    modalEditShow: function (employee) {
      this.editForm = Object.assign({}, employee)
      this.editForm.fullName = employee.surname + ' ' + employee.name + ' ' + employee.patronymic
      this.editForm.photo = `http://musiclibrary/employees/${employee.id}/photo`
      this.$refs.editEmployeeModal.show()
    },
    modalDeleteShow: function (id) {
      this.deleteId = id
      this.$refs.deleteEmployeeModal.show()
    },
    selectImage: function (form) {
      let reader = new FileReader()
      reader.form = form
      reader.onload = this.onImageLoad
      reader.readAsDataURL(form.file)
    },
    onFileChange: function (event, form) {
      form.file = event.target.files[0]
      this.selectImage(form)
    },
    onImageLoad: function (event) {
      event.target.form.photo = event.target.result
    },
    onOkClick: function () {
      this.inProgress = true
      let promises = []
      if (this.editForm.file) {
        let formData = new FormData()

        formData.append('path', this.editForm.file)
        promises.push(axios.post(`http://musiclibrary/employees/${this.editForm.id}/photo/update`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }))
      }
      promises.push(axios.post(`http://musiclibrary/employees/${this.editForm.id}/update`, {
        surname: this.editForm.fullName.split(' ')[0],
        name: this.editForm.fullName.split(' ')[1],
        patronymic: this.editForm.fullName.split(' ')[2],
        birthday: this.editForm.birthday,
        salary: this.editForm.salary,
        rate: this.editForm.rate,
        subdivison: this.editForm.subdivision,
        post: this.editForm.post
      }))

      axios.all(promises)
        .then(response => {
          this.alertMsg(true, 'Информация о сотруднике успешно обновлена!')
          this.getEmployees()
        })
    },
    onDeleteClick: function () {
      this.inProgress = true
      axios.post(`http://musiclibrary/employees/${this.deleteId}/delete`)
        .then(response => {
          this.alertMsg(false, 'Информация о сотруднике успешно удалена!')
          this.getEmployees()
        })
    },
    getEmployees: function () {
      axios.get('http://musiclibrary/employees')
        .then(response => {
          console.log(response)
          this.inProgress = false
          this.items = response.data.data
        })
    },
    alertMsg: function (type, msg) {
      this.alertMessage = msg
      this.isSuccess = type
      this.dismissCountDown = this.dismissSecs
    }
  },
  mounted: function () {
    this.getEmployees()
    bus.$on('getEmployees', () => {
      this.getEmployees()
      this.alertMsg(true, 'Информация о сотруднике успешно добавлена!')
    })
    bus.$on('Update', () => { this.inProgress = true })
  },
  watch: {
  }
}
</script>

<style scoped lang="less">
.container-fluid.main {
    padding-top: 65px;

    .nav {
        font-weight: bold;

        li.nav-item a {
            color: green;

            &.disabled {
                color: dimgrey;
            }
        }

        span {
          border-bottom: 2px solid #dee2e6;
          border-radius: 1px;
        }

    }
}

.divider {
    border-right: 1px solid #dee2e6;
    border-radius: 1px;
    height: 100%;
}
.spinner-border {
  position: absolute;
  top: 40%;
  width: 5rem;
  height: 5rem;
}
</style>

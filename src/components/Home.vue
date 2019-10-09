<template>
<div>
    <b-container fluid class="main">
        <b-row>
            <b-col cols="2" class="position-fixed divider">
                <b-nav class="flex-sm-column text-center mt-5">
                    <b-nav-item>Подразделение 1</b-nav-item>
                    <b-nav-item disabled>Подразделение 2</b-nav-item>
                    <b-nav-item>Подразделение 3</b-nav-item>
                    <b-nav-item>Подразделение 4</b-nav-item>
                    <b-nav-item href="/subdivisions" class="mt-5"><b-button variant="outline-primary" >Подробнее о подразделениях</b-button></b-nav-item>
                </b-nav>
            </b-col>
            <b-col offset="2" class="flex-sm-column" >
              <template v-if="!inProgress">
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
                      <b-button size="sm"  variant="warning" v-on:click="modalEditShow = !modalEditShow">
                        <font-awesome-icon icon="edit"></font-awesome-icon>
                      </b-button>
                      <b-button size="sm"  variant="danger" v-on:click="modalDeleteShow = !modalDeleteShow">
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
              </template>
              <template v-else>
                <div class="d-flex justify-content-center mb-3">
                  <b-spinner  variant="success" label="Spinning"></b-spinner>
                </div>
              </template>

            </b-col>

        </b-row>
      <b-modal
        title="Удаление"
        v-model="modalDeleteShow"
        ok-title="Удалить"
        ok-variant="danger"
        cancel-title="Отменить"
      >
        Удалить информацию о сотруднике?
      </b-modal>

      <b-modal
        size="lg"
        title="Редактирование"
        v-model="modalEditShow"
        ok-title="Применить"
        cancel-title="Отменить"
      >
        <b-container fluid>
          <b-row class="mb-3">
            <b-col cols="3">Фото: </b-col>
            <b-col>
              <b-img src="https://picsum.photos/300/150/?image=41" thumbnail fluid></b-img>
            </b-col>

          </b-row>
          <b-row class="mb-3">
            <b-col cols="3">ФИО:</b-col>
            <b-col>
              <b-form-input
                placeholder="Введите полное имя сотрудника"
                aria-describedby="input-formatter-help"
              ></b-form-input>
            </b-col>
          </b-row>

          <b-row class="mb-3">
            <b-col cols="3">Дата рождения: </b-col>
            <b-col>
              <date-picker :config="{format: 'DD.M.YYYY'}"></date-picker>
            </b-col>
          </b-row>

          <b-row class="mb-3">
            <b-col cols="4">Подразделение / Должность:</b-col>
            <b-col>
              <b-form-select
                :options="subdivisions"
              ></b-form-select>
            </b-col>
            <b-col>
              <b-form-select
                :options="posts"
              ></b-form-select>
            </b-col>
          </b-row>

          <b-row >
            <b-col cols="3">Оклад / Ставка: </b-col>
            <b-col>
              <b-input-group prepend="₽">
                <b-form-input
                  type="number"
                  min="0.00"
                  placeholder="Оклад"
                  aria-describedby="input-formatter-help"
                ></b-form-input>
              </b-input-group>
            </b-col>
            <b-col>
              <b-form-input
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

export default {
  name: 'Home',
  data () {
    return {
      inProgress: true,
      perPage: 10,
      currentPage: 1,
      modalDeleteShow: false,
      modalEditShow: false,
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
      ],
      items: [],
      subdivisions: ['Подразделение 1', 'Подразделение 2', 'Подразделение 3', 'Подразделение 4'],
      posts: ['Директор', 'Руководитель проекта', 'Старший специалист', 'Специалист']
    }
  },
  computed: {
    rows: function () {
      return this.items.length
    },
    sortedItems: function () {
      return this.items.slice().sort(function (a, b) {
        return (a.surname > b.surname) ? 1 : ((b.surname > a.surname) ? -1 : 0)
      })
    }
  },
  methods: {
    fullName: function (surname, name, patronymic) {
      return surname + ' ' + name[0] + '.' + patronymic[0] + '.'
    }
  },
  mounted: function () {
    axios
      .get('http://musiclibrary/employees')
      .then(response => {
        this.inProgress = false
        this.items = response.data.data
        // this.items.forEach(function (item) {
        //   item.shortName = item.surname + ' ' + item.name[0] + '.' + item.patronymic[0] + '.'
        // })
      })
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
    }
}

.divider {
    border-right: 1px solid #dee2e6;
    border-radius: 1px;
    height: 100%;
}
.spinner-border {
  position: absolute;
  top: 2000%;
  width: 5rem;
  height: 5rem;
}
</style>

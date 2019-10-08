<template>
<div>
    <b-container fluid>
        <b-row>
            <b-col cols="2" class="position-fixed test">
                <b-nav class="flex-sm-column text-center mt-5">
                    <b-nav-item>Подразделение 1</b-nav-item>
                    <b-nav-item disabled>Подразделение 2</b-nav-item>
                    <b-nav-item>Подразделение 3</b-nav-item>
                    <b-nav-item>Подразделение 4</b-nav-item>
                    <b-nav-item href="/subdivisions"><b-button variant="link" >Подробнее о подразделениях</b-button></b-nav-item>
                </b-nav>
            </b-col>
            <b-col offset="2" class="flex-sm-column">

                <b-table hover
                         id="my-table"
                         v-bind:items="items"
                         v-bind:fields="fields"
                         v-bind:per-page="perPage"
                         v-bind:current-page="currentPage"
                >
                    <template v-slot:cell(show_details)="row">
                        <b-button size="sm" v-on:click="row.toggleDetails" class="mr-2" variant="info">
                            {{ row.detailsShowing ? 'Скрыть' : 'Показать'}} детали
                        </b-button>
                    </template>

                    <template v-slot:cell(actions)="row">
                        <b-button size="sm" class="mr-2" variant="warning">
                            <font-awesome-icon icon="edit"></font-awesome-icon>
                        </b-button>
                        <b-button size="sm" class="mr-2" variant="danger">
                            <font-awesome-icon icon="trash"></font-awesome-icon>
                        </b-button>
                    </template>

                    <template v-slot:row-details="row">
                        <b-card img-src="https://pbs.twimg.com/profile_images/771151978634153985/3JSf8oYk_400x400.jpg" img-alt="Card image" img-left>
                            <b-row class="mb-2">
                                <b-col sm="3" class="text-sm-right"><b>ФИО:</b></b-col>
                                <b-col>{{ row.item.name }}</b-col>
                            </b-row>

                            <b-row class="mb-2">
                                <b-col sm="3" class="text-sm-right"><b>Дата рождения:</b></b-col>
                                <b-col>{{ row.item.birthday }}</b-col>
                            </b-row>
                            <b-row class="mb-2">
                                <b-col sm="3" class="text-sm-right"><b>Ставка:</b></b-col>
                                <b-col>{{ row.item.rate }}</b-col>
                            </b-row>
                            <b-row class="mb-2">
                                <b-col sm="3" class="text-sm-right"><b>Оклад:</b></b-col>
                                <b-col>{{ row.item.salary }}</b-col>
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
    </b-container>
</div>
</template>

<script>
export default {
  name: 'Home',
  data () {
    return {
      perPage: 10,
      currentPage: 1,
      fields: [
        {
          key: 'name',
          label: 'Сотрудник'
        },
        {
          key: 'place',
          label: 'Подразделение'
        },
        {
          key: 'job',
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
      items: [
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 },
        { name: 'Иванов И.И.', place: 'Подразделение 1', job: 'Директор', birthday: '07-12-2019', rate: 1, salary: 250000 }
      ]
    }
  },
  computed: {
    rows () {
      return this.items.length
    }
  }
}
</script>

<style scoped lang="less">
.container-fluid {
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

.test {
    border-right: 1px solid #dee2e6;
    border-radius: 1px;
    height: 100%;
}
</style>

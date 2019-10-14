<!-- eslint-disable -->
<template>
  <div class="main">

    <header>
      <b-navbar type="light" variant="light" fixed="top">
        <b-navbar-brand href="/" class="navbar-brand">
          <font-awesome-icon icon="bookmark" class="bookmark"/>
          Справочник
        </b-navbar-brand>

        <b-navbar-nav class="ml-auto">

          <b-button pill variant="success" v-on:click="modalAddShow">Добавить сотрудника</b-button>

        </b-navbar-nav>
      </b-navbar>
    </header>

    /*---------------------- Модальное окно добавления сотрудника ---------------------------*/
    <b-modal
      ref="addEmployeeModal"
      id="add-employee-modal"
      size="lg"
      title="Редактирование"
      ok-title="Добавить"
      cancel-title="Отменить"
      @ok="onOkClick"
    >
      <b-container fluid>
        <b-row class="mb-3">
          <b-col cols="3">Фото:</b-col>
          <b-col v-if="addForm.photo != ''">
            <b-img :src="addForm.photo" img-left width="168" height="168" thumbnail fluid></b-img>
          </b-col>

        </b-row>
        <b-row class="mb-3">
          <b-col cols="3"></b-col>
          <b-col>
            <b-form-file
              v-model="addForm.file"
              v-on:change.prevent="onFileChange($event, addForm)"
              browse-text="Открыть"
              placeholder="Добавить фотографию..."
            ></b-form-file>
          </b-col>

        </b-row>
        <b-row class="mb-3">
          <b-col cols="3">ФИО:</b-col>
          <b-col>
            <b-form-input
              :state="null"
              v-model="addForm.fullName"
              placeholder="Введите полное имя сотрудника"
              aria-describedby="input-formatter-help"
              @change="$v.fullName.$touch()"
            ></b-form-input>
          </b-col>
        </b-row>

        <b-row class="mb-3">
          <b-col cols="3">Дата рождения:</b-col>
          <b-col>
            <date-picker v-model="addForm.birthday" :config="{format: 'YYYY-M-DD'}" @change="$v.birthday.$touch()"></date-picker>
          </b-col>
        </b-row>

        <b-row class="mb-3">
          <b-col cols="4">Подразделение / Должность:</b-col>
          <b-col>
            <b-form-select
              v-model="addForm.subdivision"
              :options="subdivisions"
              value-field="name"
              text-field="name"
              @change="$v.subdivision.$touch()"
            ></b-form-select>
          </b-col>
          <b-col>
            <b-form-select
              v-model="addForm.post"
              :options="posts"
              value-field="name"
              text-field="name"
              @change="$v.post.$touch()"
            ></b-form-select>
          </b-col>
        </b-row>

        <b-row>
          <b-col cols="3">Оклад / Ставка:</b-col>
          <b-col>
            <b-input-group prepend="₽">
              <b-form-input
                v-model="addForm.salary"
                type="number"
                min="0.00"
                placeholder="Оклад"
                aria-describedby="input-formatter-help"
                @change="$v.salary.$touch()"
              ></b-form-input>
            </b-input-group>
          </b-col>
          <b-col>
            <b-form-input
              v-model="addForm.rate"
              type="number"
              min="0.00"
              placeholder="Ставка"
              aria-describedby="input-formatter-help"
              @change="$v.post.$touch()"
            ></b-form-input>
          </b-col>
        </b-row>

      </b-container>
    </b-modal>
    <router-view :subdivisions="subdivisions" :posts="posts"/>

  </div>
</template>

<script>
/* eslint-disable*/
import axios from 'axios'
import { required, between, numeric, decimal } from 'vuelidate/lib/validators'
import {bus} from './main'

export default {
    data() {
        return {
            addForm: {
                birthday: '',
                fullName: '',
                photo: '',
                salary: '',
                rate: '',
                subdivision: '',
                post: '',
                file: null
            },
            subdivisions: [],
            posts: [],
            validations: {
                birthday: {
                    required
                },
                fullName: {
                    required
                },
                photo: {
                    required
                },
                salary: {
                    required,
                    numeric
                },
                rate: {
                    required,
                    decimal,
                    between: between(0, 1)
                },
                subdivision: {
                    required
                },
                post: {
                    required
                }
            }

        }
    },
    methods: {
        fullName: function (surname, name, patronymic) {
            return surname + ' ' + name[0] + '.' + patronymic[0] + '.'
        },
        modalAddShow: function () {
            this.addForm.birthday = ''
            this.addForm.fullName = ''
            this.addForm.photo = ''
            this.addForm.salary = ''
            this.addForm.rate = ''
            this.addForm.subdivision = ''
            this.addForm.post = ''
            this.addForm.file = null
            this.$refs.addEmployeeModal.show()
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

            this.$v.$touch()
            if (!this.$v.$invalid) {
                bus.$emit('Update')

                let data = JSON.stringify({
                    surname: this.addForm.fullName.split(' ')[0],
                    name: this.addForm.fullName.split(' ')[1],
                    patronymic: this.addForm.fullName.split(' ')[2],
                    birthday: this.addForm.birthday,
                    salary: this.addForm.salary,
                    rate: this.addForm.rate,
                    subdivison: this.addForm.subdivision,
                    post: this.addForm.post
                })

                axios.post('http://musiclibrary/employees/add', data, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {

                        let id = response.data.data[0]
                        if (this.addForm.file) {
                            let formData = new FormData()

                            formData.append('path', this.addForm.file)
                            axios.post(`http://musiclibrary/employees/${id}/photo/update`, formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                                .then(response => {
                                    console.log('ID')
                                    bus.$emit('getEmployees')
                                })
                                .catch(response => {
                                    console.log(response)
                                })
                        } else {
                            bus.$emit('getEmployees')
                        }
                    })
              }
        }


        //     bus.$emit('Update')
        //
        //     let data = JSON.stringify({
        //         surname: this.addForm.fullName.split(' ')[0],
        //         name: this.addForm.fullName.split(' ')[1],
        //         patronymic: this.addForm.fullName.split(' ')[2],
        //         birthday: this.addForm.birthday,
        //         salary: this.addForm.salary,
        //         rate: this.addForm.rate,
        //         subdivison: this.addForm.subdivision,
        //         post: this.addForm.post
        //     })
        //
        //     axios.post('http://musiclibrary/employees/add', data, {
        //         headers: {
        //             'Content-Type': 'application/json'
        //         }
        //     })
        //         .then(response => {
        //
        //             let id = response.data.data[0]
        //             if (this.addForm.file) {
        //                 let formData = new FormData()
        //
        //                 formData.append('path', this.addForm.file)
        //                 axios.post(`http://musiclibrary/employees/${id}/photo/update`, formData, {
        //                     headers: {
        //                         'Content-Type': 'multipart/form-data'
        //                     }
        //                 })
        //                     .then(response => {
        //                         console.log('ID')
        //                         bus.$emit('getEmployees')
        //                     })
        //                     .catch(response => {
        //                         console.log(response)
        //                     })
        //             } else {
        //                 bus.$emit('getEmployees')
        //             }
        //         })
        // }
    },
    mounted() {
        console.log(this.$v)
        axios
            .get('http://musiclibrary/subdivisions')
            .then(response => {
                this.subdivisions = response.data.data.subdivisions
                this.posts = response.data.data.posts
            })
        bus.$on('updateSubdivisions', () => {
            axios
                .get('http://musiclibrary/subdivisions')
                .then(response => {
                    this.subdivisions = response.data.data.subdivisions
                })
        })
    }
}
</script>
<style lang="less">
  .navbar-brand {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    font-weight: bold;
  }

  #nav {
    padding: 30px;

    a {
      font-weight: bold;
      color: #2c3e50;

      &.router-link-exact-active {
        color: #42b983;
      }
    }
  }

  * {
    margin: 0;
    padding: 0;
  }

  html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    background-color: #f5f5f5;
  }

  .bookmark {
    color: #42b983;
  }

  .main {
    width: 100%;
    height: 100%;
    background-color: #f5f5f5;
  }
</style>


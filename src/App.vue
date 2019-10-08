<template>
  <div class="main">
    <header>
      <b-navbar type="light" variant="light" fixed="top">
        <b-navbar-brand href="/" class="navbar-brand">
          <font-awesome-icon icon="bookmark" class="bookmark"/>
          Справочник
        </b-navbar-brand>
        <b-navbar-nav class="ml-5">
          <b-form-input size="sm" class="mr-sm-2" placeholder="Поиск"></b-form-input>
          <b-button size="sm" class="my-2 my-sm-0" type="submit">Поиск</b-button>
        </b-navbar-nav>
        <b-navbar-nav class="ml-auto">

          <b-button pill variant="success">Добавить сотрудника</b-button>

        </b-navbar-nav>
      </b-navbar>
    </header>

    <router-view/>
  </div>
</template>

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
<script>
</script>

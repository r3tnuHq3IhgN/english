<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <form class="dropdown">
              <div class="form-group">
                <label for="search">Search</label>
                <input type="text" class="form-control" id="search" v-model="search" @input="searchWord" @keydown="searchWord">
                <div v-if="words" class="dropdown-content active">
                  <a v-for="word in words" :key="word._id" @click="selectItem(result)">
                    {{ word._source.eng }}: {{ word._source.vi }}
                  </a>
                </div>
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
export default {
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {
      search: '',
      words: null
    }
  },
  methods: {
    searchWord() {
      this.words = null;
      if (this.search.length > 0) {
        axios.post('/api/search-word-with-elastic', {
          query: this.search
        })
          .then(response => {
            this.words = response.data.original

            console.log(response.data.original)
          })
          .catch(error => {
            console.log(error)
          })
      }
      else {
        this.words = null;
        this.search = '';
      }
    }
  },
}
</script>
<style>
/* Styling cho dropdown */
.dropdown {
  position: relative;
}
.dropdown-content {
  display: block;
  position: relative;
  background-color: #f9f9f9;
  min-width: 160px;
  border: 1px solid #ddd;
  z-index: 1;
}
.dropdown-content a {
  color: black;
  padding: 8px 16px;
  text-decoration: none;
  display: block;
}
.dropdown-content a:hover {
  background-color: #f1f1f1;
}
.dropdown-content {
  display: none;
}
.dropdown-content.active {
  display: block;
}
</style>

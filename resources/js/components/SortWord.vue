<template>
  <div class="mt-6">
    <h2>Danh sách các từ mời theo ngày</h2>
    <select
        class="form-select mb-3"
        v-model="filter.date"
        @change="getWords"
    >
      <option v-for="(date, index) in dates" :key="index">
        {{ date }}
      </option>
    </select>
    <select
        class="form-select mb-3"
        v-model="filter.type"
        @change="getWords"
    >
      <option value="sort2" selected>Theo thứ tự Alphabe</option>
      <option value="sort1">Theo thứ tự được thêm vào</option>
    </select>
    <table class="table table-bordered table-striped" v-show="word1 != null">
      <thead class="thead-dark">
      <tr>
        <th scope="col" class="col-md-2">English</th>
        <th scope="col" class="col-md-1">Type</th>
        <th scope="col" class="col-md-4">Vietnamese</th>
        <th scope="col" class="col-md-5">Example</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(item, index) in word1" :key="index">

        <th scope="row">{{ item.eng }}</th>
        <td>( {{ item.type }} )</td>
        <td>{{ item.vi }}</td>
        <td v-html="item.example"></td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    dates: Array,
    filter: Object,
    word1: Array,
  },
  methods: {
    getWords() {
      this.$emit("getWords", this.filter.date, this.filter.type);
    },
  },
};
</script>

<style scoped>
label {
  font-weight: bold;
}
th,
tr,
td {
  text-align: center;
  vertical-align: middle;
}
td > p {
  margin: 0px auto;
}
/* Các kiểu riêng cho component này nếu cần */
</style>


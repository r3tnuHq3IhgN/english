<template>
  <div class="mt-6">
    <h2>Tất cả các từ mới (<span style="color: red">{{ count_w }}</span>)</h2>
    <select
        class="form-select mb-3"
        v-model="type_"
        @change="getAllWords"
    >
      <option value="sort2" selected>Theo thứ tự Alphabe</option>
      <option value="sort1">Theo thứ tự được thêm vào</option>
    </select>
    <table class="table table-bordered table-striped" v-show="word2 != null">
      <thead class="thead-dark">
      <tr>
        <th scope="col" class="col-md-2">English</th>
        <th scope="col" class="col-md-1">Type</th>
        <th scope="col" class="col-md-4">Vietnamese</th>
        <th scope="col" class="col-md-5">Example</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(item, index) in word2" :key="index">
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
    count_w: Number,
    word2: Array,
  },
  data() {
    return {
      type_: 'sort1',
    };
  },
  methods: {
    getAllWords() {
      console
      this.$emit("getAllWords", this.type_);
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

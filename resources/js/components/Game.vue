<template>
  <div class="container">
    <h2>Kiểm tra từ mới</h2>
    <div class="header">
      <div class="btn btn-primary" @click="choose = 1">Theo ngày</div>
      <div class="btn btn-danger" @click="choose = 2">Ngẫu nhiên</div>
    </div>
    <div v-if="choose == 1">
      <div class="mb-6">
        <select
          class="form-select mb-3"
          aria-label="Default select example"
          v-model="date"
        >
          <option v-for="(item, index) in dates" :key="index">
            {{ item }}
          </option>
        </select>
        <div
          class="btn btn-primary"
          @click="getWords(base_url)"
          v-if="summary == false"
        >
          Start
        </div>
        <div
          class="btn btn-danger"
          @click="getWords(base_url)"
          v-if="summary == true"
        >
          New test
        </div>
      </div>

      <div v-if="summary == false">
        <div class="mt-6" v-for="(w, index) in words" :key="index">
          <div v-if="index == count">
            <div class="main_w btn btn-outline-danger d-block">
              <span>{{ w.eng }} ({{ w.type }})</span>
            </div>
            <input
              type="text"
              class="form-control mt-3 w-50 m-auto answer"
              v-model="answer"
              :disabled="next == true"
              @keyup.enter="checkResult(w.eng, w.type, w.vi)"
            />
            <div
              class="btn btn-primary mt-3 result_ d-block"
              v-if="next != true"
              @click="checkResult(w.eng, w.type, w.vi)"
            >
              Kiểm tra
            </div>
            <div
              class="btn btn-success mt-3 result_ d-block"
              v-if="next == true && count != num - 1"
              @click="nextQues"
            >
              Câu tiếp
            </div>
            <div
              class="btn btn-info mt-3 result_ d-block"
              v-if="count == num - 1 && next == true"
              @click="summary = true"
            >
              Kết quả
            </div>
            <div v-if="show == true" class="result_">
              <div class="btn btn-success mt-3" v-if="re_ans == true">True</div>
              <div class="btn btn-danger mt-3" v-if="re_ans == false">False</div>
            </div>
          </div>
        </div>
      </div>
      <br />
      <table
        class="table table-bordered table-striped mt-6"
        v-if="summary == true"
      >
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="col-md-2">English</th>
            <th scope="col" class="col-md-2">Type</th>
            <th scope="col" class="col-md-3">Vietnamese</th>
            <th scope="col" class="col-md-3">Answer</th>
            <th scope="col" class="col-md-2">Result</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in result" :key="index">
            <th scope="row">{{ item[0] }}</th>
            <td>( {{ item[1] }} )</td>
            <td>{{ item[2] }}</td>
            <td>{{ item[3] }}</td>
            <td v-if="item[4] == false">
              <div class="btn btn-danger">False</div>
            </td>
            <td v-if="item[4] == true">
              <div class="btn btn-success">True</div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="choose == 2">
      <div class="mb-6">
        <select
          class="form-select mb-3"
          aria-label="Default select example"
          v-model="num_word"
        >
          <option v-for="(item, index) in num_of_word" :key="index">
            {{ item }}
          </option>
        </select>
        <div
          class="btn btn-primary"
          @click="getWordsRan()"
          v-if="summary == false"
        >
          Start
        </div>
        <div
          class="btn btn-danger"
          @click="getWordsRan()"
          v-if="summary == true"
        >
          New test
        </div>
      </div>

      <div v-if="summary == false">
        <div class="mt-6" v-for="(w, index) in words" :key="index">
          <div v-if="index == count">
            <div class="main_w btn btn-outline-danger d-block">
              <span>{{ w.eng }} ({{ w.type }})</span>
            </div>
            <input
              type="text"
              class="form-control mt-3 w-50 m-auto answer"
              v-model="answer"
              :disabled="next == true"
              @keyup.enter="checkResult(w.eng, w.type, w.vi)"
            />
            <div
              class="btn btn-primary mt-3 result_ d-block"
              v-if="next != true"
              @click="checkResult(w.eng, w.type, w.vi)"
            >
              Kiểm tra
            </div>
            <div
              class="btn btn-success mt-3 result_ d-block"
              v-if="next == true && count != num - 1"
              @click="nextQues"
            >
              Câu tiếp
            </div>
            <div
              class="btn btn-info mt-3 result_ d-block"
              v-if="count == num - 1 && next == true"
              @click="summary = true"
            >
              Kết quả
            </div>
            <div v-if="show == true" class="result_">
              <div class="btn btn-success mt-3" v-if="re_ans == true">True</div>
              <div class="btn btn-danger mt-3" v-if="re_ans == false">False</div>
            </div>
          </div>
        </div>
      </div>
      <br />
      <table
        class="table table-bordered table-striped mt-6"
        v-if="summary == true"
      >
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="col-md-2">English</th>
            <th scope="col" class="col-md-2">Type</th>
            <th scope="col" class="col-md-3">Vietnamese</th>
            <th scope="col" class="col-md-3">Answer</th>
            <th scope="col" class="col-md-2">Result</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in result" :key="index">
            <th scope="row">{{ item[0] }}</th>
            <td>( {{ item[1] }} )</td>
            <td>{{ item[2] }}</td>
            <td>{{ item[3] }}</td>
            <td v-if="item[4] == false">
              <div class="btn btn-danger">False</div>
            </td>
            <td v-if="item[4] == true">
              <div class="btn btn-success">True</div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
export default {
  mounted() {
    this.getDates();
  },
  props: {
    user_id: Number,
  },
  data() {
    return {
      choose: 1,
      dates: null,
      date: null,
      words: null,
      answer: null,
      re_ans: null,
      show: false,
      count: 0,
      num: null,
      next: null,
      result: [],
      summary: false,
      base_url: "/api/get-rand-words",
      num_of_word: [10, 20, 30, 50, 100],
      num_word: 10,
    };
  },
  methods: {
    getDates() {
      axios
        .get("/api/get-dates", {
          params: {
            uid: this.user_id,
          }
        })
        .then((res) => {
          if (res.data != null) {
            this.dates = res.data;
            this.date = this.dates[this.dates.length - 1];
          }
          //console.log(res);
        })
        .catch((err) => {
          console.error(err);
        });
    },
    getWords(url) {
      this.show = false;
      this.next = false;
      this.summary = false;
      this.count = 0;
      this.answer = null;
      this.result = [];
      axios
        .get(url, {
          params: {
            uid: this.user_id,
            date: this.date,
          },
        })
        .then((res) => {
          this.words = res.data.words;
          this.num = res.data.count;
        })
        .catch((err) => {
          console.error(err);
        });
    },
    getWordsRan(){
      this.show = false;
      this.next = false;
      this.summary = false;
      this.count = 0;
      this.answer = null;
      this.result = [];
      axios
        .get("/api/get-words-random", {
          params: {
            uid: this.user_id,
            num: this.num_word,
          },
        })
        .then((res) => {
          this.words = res.data.words;
          this.num = this.num_word;
        })
        .catch((err) => {
          console.error(err);
        });
    },
    checkResult(eng, type, vi) {
      this.re_ans = vi.includes(this.answer);
      this.show = true;
      this.result.push([eng, type, vi, this.answer, this.re_ans]);
      this.next = true;
      //console.log(this.re_ans);
    },
    nextQues() {
      this.count += 1;
      this.show = false;
      this.next = false;
      this.answer = null;
    },
  },
};
</script>
<style scoped>
.header {
  margin-bottom: 20px;
}
.main_w {
  border: 1px solid #bababa;
  border-radius: 15px;
  width: 200px;
  height: 50px;
  text-align: center;
  vertical-align: super;
  margin: 0px auto;
}

.main_w > span {
  margin: 20px;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
.result_ {
  width: 100px;
  margin: 10px auto;
}
.wrap_ {
  display: flex;
}
.answer {
  text-align: center;
}
label {
  font-weight: bold;
}
th,
tr,
td {
  text-align: center;
  vertical-align: middle;
}
p {
  margin-top: 1rem;
}
</style>
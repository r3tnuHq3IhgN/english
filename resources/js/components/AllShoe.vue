<template>
    <div class="container">
      <h2>Tất cả các giày</h2>
      <table class="table table-bordered table-striped" v-if="shoes">
        <thead class="thead-dark">
          <tr>
              <th scope="col" class="col-md-2">Tên giày</th>
              <th scope="col" class="col-md-1">Size</th>
              <th scope="col" class="col-md-4">Giá</th>
              <th scope="col" class="col-md-5">Ảnh</th>
              <th scope="col" class="col-md-5">Hoạt động</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(shoe, index) in shoes" :key="index">
            <th scope="row">{{ shoe.name }}</th>
            <td>{{ shoe.size }}</td>
            <td>{{  shoe.price }}</td>
            <td id="images">
              <img :src="shoe.images[0].image_url" style="width: 100px; height: 100px;" alt="">

            </td>
            <td>
              <div class="btn btn-danger" @click="deleteItem(shoe.id)">Xóa</div>
            </td>
          </tr>
        </tbody>
       </table>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    props: {
      user_id: {
          type: Number
      },
    },
    mounted: function() {
      this.getAllShoes();
    },
    data() {
    return {
      shoes: [],
        
    };
    },
    methods: {
      getAllShoes() {
        axios.get('/api/shoe/get-all-shoes', {
          params: {
            uid: this.user_id
          }
        }).then(response => {
          this.shoes = response.data;
          console.log(response.data);
        }).catch(error => {
          console.log(error);
        });
      },
      deleteItem(shoe_id) {
        alert('Bạn có chắc chắn muốn xóa giày này không?');
        axios.delete('/api/shoe/delete-shoe', {
          params: {
            uid: this.user_id,
            shoe_id: shoe_id
          }
        }).then(response => {
          this.getAllShoes();
          console.log(response.data);
        }).catch(error => {
          console.log(error);
        });
      }
    },
};
</script>
<style scoped>
label {
  font-weight: bold;
}
th,
tr,
td :not(#images) {
  text-align: center;
  vertical-align: middle;
}
td > p {
  margin: 0px auto;
}
/* Các kiểu riêng cho component này nếu cần */
</style>


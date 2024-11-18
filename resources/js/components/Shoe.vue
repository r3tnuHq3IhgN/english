<template>
    <div class="container">
        <div class="header row">
            <div class="btn btn-primary col-sm-2 col-3" @click="choose = 1">Thêm giày mới</div>
            <div class="btn btn-success col-sm-2 col-3" @click="choose = 2">Xem toàn bộ giày</div>
            <div class="btn btn-danger col-sm-2 col-3" @click="choose = 3">Tìm kiếm</div>
            <!-- <div class="btn btn-warning col-sm-2 col-3" @click="choose = 4">Chỉnh sửa giày</div> -->
        </div>
        <add-shoe 
            v-if="choose === 1" 
            :user_id="user_id"
        />
        <all-shoe 
            v-if="choose === 2" 
            :user_id="user_id"
        />
    </div>
</template>

<script>
    export default {
        props: {
            user_id: {
                type: Number,
                required: true,
            },
        },
        data() {
            return {
                choose: 2,
            };
        },
        methods: {
            submitForm() {
                console.log('submit form');
            },
            resetForm() {
                console.log('reset form');
            },
            getWords() {
                console.log('get words');
            },
            getAllWords() {
                console.log('get all words');
            },
        },
    };

</script>
<style scoped>
    .header {
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .btn {
        margin-right: 10px;
        margin-left: 10px;
    }
</style>
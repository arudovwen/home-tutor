<template>
  <div class="container">
    <form @submit.prevent="register">
       <b-form-group label="Category" v-if="admin">
            <b-form-select class="toCaps" v-model="tutor.level">
              <b-form-select-option  class="toCaps" :value="level" v-for="(level,idx) in JSON.parse(admin.level)"  :key="idx">{{level.toLowerCase()}}</b-form-select-option>
            </b-form-select>
          </b-form-group>
      <div class="form-group">
        <label for>Name</label>
        <input
          type="text"
          class="form-control"
          name="name"
          id="name"
          v-model="tutor.name"
          aria-describedby="helpId"
          placeholder="John Doe"
        />
      </div>
      <div class="form-group">
        <label for>Email</label>
        <input
          type="email"
          class="form-control"
          name="email"
          id="email"
          v-model="tutor.email"
          aria-describedby="helpId"
          placeholder="example@email.com"
        />
      </div>

      <b-form-group>
        <label for>Gender</label>

        <b-form-select v-model="tutor.gender" :options="options"></b-form-select>
      </b-form-group>

     
      <b-form-group class="mb-3">
        <b-row>
          <b-col>
            <b-button variant="outline-secondary" @click="toggleModal" block type="button">Cancel</b-button>
          </b-col>
          <b-col>
            <b-button variant="success" block type="submit">Register</b-button>
          </b-col>
        </b-row>
      </b-form-group>
    </form>
  </div>
</template>


<script>
export default {
  data() {
    return {
      subjects:[],
      tutor: {
        name: "",
        email: "",
        gender: "",
        subject: "null",
        level:'',
      },
      admin:{},
      options: [
        { value: "", text: "Select an option", disabled: true },
        { value: "male", text: "Male" },
        { value: "female", text: "Female" },
      ],

      spin: false,
    };
  },
  created() {
    this.getSubjects()
       this.admin = JSON.parse(localStorage.getItem("typeAdmin"));
  },
  methods: {
    toggleModal() {
      this.$emit("toggleModal", "tutor-create");
    },
      getSubjects() {
         let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      axios
        .get("/api/subject", {
          headers: {
            Authorization: `Bearer ${admin.access_token}`
          }
        })
        .then(res => {
          if (res.status == 200) {
            this.subjects = res.data;
          }
        });
    },
    register() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      axios
        .post("/api/tutor", this.tutor, {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.$toasted.info("Successful");
            this.toggleModal();
               this.$emit('update')
          }
        });
    },
  },
};
</script>

<style scoped>
.container {
}
form {
  width: 100%;
}
.form-group {
  margin-bottom: 24px;
}
</style>
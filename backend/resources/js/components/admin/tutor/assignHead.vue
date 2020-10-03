<template>
  <div class="body p-3">
  
    <b-row class=" mb-5">
      <b-col cols="12" class="mb-3">
       
        <b-form-group  label="Choose Lecturer">
          <b-form-select v-model="selected">
            <b-form-select-option  v-for="(tutor,idx) in tutors" :key="idx" :value="tutor">{{tutor.name}}</b-form-select-option>
          </b-form-select>
        </b-form-group>
      </b-col>
      <b-col cols="12" class="">
        <b-form-group label="Levels">
          <b-form-row>
            <b-col cols="4" v-for="(item,idx) in classes" :key="idx">
              <b-form-checkbox
                v-model="data.class.id"
                :value="item.id"
              >{{item.class_name}}</b-form-checkbox>
            </b-col>
          </b-form-row>
        </b-form-group>
      </b-col>
    </b-row>
    <div class="my-3">
      <b-button type="button" @click="submit">Submit</b-button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["admin"],
  data() {
    return {
      tutors: [],
      active:'',
      classes: [],
      heads: [],
      selected:{},
      data: {
        tutor: {
          id: "",
          name: "",
        },
        class: {
          id: "",
          name: "",
        },
      },
    };
  },
  mounted() {
    this.getTutors();
    this.getClasses();
    this.getHeads();
  },
  methods: {
    submit() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
       this.data.tutor.name = this.selected.name;
      this.data.tutor.id =  this.selected.id;
    if (this.data.tutor.id!=='') {
        axios
        .post("/api/tutor-class", this.data, {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.$toasted.info("Successful");
            this.$emit("update");
          } else if (res.status == 200) {
              this.$emit("update");
            this.$toasted.info("Updated");
          }
        });
    }
    else {
        this.$toasted.info("Select Tutor");
      }
    },
    selectSubject(id, name) {
      if (this.data.tutor.name !== "") {
        let data = {
          id: id,
          name: name,
        };
        this.data.class.name = name;
        this.data.class.id = id;
      } else {
        this.$toasted.info("Select Tutor");
      }
    },
    selectTutor(id, name) {
      this.data.tutor.name = name;
      this.data.tutor.id = id;

    },
    getHeads() {
      axios
        .get("/api/tutor-class", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.heads = res.data.data;
          }
        });
    },
    getTutors() {
      axios
        .get("/api/tutor", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.tutors = res.data;
          }
        });
    },
    getClasses() {
      axios
        .get("/api/classes", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.classes = res.data;
          }
        });
    },
  },
};
</script>
<style scoped>
.active{
  background:green;
  color:white;
}
</style>
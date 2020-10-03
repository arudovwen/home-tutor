<template>
  <div class="body p-3">
    <div class="d-flex">
      <div class="w-25 mr-3">
       
        <b-form-group :label="JSON.parse(admin.level).includes('tertiary')?'Lecturers List':'Teachers List'">
          <b-form-select v-model="tutor.id">
            <b-form-select-option value="select">Select</b-form-select-option>
            <b-form-select-option :value="item.id" v-for="(item,idx) in tutors" :key="idx">{{item.name}}</b-form-select-option>
          </b-form-select>
        </b-form-group>
      </div>
      <div class="w-75">
       

       <b-form-group label="Subjects List">
          <b-form-row>
         <b-col cols="4" v-for="(item,idx) in subjects" :key="idx">
           <b-form-checkbox v-model="tutor.choosen_course" :value="item.name.toLowerCase()">{{item.name}}</b-form-checkbox>
         </b-col>
        </b-form-row>
       </b-form-group>
      </div>

    
    </div>
       <div class="my-3" >
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
      subjects: [],
      
        tutor: {
          id: "select",
          choosen_course: []
        }
      
    };
  },
  mounted() {
    this.getTutors();
    this.getSubjects();
  },
  methods: {
    submit() {
        let admin = JSON.parse(localStorage.getItem('typeAdmin'))
      axios.post("/api/tutor-course", this.tutor,{headers:{
          Authorization: `Bearer ${admin.access_token}`
      }}).then(res => {
        if (res.status == 201) {
          this.$toasted.info("Successful");
         
          this.$emit('update')
        }else if(res.status == 200){
              this.$toasted.info("Updated")
                 this.$emit('update')
        }
        this.tutor = {
          id:'',
          choosen_course:[]
        }
      });
    },
    selectSubject(id, name) {
      if (this.data.tutor.name !== "") {
        let data = {
          id: id,
          name: name
        };
        let arr = [];
        this.data.choosen_course.forEach(item => {
          arr.push(item.id);
        });
        let presence = arr.includes(id);

        if (!presence) {
          this.data.choosen_course.push(data);
        } else {
          this.$toasted.error("Already added");
        }
      } else {
        this.$toasted.info("Select Tutor");
      }
    },
    selectTutor(id, name) {
      this.data.tutor.name = name;
      this.data.tutor.id = id;
    },
    getTutors() {
      axios
        .get("/api/tutor", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`
          }
        })
        .then(res => {
          if (res.status == 200) {
            this.tutors = res.data;
          }
        });
    },
    getSubjects() {
      axios
        .get("/api/subject", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`
          }
        })
        .then(res => {
          if (res.status == 200) {
            this.subjects = res.data;
          }
        });
    }
  }
};
</script>
<style scoped>
</style>
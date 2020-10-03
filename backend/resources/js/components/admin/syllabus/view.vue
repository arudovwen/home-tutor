<template>
  <div class="body">
      <h3 class="text-center my-3">Syllabus</h3>
    <!-- form starts here  -->
   
    <b-container >
       <b-row class="border p-3 my-4">
      <b-col cols="12" class="curriculum-header">
        <span><i class="fa fa-users"></i></span><h5>Class Information</h5>
      </b-col>
      <b-col cols="6" class="">
        <h6 class="toCaps">      
         <span class="text-muted">Term :</span> {{syllabus.term}}
        </h6>
      </b-col>
      <b-col cols="6"  class="">
        <h6 class="toCaps">
          <span class="text-muted">Grade Level :</span>
          {{syllabus.grade_level}}
        </h6>
      </b-col>
    </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
         <div class="curriculum-header">
           <span><i class="fa fa-book"></i></span> <h5>Subject Information</h5>
         </div>

          <div class="">
            <h6 class="toCaps">
              <span class="text-muted">Subject :</span>
              {{syllabus.subject}}
            </h6>
          </div>

          <div class="">
            <label for>Description :</label>
            {{syllabus.description}}
          </div>

          <div class="">
            <label for>Frequency :</label>
            {{syllabus.frequency}}
          </div>
        </b-col>
      </b-row>

      <b-row class="border p-3 my-4">
        <b-col>
          <div class="curriculum-header">
            <span><i class="fa fa-leanpub"></i></span><h5>Overarching Learner Outcome</h5>
          </div>

          <div class="">
            <span v-for="(item,idx) in syllabus.learner_outcome" :key="idx" class="mb-2 d-flex">
              <span class="mr-3">{{idx+1}}.</span>
              {{item.name}}
            </span>
          </div>
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
         <div class="curriculum-header">
           <span><i class="fa fa-bullseye"></i></span> <h5>Target skills to be learned</h5>
         </div>
          <span v-for="(item,idx) in syllabus.target_skills" :key="idx" class="mb-2 d-flex">
            <span class="mr-3">{{idx+1}}.</span>
            {{item.name}}
          </span>
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
          <div class="curriculum-header">
            <span><i class="fa fa-handshake-o"></i></span><h5>Required Modules</h5>
          </div>

          <span v-for="(item,idx) in syllabus.modules" :key="idx" class="mb-2 d-flex">
            <span class="mr-3">{{idx+1}}.</span>
            {{item.name}}
          </span>
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
          <div class="curriculum-header">
            <span><i class="fa fa-puzzle-piece"></i></span><h5>Delivery Methods</h5>
          </div>

          <span v-for="(item,idx) in syllabus.delivery_methods" :key="idx" class="mb-2 d-flex">
            <span class="mr-3">{{idx+1}}.</span>
            {{item.name}}
          </span>
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
          <div class="curriculum-header">
            <span><i class="fa fa-pie-chart"></i></span> <h5>Assessment</h5>
          </div>
          <small id="helpId" class="form-text text-muted">How will the students be assessed?</small>

          <span v-for="(item,idx) in syllabus.assessments" :key="idx" class="mb-2 d-flex">
            <span class="mr-3">{{idx+1}}.</span>
            {{item.name}}
          </span>
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
         <div class="curriculum-header">
            <span><i class="fa fa-question-circle"></i></span><h5>FAQs (Optional)</h5>
         </div>

          <div v-for="(item,idx) in syllabus.faqs" :key="idx" class="mb-3">
            <span class="d-flex mb-2">
              <label for="Question" class="side-label">Question</label>
              {{item.question}}
            </span>
            <span class="d-flex">
              <label for class="side-label">Answer</label>
              {{item.answer}}
            </span>
            <hr v-if="syllabus.faqs.length > 1" />
          </div>
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
          <div class="curriculum-header">
            <span><i class="fa fa-check-square"></i></span> <h5>Course Availability</h5>
         </div>

          <div class="">{{syllabus.availability}}</div>
        </b-col>
      </b-row>

      <b-row class="border p-3 my-4">
        <b-col>
         <div class="curriculum-header">
            <span><i class="fa fa-comment"></i></span><h5>Comments</h5>
         </div>
          <div class="">{{syllabus.comments}}</div>
        </b-col>
      </b-row>
    </b-container>
    <!-- form ends here  -->
  </div>
</template>

<script>
export default {
  props: ["admin"],
  data() {
    return {
      syllabus: []
    };
  },
  mounted() {
    this.getSyllabus();
  },
  methods: {
    getSyllabus() {
      let data = { syllabus: this.syllabus };
      axios
        .get(`/api/syllabus/${this.$route.params.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`
          }
        })
        .then(res => {
          if (res.status == 200) {
            this.syllabus = JSON.parse(res.data.syllabus);
          }
        });
    }
  }
};
</script>

<style scoped>
.body {
  padding: 40px 30px 70px;
  position: relative;

}
.popup-overlay {
  position: absolute;
  width: 100%;
  height: 100vh;
  background: rgba(255, 255, 255, 0.7);
  z-index: 1;
  top: 0;
  left: 0;
}
.suggestion-box {
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
  background: white;
  border-radius: 5px;
  height: 250px;
  width: 50%;
  padding: 15px;
  position: absolute;
  bottom: 50%;
 transform: translate(50%, 50%);
  right: 50%;
}
.row.border {
  background: #fff;
  border: 2px dashed #008e3a !important;
  border-radius: 5px;
}
.fa,.fas{
color: #008e3a;
}
.suggestion-box p {
  font-size: 24px;
  font-weight: bold;
  text-align: center;
}
.close {
  position: absolute;
  right: 5px;
  top: 5px;
}
form {
  padding: 20px;
  padding-bottom: 60px;
  background: white;
  font-size: 14px;
}
h5 {
  margin-bottom: 24px;
  font-size: 1.4em;
}
.side-label {
  width: 150px;
}
.form-text {
  margin-bottom: 18px;
}
.curriculum-header{
  display: flex;
}
.curriculum-header i{
  font-size: 24px;
  padding-right: 10px;
}
</style>
<template>
  <div class="body">
    <h3 class="text-center my-3">Curriculum</h3>
    <div class="form-group grade-level mx-0" v-if="curriculum">
      <h4 class="toCaps">
        <span class="text-muted">Grade Level :</span>
        {{curriculum.grade_level}}
      </h4>
    </div>
    <b-container >
      <b-row class="border p-3 my-4"  v-if="curriculum.profile">
        <b-col>
         <div class="curriculum-header">
            <span><i class="fa fa-graduation-cap" aria-hidden="b-rowue"></i></span> <h5>Student Profile</h5>
         </div>
           <b-row>
             <b-col  md="6">  <div class="form-group d-flex">
            <label for class="mr-3 side-label">Target Age :</label>
            {{curriculum.profile.age}}
          </div></b-col>
             <b-col  md="6"> <div class="form-group d-flex">
            <label for class="mr-3 side-label">Interests :</label>
            {{curriculum.profile.interests}}
          </div></b-col>
             <b-col  md="6"> <div class="form-group d-flex">
            <label for class="mr-3 side-label">Learning Abilities :</label>
            {{curriculum.profile.abilities}}
          </div></b-col>
             <b-col md="6"> <div class="form-group d-flex">
            <label for class="mr-3 side-label">Other Pecularities :</label>
            {{curriculum.profile.others}}
          </div></b-col>
           </b-row>
        
         
         
         
        </b-col>
      </b-row>

      <b-row class="border p-3 my-4">
        <b-col>
          <div class="curriculum-header">
            <span><i class="fa fa-leanpub" aria-hidden="b-rowue"></i></span><h5>Overarching Learner Outcome</h5>
          </div>
          <div class="form-group">
            <span v-for="(item,idx) in curriculum.learner_outcome" :key="idx" class="mb-2 d-flex">
              <span class="mr-3">{{idx+1}}.</span>
              {{item.name}}
            </span>
          </div>
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
         <div class="curriculum-header">
            <span><i class="fa fa-bullseye" aria-hidden="b-rowue"></i></span><h5>Target skills to be learned</h5>
         </div>

          <span v-for="(item,idx) in curriculum.target_skills" :key="idx" class="mb-2 d-flex">
            <span class="mr-3">{{idx+1}}.</span>
            {{item.name}}
          </span>
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
          <div class="curriculum-header">
            <span><i class="fa fa-handshake-o" aria-hidden="b-rowue"></i></span><h5>Required subjects</h5>
          </div>

          <span v-for="(item,idx) in curriculum.prerequisite" :key="idx" class="mb-2 d-flex toCaps">
            <span class="mr-3">{{idx+1}}.</span>
            {{item}}
          </span>
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
        <div class="curriculum-header">
           <span><i class="fa fa-puzzle-piece" aria-hidden="b-rowue"></i></span> <h5>Delivery Methods</h5>
        </div>

          <span v-for="(item,idx) in curriculum.delivery_methods" :key="idx" class="mb-2 d-flex">
            <span class="mr-3">{{idx+1}}.</span>
            {{item.name}}
          </span>
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
         <div class="curriculum-header">
           <span><i class="fa fa-pie-chart" aria-hidden="b-rowue"></i></span> <h5>Assessment</h5>
         </div>

          <span v-for="(item,idx) in curriculum.assessments" :key="idx" class="mb-2 d-flex">
            <span class="mr-3">{{idx+1}}.</span>
            {{item.name}}
          </span>
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
        <div class="curriculum-header">
           <span><i class="fa fa-question-circle" aria-hidden="b-rowue"></i></span> <h5>Frequently Asked Questions</h5>
        </div>
        <b-row class="">
        <b-col>
         
          <div role="tablist" v-for="(item,idx) in curriculum.faqs" :key="idx">
            <b-card no-body class="mb-1">
              <b-card-header header-tag="header" class="p-2" role="tab">
                <div
                  block
                  v-b-toggle="item.question.replace(/[^a-z0-9]/gi, '').replace(/\$/g, '')"
                  variant="secondary"
                  class="text-left"
                >{{item.question}}</div>
              </b-card-header>
              <b-collapse
                :id="item.question.replace(/[^a-z0-9]/gi, '').replace(/\$/g, '')"
                accordion="my-accordion"
                role="tabpanel"
              >
                <b-card-body>
                  <b-card-text>{{item.answer}}</b-card-text>
                </b-card-body>
              </b-collapse>
            </b-card>
          </div>
        </b-col>
      </b-row>
         
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
          <div class="curriculum-header"> <span><i class="fa fa-check-square" aria-hidden="b-rowue"></i></span><h5>Course Availability</h5></div>
          {{curriculum.availability}}
        </b-col>
      </b-row>

      <b-row class="border p-3 my-4">
        <b-col>
           <div class="curriculum-header">
             <span><i class="fa fa-comment" aria-hidden="b-rowue"></i></span><h5>Comments</h5>
           </div>
          {{curriculum.comments}}
        </b-col>
      </b-row>
    </b-container>
  
  </div>

</template>

<script>
export default {
  props: ["tutor"],
  data() {
    return {
      curriculum: {}
    };
  },
  mounted() {
    this.getCurriculum();
  },
  methods: {
    getCurriculum() {
      axios
        .get(`/api/tutor-curriculum/${this.$route.params.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`
          }
        })
        .then(res => {
          if (res.status == 200) {
            this.curriculum = JSON.parse(res.data.curriculum);
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
.table{
  font-size: 17px;
  border:3px solid #41cee2;
  border-radius: 5px;
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
  border-bottom-left-radius: 5px;
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
  border: 2px dashed #41cee2 !important;
  border-radius: 5px;
}
.fa,.fas{
color: #41cee2;
}
.suggestion-box p {
  font-size: 24px;
  font-weight: bold;
  text-align: center;
}
label{
  color: #888888;
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
  font-size: 15px;
}
h5 {
  margin-bottom: 24px;
}
/* .side-label {
  width: 150px;
} */
.form-text {
  margin-bottom: 18px;
}
.underline {
  width: 80px;
  height: 3px;
  background-color: #0a4065;
}
.class-information {
  padding-top: 20px;
}
.class-information h3 {
  margin-bottom: 0 !important;
}
.class-information-inner {
  display: flex;
  justify-content: space-around;
  padding-top: 20px;
}
.class-information-inner span {
  color: #808080;
}
.curriculum-header{
  display: flex;
}
.curriculum-header i{
  font-size: 24px;
  padding-right: 10px;
}
.view-syllabus {
  background: #fff;
  box-shadow: 5px 4px 13px rgba(249, 247, 240, 0.25);
  padding: 30px;
  border-radius: 10px;
}
.subject-description {
  padding-top: 15px;
}
.contain-syllabus {
  padding: 30px;
}
b-col{
  padding:2.50rem;
}
.grade-level{
  padding-left: 8px;
}
</style>
<template>
 
  <div class="viewsyllabus pt-3 pl-3">
    <h2 class="text-center">Syllabus</h2>
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

          <div class="form-group">
            <h6 class="toCaps">
              <span class="text-muted">Subject :</span>
              {{syllabus.subject}}
            </h6>
          </div>

          <div class="form-group">
            <label for>Description :</label>
            {{syllabus.description}}
          </div>

          <div class="form-group">
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

          <div class="form-group">
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
            <span><i class="fa fa-question-circle"></i></span><h5>FAQs </h5>
         </div>

       
          <b-row class="">
        <b-col>
         
          <div role="tablist" v-for="(item,idx) in syllabus.faqs"  :key="idx">
            <b-card no-body class="mb-1">
              <b-card-header header-tag="header" class="p-2" role="tab">
                <div
                  block
                  v-b-toggle="item.question.replace(/[^a-z0-9]/gi, '').replace(/\$/g, '')"
                  variant="secondary"
                  class="text-left faq"
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
          <div class="curriculum-header">
            <span><i class="fa fa-check-square"></i></span> <h5>Course Availability</h5>
         </div>

          <div class="form-group">{{syllabus.availability}}</div>
        </b-col>
      </b-row>

      <b-row class="border p-3 my-4">
        <b-col>
         <div class="curriculum-header">
            <span><i class="fa fa-comment"></i></span><h5>Comments</h5>
         </div>
          <div class="form-group">{{syllabus.comments}}</div>
        </b-col>
      </b-row>
    </b-container>
    <!-- form ends here  -->
  </div>
</template>

<script>
export default {
  props: ["tutor"],
  data() {
    return {
      syllabus: [],
    };
  },
  mounted() {
    this.getSyllabus();
  },
  methods: {
    getSyllabus() {
      let data = { syllabus: this.syllabus };
      axios
        .get(`/api/tutor-syllabus/${this.$route.params.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.syllabus = JSON.parse(res.data.syllabus);
          }
        });
    },
  },
};
</script>

<style scoped>
.body {
  padding: 30px 30px 70px;
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
h5 {
  margin-bottom: 24px;
  font-size: 1.4em;
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
strong {
  margin-bottom: 24px;
  font-size: 1.3em;
}
.side-label {
  width: 150px;
}
.form-text {
  margin-bottom: 18px;
}
.underline {
  width: 80px;
  height: 3px;
  background-color: #0a4065;
}
.faq{
  
}
.row.border {
  background: #fff;
  border: 2px dashed #41cee2 !important;
  border-radius: 5px;
}
.fa,.fas{
color: #41cee2;
}
.class-information {
  padding-top: 20px;
}
.class-information strong {
  margin-bottom: 0 !important;
}
.class-information-inner {
  display: flex;
  justify-content: space-around;
  padding-top: 20px;
}

.class-information-inner span {
  color: #333333;
}
.view-syllabus {
  background: #fff;
  box-shadow: 5px 4px 13px rgba(249, 247, 240, 0.25);
  padding: 30px;
  border-radius: 10px;

  border:3px solid #41cee2;

}
.subject-description {
  padding-top: 15px;
}
.contain-syllabus {
  padding: 30px;
}
.curriculum-header{
  display: flex;
}
.curriculum-header i{
  font-size: 24px;
  padding-right: 10px;
}
</style>
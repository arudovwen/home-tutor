<template>
  <div class="body">
    <h3 class="text-center my-3">Curriculum</h3>
    <div class="form-group" v-if="curriculum">
      <h4 class="toCaps">
        <span class="text-muted">Grade Level :</span>
        {{curriculum.grade_level}}
      </h4>
    </div>
    <b-container class>
      <b-row class="border p-3 my-4" v-if="curriculum.profile">
        <b-col>
          <div class="curriculum-header">
            <span>
              <i class="fa fa-graduation-cap"></i>
            </span>
            <h5>Student Profile</h5>
          </div>
          <b-row>
            <b-col>
              <div class="form-group d-flex">
                <label for class="mr-3 side-label">Target Age :</label>
                {{curriculum.profile.age}}
              </div>
              <div class="form-group d-flex">
                <label for class="mr-3 side-label">Interests :</label>
                {{curriculum.profile.interests}}
              </div>
            </b-col>
            <b-col>
              <div class="form-group d-flex">
                <label for class="mr-3 side-label">Learning Abilities :</label>
                {{curriculum.profile.abilities}}
              </div>
              <div class="form-group d-flex">
                <label for class="mr-3 side-label">Other Pecularities :</label>
                {{curriculum.profile.others}}
              </div>
            </b-col>
          </b-row>
        </b-col>
      </b-row>

      <b-row class="border p-3 my-4">
        <b-col>
          <div class="curriculum-header">
            <span>
              <i class="fa fa-leanpub"></i>
            </span>
            <h5>Overarching Learner Outcome</h5>
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
            <span>
              <i class="fa fa-bullseye"></i>
            </span>
            <h5>Target skills to be learned</h5>
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
            <span>
              <i class="fa fa-handshake-o"></i>
            </span>
            <h5>Required subjects</h5>
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
            <span>
              <i class="fa fa-puzzle-piece"></i>
            </span>
            <h5>Delivery Methods</h5>
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
            <span>
              <i class="fa fa-pie-chart"></i>
            </span>
            <h5>Assessment</h5>
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
            <span>
              <i class="fa fa-question-circle"></i>
            </span>
            <h5>FAQs (Optional)</h5>
          </div>

          <div v-for="(item,idx) in curriculum.faqs" :key="idx" class="mb-3">
            <span class="d-flex mb-2">
              <label for="Question" class="side-label">Question</label>
              {{item.question}}
            </span>
            <span class="d-flex">
              <label for class="side-label">Answer</label>
              {{item.answer}}
            </span>
            <hr v-if="curriculum.faqs.length > 1" />
          </div>
        </b-col>
      </b-row>
      <b-row class="border p-3 my-4">
        <b-col>
          <div class="curriculum-header">
            <span>
              <i class="fa fa-check-square"></i>
            </span>
            <h5>Course Availability</h5>
          </div>
          {{curriculum.availability}}
        </b-col>
      </b-row>

      <b-row class="border p-3 my-4">
        <b-col>
          <div class="curriculum-header">
            <span>
              <i class="fa fa-comment"></i>
            </span>
            <h5>Comments</h5>
          </div>
          {{curriculum.comments}}
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
      curriculum: {},
    };
  },
  mounted() {
    this.getCurriculum();
  },
  methods: {
    getCurriculum() {
      axios
        .get(`/api/curriculum/${this.$route.params.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.curriculum = JSON.parse(res.data.curriculum);
          }
        });
    },
  },
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
.row.border {
  background: #fff;
  border: 2px dashed #008e3a !important;
  border-radius: 5px;
}
.fa,.fas{
color: #008e3a;
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
h5 {
  margin-bottom: 24px;
}
.side-label {
  width: 150px;
}
.form-text {
  margin-bottom: 18px;
}
.curriculum-header {
  display: flex;
}
.curriculum-header i {
  font-size: 24px;
  padding-right: 10px;
}
</style>